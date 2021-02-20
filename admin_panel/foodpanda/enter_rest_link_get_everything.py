from bs4 import BeautifulSoup
import requests
import csv
import json
import re
import sys

headers = {'Accept-Language': 'zh'}

restaurant_full_name=[]
restaurant_full_name_zh=[]    #
dish_category_title=[]
dish_category_title_zh=[]     # 
dish_name=[]
dish_name_zh=[]               #
dish_description=[] 
dish_description_zh=[]        #      
original_price=[]
discounted_price=[]
image_url=[]
prod_variations=[]
prod_variations_zh=[]         #

address=[]
address_zh=[]                 #
opening_time=[]
opening_time_zh=[]             #
delivery_hours=[]
delivery_hours_zh=[]           #
rating=[]
rating_count=[]
rest_image_url=[]
longitude=[]
latitude=[]
tags=[]
telephone=[]
postalcode=[]
restaurant_url=[]
budget_symbol=[]

######################################eng menu code############################################333

# rest_url=input('Enter a Restaurant URL to scrape its menu: ')
rest_url=sys.argv[1]

html =  requests.get(rest_url)

soup=BeautifulSoup( html.text,'lxml')

######################################eng info code##########################################


data_all=soup.find_all('div',class_='modal fade rich-description')

for data in data_all:
    symbol=''
    try:
        sym=data.find_all('span',class_='budget-symbol--filled')
        for ele in sym:
            symbol=symbol+ele.text.strip()
    except:
        symbol="N/A"

    try:
        rest_img=data.find('div',class_='b-lazy vendor-picture')['data-src']
        res_img=(rest_img.split('?'))[0]
    except:
        rest_img="N/A"

    try:
        rat=data.find('span',class_='rating').text.strip() 
    except:
        rat="N/A"
    try:
        count=data.find('span',class_='count').text.strip() 
    except:
        count="N/A"




    tag_all_in_one=data.find('ul',class_='vendor-cuisines')
    tag_all_in_one=(tag_all_in_one.text.replace(' ','').strip())
    all_tag_list=tag_all_in_one.split()
    tgs=""

    for each_t in all_tag_list[3:]:
        tgs=tgs+"|"+each_t
    
    




    timings=data.find('span',class_='schedule-times')
    op_time=timings.text.strip()



    hours=data.find('ul',class_='vendor-delivery-times')
    hours=(hours.text.replace(' ','').strip())
    

    
    loc=data.find('p',class_='vendor-location')
    loc=loc.text.strip()
    



script=soup.find_all('script')[1]
script=str(script)

try:
    longitude_index=script.find('longitude')
    long=script[longitude_index+12:]
    long=(long.split())[0]
    


    latitude_index=script.find('latitude')
    lat=script[latitude_index+10:]
    lat=(lat.split())[0]
    lat=lat[:-1]
    
except:
    long=""
    lat=""


try:
    tel_index=script.find('"tel')
    tel=script[tel_index+14:]
    tel=(((tel.split())[0]).split('"'))[0]

except:
    tel=''


try:
    postal_index=script.find('postalCode')
    postal=script[postal_index+14:]
    postal=(((postal.split())[0]).split('"'))[0]
    
except:
    postal=''

try:
    re_url_index=script.find('url')
    re_url=script[re_url_index+7:]
    re_url=(re_url.split('"'))[0]

    

except:
    re_url=''


###################################eng menu code continue#################################

item=soup.find_all('div',class_='dish-category-section__inner-wrapper')


h1=soup.find('h1',class_='fn')    #to get restaurant name 
restaurant_name=h1.text

try:
    dictionary=soup.find('section',class_='vendor-section')['data-vendor']
    dct=json.loads(dictionary)
    toppings_layer_structure=dct['toppings']
    # pprint(toppings_layer_structure)
except:
    toppings_layer_structure=''


for data in item:
    h2=data.find('h2',class_='dish-category-title').text.strip()
    titles=data.find_all('h3',class_='dish-name fn p-name')
    for title in titles:
        dish_category_title.append(h2)
        item=title.text.strip()
        dish_name.append(item)
        restaurant_full_name.append(restaurant_name)
        # restaurant_url.append(rest_url)
        budget_symbol.append(symbol)           ###### other info lists appended here  ##############
        rest_image_url.append(rest_img)
        tags.append(tgs)
        rating.append(rat) 
        rating_count.append(count)  #no. of people votes taken for the rating
        opening_time.append(op_time)
        delivery_hours.append(hours)
        address.append(loc)
        telephone.append(tel)
        latitude.append(lat)
        longitude.append(long)
        postalcode.append(postal)
        restaurant_url.append(re_url)
            

    title_descs=data.find_all('div',class_='dish-info')
    for title_desc in title_descs:       
        try:
           desc=title_desc.find('p',class_='dish-description e-description').text.strip()
        except:
            desc="Dish Description not available"
        dish_description.append(desc)


    prices=data.find_all('span',class_='price p-price')
    for each_price in prices:
        price=each_price.text
        price=''.join(price.split())
        indices = [s.start() for s in re.finditer('MYR', price)]

        if (len(indices)==2):
            dis_price=price[indices[0]:indices[1]]
            org_price=price[indices[1]:]
        else:
            dis_price='N/A'
            org_price=price[indices[0]:]

        try:
            x=price.index('from')
            dis_price="from "+dis_price
            org_price="from "+org_price
        except:
            pass        
        original_price.append(org_price)
        discounted_price.append(dis_price)


    pic=data.find_all('li',class_='dish-card h-product menu__item')
    for img in pic:
        image=img.find('div',class_='photo u-photo b-lazy')
        
        if image is None:
            image_url.append('N/A')

        else:
            image=image['data-src']
            image=(image.split('?'))[0]
            image_url.append(image)




# if (len(image_url)!=len(dish_name)):
#     image_url=[]
#     for i in range(len(dish_name)):
#         image_url.append("N/A")



try:
    list_item=soup.find_all('li',class_='dish-card h-product menu__item')
    for opt in list_item:
        # titles=opt.find('h3',class_='dish-name fn p-name')
        # name_item=titles.find('span').text.strip()
        # print('name=',name_item) 
        topping_string=''
        dic=opt['data-object']
        dct=json.loads(dic)
        toppings=dct['product_variations']
        for each_dic in toppings:
            
            list_of_name_topping=each_dic['name']
            if list_of_name_topping is None:
                pass
            else:
                topping_string=topping_string+'\n\n'+list_of_name_topping
            

            list_of_price=each_dic['price']
            topping_string=topping_string+' ,Price:  '+str(list_of_price)
            list_of_toppings=each_dic['toppings']
            list_of_topping_ids=each_dic['topping_ids']

            if  list_of_name_topping==None and len(list_of_toppings)==0 and len(list_of_topping_ids)==0:
                topping_string=topping_string+"     no sub category    "
                
            elif len(list_of_topping_ids)!=0:
                for idno in list_of_topping_ids:
                    topping_string=topping_string+'\n'+toppings_layer_structure[str(idno)]['name']
                    topping_string=topping_string+':                (quantity_maximum: '+str(toppings_layer_structure[str(idno)]['quantity_maximum'])
                    topping_string=topping_string+', quantity_minimum: '+str(toppings_layer_structure[str(idno)]['quantity_minimum'])+')'           
                    topping_options= toppings_layer_structure[str(idno)]['options']
                    for each_dic_option in topping_options:
                        topping_string=topping_string+'\n'+'Name: '+ each_dic_option['name']
                        if (each_dic_option['price']!=0):
                            topping_string=topping_string+', Price: '+ str(each_dic_option['price'])
            else:
                pass
        prod_variations.append(topping_string)

except:
    prod_variations=[]
    for i in range(len(dish_name)):
        prod_variations.append("")







############################chinese menu code###############################################

htmlzh =  requests.get(rest_url, headers=headers)

soupzh=BeautifulSoup( htmlzh.text,'lxml')



######################################chinese info code##########################################


data_all_zh=soupzh.find_all('div',class_='modal fade rich-description')

for data in data_all_zh:
    timings=data.find('span',class_='schedule-times')
    op_time=timings.text.strip()



    hours=data.find('ul',class_='vendor-delivery-times')
    hours=(hours.text.replace(' ','').strip())
    

    
    loc=data.find('p',class_='vendor-location')
    loc=loc.text.strip()
    

###################################chinese menu code continue#################################



itemzh=soupzh.find_all('div',class_='dish-category-section__inner-wrapper')


h1=soupzh.find('h1',class_='fn')    #to get restaurant name 
restaurant_name=h1.text

try:
    dictionary=soupzh.find('section',class_='vendor-section')['data-vendor']
    dct=json.loads(dictionary)
    toppings_layer_structure=dct['toppings']
    # pprint(toppings_layer_structure)
except:
    toppings_layer_structure=''


for data in itemzh:
    h2=data.find('h2',class_='dish-category-title').text.strip()
    titles=data.find_all('h3',class_='dish-name fn p-name')
    for title in titles:
        dish_category_title_zh.append(h2)
        item=title.text.strip()
        dish_name_zh.append(item)
        restaurant_full_name_zh.append(restaurant_name)
        opening_time_zh.append(op_time)          ###### other info lists appended here  ##############
        delivery_hours_zh.append(hours)
        address_zh.append(loc)

        

    title_descs=data.find_all('div',class_='dish-info')
    for title_desc in title_descs:       
        try:
           desc=title_desc.find('p',class_='dish-description e-description').text.strip()
        except:
            desc="Dish Description not available"
        dish_description_zh.append(desc)





try:
    list_itemzh=soupzh.find_all('li',class_='dish-card h-product menu__item')
    for opt in list_itemzh:
        # titles=opt.find('h3',class_='dish-name fn p-name')
        # name_item=titles.find('span').text.strip()
        # print('name=',name_item) 
        topping_string=''
        dic=opt['data-object']
        dct=json.loads(dic)
        toppings=dct['product_variations']
        for each_dic in toppings:
            
            list_of_name_topping=each_dic['name']
            if list_of_name_topping is None:
                pass
            else:
                topping_string=topping_string+'\n\n'+list_of_name_topping
            

            list_of_price=each_dic['price']
            topping_string=topping_string+' ,Price:  '+str(list_of_price)
            list_of_toppings=each_dic['toppings']
            list_of_topping_ids=each_dic['topping_ids']

            if  list_of_name_topping==None and len(list_of_toppings)==0 and len(list_of_topping_ids)==0:
                topping_string=topping_string+"     no sub category    "
                
            elif len(list_of_topping_ids)!=0:
                for idno in list_of_topping_ids:
                    topping_string=topping_string+'\n'+toppings_layer_structure[str(idno)]['name']
                    topping_string=topping_string+':                (quantity_maximum: '+str(toppings_layer_structure[str(idno)]['quantity_maximum'])
                    topping_string=topping_string+', quantity_minimum: '+str(toppings_layer_structure[str(idno)]['quantity_minimum'])+')'           
                    topping_options= toppings_layer_structure[str(idno)]['options']
                    for each_dic_option in topping_options:
                        topping_string=topping_string+'\n'+'Name: '+ each_dic_option['name']
                        if (each_dic_option['price']!=0):
                            topping_string=topping_string+', Price: '+ str(each_dic_option['price'])
            else:
                pass
        prod_variations_zh.append(topping_string)

except:
    prod_variations_zh=[]
    for i in range(len(dish_name)):
        prod_variations_zh.append("")




final_dct={'Restaurant Name': restaurant_full_name,
'Restaurant Name in Chinese': restaurant_full_name_zh,
'Restaurant URL': restaurant_url,
'Address':address,
'Address Chinese':address_zh,
'Budget Symbol': budget_symbol,
'Opening Time':opening_time,
'Opening Time Chinese':opening_time_zh,
'Delivery Hours':delivery_hours,
'Delivery Hours Chinese':delivery_hours_zh,
'Rating':rating,
'Rating Count':rating_count,
'Image URL':image_url,
'Longitude':longitude,
'Latitude':latitude,
'Tags': tags,
'Tel No.': telephone,
'Postal Code': postalcode,
'Dish Category Title':dish_category_title,
'Dish Category Title Chinese':dish_category_title_zh,
'Dish Name':dish_name,
'Dish Name Chinese':dish_name_zh,
'Dish Description Chinese':dish_description_zh,
'Original Price':original_price,
'Discounted Price':discounted_price,
'Product Variations': prod_variations,
'Product Variations Chinese': prod_variations_zh,
'Image URL':image_url}


# for k,v in final_dct.items():
#     print(k,len(v))



json_out=json.dumps(final_dct)
print(json_out)

#####To output json as file############
# filename=(restaurant_name.replace(' ','_')).replace(':','_')+'.json'
# with open(filename, "w") as outfile: 
#     outfile.write(json_out) 
# print('Exported to json')
# print('Check '+filename)


########To output as csv###########
# import pandas as pd
# df=pd.DataFrame(final_dct)
# filename=(restaurant_name.replace(' ','_')).replace(':','_')+'.csv'
# df.to_csv(filename)
# print('Exported to csv')
# print('Check '+filename)



