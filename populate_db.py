from random import choice, randint
from datetime import date
from os import getenv

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
Base = automap_base()
Base.prepare(engine, reflect = True)
session = Session(engine)


# reflect table to class
Auction = Base.classes.Auction
Bid = Base.classes.Bid
User = Base.classes.User
# print(Auction.__dict__.keys())


# configure faker
class UidProvider(BaseProvider):
    def __init__(self, generator):
        super().__init__(generator)
        self._uids = tuple(map(lambda arr: arr[0], session.query(User.userId).all()))

    def get_uid(self):
        return choice(self._uids)

fake = Faker()
fake.add_provider(UidProvider)
fake.add_provider(VehicleProvider)


# helpers
def commit_or_rollback(session):
    try:
        session.commit()
    except:
        session.rollback()
        raise


# funcs to populate db
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
    pass

def pop_users(cnt = 10):
    pass


def run():
    pop_auctions(10)
    print('Completed.')

run()