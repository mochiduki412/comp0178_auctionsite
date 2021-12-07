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
engine = create_engine(f'mysql+pymysql://{USER}:{PASS}@{SERV}/{DB}', echo = False)
metadata = MetaData()
session = Session(engine)


# reflect table to class
Base = automap_base()
Base.prepare(engine, reflect = True)
Auction = Base.classes.Auction
Bid = Base.classes.Bid
User = Base.classes.User


# faker custom data provider
class UidProvider(BaseProvider):
    def __init__(self, generator):
        super().__init__(generator)
        res = session.query(User.userId, User.type).filter(User.type == 'buyer').all()
        self._uids = tuple(map(lambda arr: arr[0], res))

    def get_uid(self):
        return choice(self._uids)

class AuctionIdProvider(BaseProvider):
    def __init__(self, generator):
        super().__init__(generator)
        self._auctionIds = tuple(map(lambda arr: arr[0], session.query(Auction.auctionId).all()))

    def get_auctionId(self):
        return choice(self._auctionIds)


# helpers
def commit_or_rollback(session):
    try:
        session.commit()
    except:
        session.rollback()
        raise

def truncate_tables(*models):
    for m in models:
        session.query(m).delete()


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
            print(str(e))

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
            endDate = fake.date_between(start_date='-1y', end_date='+1y'),
            status = OPEN
        )
        session.add(auction)
        try:
            commit_or_rollback(session)
            i += 1
        except IntegrityError as e:
            print(str(e))

def pop_bids(cnt = 40):
    for i in range(cnt):
        bid = Bid(
            auctionId = fake.get_auctionId(),
            bidderId = fake.get_uid(),
            bidPrice = randint(100, 500),
        )
        session.add(bid)
        commit_or_rollback(session)


fake = Faker()
fake.add_provider(VehicleProvider)
def run():
    # truncate_tables(User)
    # pop_users(5)
    
    # truncate_tables(Auction, Bid)
    fake.add_provider(UidProvider)
    pop_auctions(20)
    fake.add_provider(AuctionIdProvider)
    pop_bids(40)
    print('Completed.')

run()