-- MONTHLY
select to_char(order_date,'MONTH YYYY') as Month, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = :SESSION_USER_ID
group by to_char(order_date,'MONTH YYYY'), p.prod_title

-- YEARLY
select to_char(order_date,'YYYY') as year, p.prod_title as item, sum(i.quantity) as sales_quantity, '$'||sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = :SESSION_USER_ID
group by to_char(order_date,'YYYY'), p.prod_title

-- TODAY'S ORDER
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot,delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = :SESSION_USER_ID
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'
and to_char(order_date,'YYYY-MM-DD') = to_char(sysdate, 'YYYY-MM-DD')

-- LAST 7 DAYS WHOSE DELIVERY IS DONE
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

-- All ORDERS TILL DATE WITHOUT DELIVERY FILTER
select i.invoice_id, o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = :SESSION_USER_ID
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'


-- Products - Admin
select prod_id, prod_title name, '$'||prod_price price, prod_img image, allergy_info, stock, keywords,min_order,max_order,discount 
,shop_name shop, cat_title category, prod_status status
from product, shop, category
where fk_shop_id = shop_id
and fk_cat_id = cat_id

-- Customer - Admin
select user_id, firstname||' '||lastname as customer_name, username,password 
,email, address, dob, profile_picture,verified
, created_at as creation_date, status 
from users where role ='Customer'

-- Trader - Admin
select user_id, firstname||' '||lastname as trader_name, username,password ,email, address, dob, profile_picture,phone, product_line,verified, created_at as creation_date, status 
from users where role ='Trader'

-- Trader
select count(distinct i.invoice_no) as paid_orders 
from invoice i, shop s, orders o 
where i.shop_id = s.shop_id 
and i.invoice_no = o.invoice_no 
and o.payment_status = 'Paid' 
and s.fk_trader_id = :SESSION_USER_ID;

select sum(i.quantity * p.prod_price) as revenues 
from product p, invoice i, shop s, orders o 
where i.prod_id = p.prod_id 
and s.shop_id = i.shop_id 
and o.invoice_no = i.invoice_no 
and o.payment_status = 'Paid' 
and s.fk_trader_id = :SESSION_USER_ID;



