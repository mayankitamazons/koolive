from bs4 import BeautifulSoup
import requests
import sys

all_r=[]

# cityname=input('Enter a City Name to get the restaurants info of that city:')
cityname=sys.argv[1]
url="https://www.foodpanda.my/city/"+cityname
html =  requests.get(url)
soup=BeautifulSoup( html.text,'lxml')
rests=soup.find('ul',class_='vendor-list')

try:
    list_1=rests.find_all('a',class_='hreview-aggregate url')
except:
    pass

for url in list_1:
    url=str(url)
    url=(((url.split())[5]).split('='))[1]
    url=url[1:-2]
    all_r.append(url)

total_rests=len(all_r)        
print('Total Restaurants Count: '+ str(total_rests))

print()
u=0
for rest_url in all_r:
    u=u+1
    rest_name=(rest_url.split('/'))[3]
    print('Restaurant #'+str(u)+' '+ 'Name: '+ rest_name)