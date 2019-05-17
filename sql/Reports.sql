-- Trader

-- Weekly
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , '$'||(i.quantity * p.prod_price) as paid_amount, o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = :SESSION_USER_ID
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and i.delivery = 'Complete'
and o.payment_status = 'Paid'
and to_char(order_date,'YYYY-MM-DD') >= to_char(sysdate-7, 'YYYY-MM-DD')

-- Monthly
select to_char(order_date,'MONTH YYYY') as Month, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = :SESSION_USER_ID
group by to_char(order_date,'MONTH YYYY'), p.prod_title

-- Yearly
select to_char(order_date,'YYYY') as year, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = :SESSION_USER_ID
group by to_char(order_date,'YYYY'), p.prod_title

-- Daily Orders
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot
,delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = :SESSION_USER_ID
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'
and to_char(order_date,'YYYY-MM-DD') = to_char(sysdate, 'YYYY-MM-DD')

-- All Orders
select i.invoice_id, o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = :SESSION_USER_ID
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'


-- Dashboard
select null as lvl,
'Total Orders' as label,
'javascript:void(0);' as target,   
count(distinct i.invoice_no) as value 
from invoice i, shop s, orders o 
where i.shop_id = s.shop_id 
and i.invoice_no = o.invoice_no 
and o.payment_status = 'Paid' 
and s.fk_trader_id = :SESSION_USER_ID

union all

select null as lvl,
'Total Customers' as label,
'javascript:void(0);' as target,
count(distinct customer_id) as customer_count
            from orders o, shop s, invoice i
            where s.shop_id = i.shop_id
            and i.invoice_no = o.invoice_no
            and s.fk_trader_id = :SESSION_USER_ID

union all

select null as lvl,
'Total Products' as label,
'javascript:void(0);' as target,
count(*) as value
from product p, shop s
where 
p.fk_shop_id = s.shop_id
and s.fk_trader_id = :SESSION_USER_ID

union all
select null as lvl,
'Total Shops' as label,
'javascript:void(0);' as target,
count(*) as value
from shop s
where s.fk_trader_id = :SESSION_USER_ID


-- Bar graph
select prod_title, sum(quantity) no_of_sales
from invoice i, shop s, orders o, product p
where i.shop_id = s.shop_id
and s.fk_trader_id = :SESSION_USER_ID   
and i.invoice_no = o.invoice_no
and p.prod_id = i.prod_id
and o.payment_status ='Paid'
group by  prod_title


-- Stock Donut Chart
select stock, prod_title
from product p, shop s
where p.fk_shop_id = s.shop_id
and s.fk_trader_id = :SESSION_USER_ID
group by prod_title, stock

=========================================================

-- Admin

-- Dashboard
select null as lvl,
       'Total Customers' as label,
       'javascript:void(0);' as target,   
       count(*)  as value
from users
where role = 'Customer'

union all

select null as lvl,
'Total Traders' as label,
'javascript:void(0);' as target,

count(*) as value
from users 
where role = 'Trader'

union all
select null as lvl,
'Total Orders' as label,
'javascript:void(0);' as target,
count(distinct i.invoice_no) as value
from orders o, invoice i
where payment_status = 'Paid'
and i.invoice_no = o.invoice_no

union all
select null as lvl,
'Total Products' as label,
'javascript:void(0);' as target,
count(*) as value
from product

-- Bar Graph 
select prod_title, sum(quantity) no_of_sales
from invoice i, shop s, orders o, product p
where i.shop_id = s.shop_id
and i.invoice_no = o.invoice_no
and p.prod_id = i.prod_id
and o.payment_status ='Paid'
group by  prod_title
order by 2 desc

-- Sales per Shop Donut Chart
select shop_name, sum(quantity) no_of_sales
from invoice i, shop s, orders o
where i.shop_id = s.shop_id
and i.invoice_no = o.invoice_no
and o.payment_status ='Paid'
group by  shop_name
order by 2 desc

-- Customer
select user_id, firstname||' '||lastname as customer_name, username,password 
,email, address, dob, profile_picture,verified
, created_at as creation_date, status 
from users where role ='Customer'

-- Trader
select user_id, firstname||' '||lastname as trader_name, username,password ,email, address, dob, profile_picture,phone, product_line,verified, created_at as creation_date, status 
from users where role ='Trader'

-- Product
select prod_id,prod_title name, '$'||prod_price price, prod_img image, allergy_info, stock, keywords,min_order,max_order,discount 
,shop_name shop, cat_title category, prod_status status
from product, shop, category
where fk_shop_id = shop_id
and fk_cat_id = cat_id

-- Category
select cat_id, cat_title Category
from "#OWNER#"."CATEGORY" 
  
-- Daily Report
select to_char(sysdate,'DD-MON-RR') order_date, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.shop_id = :P38_NEW
and to_char(order_date,'DD/MON/RR') = to_char(sysdate,'DD/MON/RR')
group by to_char(order_date,'DD/MON/RR'), p.prod_title

-- Weekly Report
select to_char(order_date - 7/24,'IYYY') year, to_char(order_date - 7/24, 'IW') week
, p.prod_title as item, sum(i.quantity) as sales_quantity
, '$'||sum(i.quantity * p.prod_price) as revenue
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.shop_id = :P7_NEW
group by  to_char(order_date - 7/24 ,'IYYY'),to_char(order_date - 7/24, 'IW'), prod_title

-- Monthly
select to_char(order_date,'MONTH YYYY') as Month, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.shop_id = :P10_NEW
group by to_char(order_date,'MONTH YYYY'), p.prod_title

-- Orders
select i.invoice_id, o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.shop_id = :P39_NEW
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'

-- Feedback
select * from feedback


















