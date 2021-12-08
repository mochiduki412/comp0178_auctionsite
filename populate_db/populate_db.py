from random import choice, randint
from datetime import date
from os import getenv
from uuid import uuid1

from sqlalchemy.ext.automap import automap_base
from sqlalchemy import create_engine, MetaData, Table, select
from sqlalchemy.orm import Session
from sqlalchemy.exc import IntegrityError
from faker import Faker
from faker.providers import BaseProvider
from faker_vehicle import VehicleProvider


# setup sqlalchemy
DB, SERV = getenv('DB') or "comp0178db", getenv('SERV') or "localhost"
USER, PASS = getenv('USER') or "root", getenv('PASS') or ""
connection_url = f'mysql+pymysql://{USER}:{PASS}@{SERV}/{DB}'
# NOTE - use this in case unable to connect to SQL server 'mysql+pymysql://root@localhost:8888/comp0178db'
engine = create_engine(connection_url, echo = False)
metadata = MetaData()
session = Session(engine)


# reflect table to class
Base = automap_base()
Base.prepare(engine, reflect = True)
Auction = Base.classes.Auction
Bid = Base.classes.Bid
User = Base.classes.User
# print(Auction.__dict__.keys())


# faker custom data provider
class UidProvider(BaseProvider):
    def __init__(self, generator):
        super().__init__(generator)
        self._uids = tuple(map(lambda arr: arr[0], session.query(User.userId).all()))

    def get_uid(self):
        return choice(self._uids)

class AuctionIdProvider(BaseProvider):
    def __init__(self, generator):
        super().__init__(generator)
        self._auctionIds = tuple(map(lambda arr: arr[0], session.query(Auction.auctionId).all()))

    def get_auctionId(self):
        return choice(self._auctionIds)

fake = Faker()
fake.add_provider(VehicleProvider)


# helpers
def commit_or_rollback(session):
    try:
        session.commit()
    except:
        session.rollback()
        pass


# funcs to populate db
def pop_users(cnt = 10):
    i = 0
    while i < cnt:
        user = User(
            userId = str(uuid1()),
            firstName = fake.first_name(),
            lastName = fake.last_name(),
            email = fake.email(),
            password = 'test',
            type = choice(('buyer', 'seller',)),
        )
        session.add(user)
        try:
            commit_or_rollback(session)
            i += 1
        except IntegrityError: # happens to generate non-unique name/email etc.
            raise

def pop_auctions(cnt = 20):
    OPEN = 1
    i = 0 
    while i < cnt:
        auction = Auction(
            sellerId = fake.get_uid(),
            title = 'fake_' + fake.vehicle_make(),
            reservePrice = randint(200, 400),
            startingPrice = randint(10, 100),
            itemDescription = fake.text(max_nb_chars = 50),
            itemCat = fake.vehicle_category(),
            endDate = fake.date_between(start_date='today', end_date='+1y'),
            curBidPrice = randint(100, 500),
            curBidderId = fake.get_uid(),
            status = OPEN
        )
        session.add(auction)
        try:
            commit_or_rollback(session)
            i += 1
        except IntegrityError: # happens to generate non-unique name/email etc.
            pass

def pop_bids(cnt = 40):
    for i in range(cnt):
        bid = Bid(
            auctionId = fake.get_auctionId(),
            bidderId = fake.get_uid(),
            bidPrice = randint(100, 500),
        )
        session.add(bid)
        commit_or_rollback(session)


def run():
    pop_users(5)
    fake.add_provider(UidProvider)
    pop_auctions(10)
    fake.add_provider(AuctionIdProvider) # to delay init for getting newly added auctionId. Can't think of better other ways for now.
    pop_bids(20)
    print('Completed.')

run()