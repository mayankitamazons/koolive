from bs4 import BeautifulSoup
import requests
import sys
import json

all_r=[]
image_url=[]
r_name=[]
rating=[]
rating_count=[]
tags=[]
budget=[]

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


list_elements=rests.find_all('li')
# print(pic)

for le in list_elements:
    
    image=le.find('div',class_='vendor-picture b-lazy')
    if image is None:
        pass

    else:
        image=image['data-src']
        image=(image.split('?'))[0]
        image_url.append(image)

    
    
    try:
        r_info=le.find('span', class_='name fn')
        # r_info=le.find('figcaption', class_='vendor-info')
        r_name.append(r_info.text.strip())
        
    except:
        pass


for url in list_1:
    url=str(url)
    url=(((url.split())[5]).split('='))[1]
    url=url[1:-2]
    all_r.append(url)

final_dictionary={}

for i in range(len(all_r)):
    inner_dct={}
    inner_dct['Restaurant_Name']=r_name[i]
    inner_dct['URL']=all_r[i]
    inner_dct['Image_URL']=image_url[i]
    final_dictionary[i]=inner_dct

# print(final_dictionary)
json_out=json.dumps(final_dictionary)
print(json_out)    

     

