-- MONTHLY
select to_char(order_date,'MONTH YYYY') as Month, p.prod_title as item, sum(i.quantity) as "QUANTITY SOLD", sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = '<trader_id_variable>'
group by to_char(order_date,' MONTH YYYY'), p.prod_title

-- YEARLY
select to_char(order_date,'YYYY') as Month, p.prod_title as item, sum(i.quantity) as "QUANTITY SOLD", sum(i.quantity * p.prod_price) as "REVENUE"
from orders o, invoice i, shop s, product p
where payment_status ='Paid'
and i.prod_id = p.prod_id
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and s.fk_trader_id = '<trader_id_variable>'
group by to_char(order_date,'YYYY'), p.prod_title

-- TODAY'S ORDER
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = '<trader_id_variable>'
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'
and to_char(order_date,'YYYY-MM-DD') = to_char(sysdate, 'YYYY-MM-DD')

-- LAST 7 DAYS WHOSE DELIVERY IS DONE
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , '$'||(i.quantity * p.prod_price) as paid_amount, o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = '<trader_id_variable>'
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and i.delivery = 'Complete'
and o.payment_status = 'Paid'
and to_char(order_date,'YYYY-MM-DD') >= to_char(sysdate-7, 'YYYY-MM-DD')

-- All ORDERS TILL DATE WITHOUT DELIVERY FILTER
select o.order_date, p.prod_title as item, u.firstname||' '||u.lastname as Customer, i.invoice_no as invoice, i.quantity as ordered_quantity, p.stock , o.collection_slot, i.delivery
from product p, users u, invoice i, orders o, shop s
where s.fk_trader_id = '<trader_id_variable>'
and s.shop_id = i.shop_id
and i.invoice_no = o.invoice_no
and u.user_id = o.customer_id
and p.prod_id = i.prod_id
and o.payment_status = 'Paid'

