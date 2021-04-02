#!/usr/bin/env python
from bs4 import BeautifulSoup
import requests
import json
citynames=[]
htm =  requests.get("https://www.foodpanda.my/")

soup0=BeautifulSoup( htm.text,'lxml')

rests0=soup0.find('ul',class_='city-list')

list_0=rests0.find_all('li')

for url in list_0:
    url_city=url.find('a',class_='city-tile')['href'].strip()
    cityname=url_city[6:]
    citynames.append(cityname)

json_citynames=json.dumps(citynames)

print(json_citynames)