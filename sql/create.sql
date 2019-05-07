
CREATE TABLE cart
(
  user_id integer,
  prod_id integer,
  quantity integer
);

CREATE TABLE category
(
  cat_id integer NOT NULL,
  cat_title varchar2(255) unique
);

ALTER TABLE category ADD CONSTRAINT pk_category
  PRIMARY KEY (cat_id);

CREATE TABLE invoice
(
  invoice_id integer NOT NULL,
  invoice_no integer,
  shop_id integer,
  prod_id integer,
  quantity integer
);

ALTER TABLE invoice ADD CONSTRAINT pk_invoice
  PRIMARY KEY (invoice_id);

CREATE TABLE orders
(
  order_id integer NOT NULL,
  customer_id integer,
  invoice_no integer,
  amount integer,
  collection_slot varchar2(255),
  payment_status varchar2(255),
  order_date date,
  total_products integer
);

ALTER TABLE orders ADD CONSTRAINT pk_orders
  PRIMARY KEY (order_id);

CREATE TABLE product
(
  prod_id integer NOT NULL,
  prod_title varchar2(255),
  prod_price integer,
  prod_img varchar2(255),
  prod_desc varchar2(255),
  stock integer,
  min_order integer,
  max_order integer,
  allergy_info varchar2(255),
  keywords varchar2(255),
  prod_status varchar2(255) DEFAULT 'active',
  fk_shop_id integer,
  fk_cat_id integer,
  discount integer DEFAULT 0
);

ALTER TABLE products ADD CONSTRAINT pk_products
  PRIMARY KEY (prod_id);

CREATE TABLE shop
(
  shop_id integer NOT NULL,
  shop_name varchar2(255) unique,
  fk_trader_id integer
);

ALTER TABLE shop ADD CONSTRAINT pk_shop
  PRIMARY KEY (shop_id);

CREATE TABLE users
(
  user_id integer NOT NULL,
  username varchar2(255) UNIQUE,
  password varchar2(255),
  email varchar2(255),
  dob date,
  firtsname varchar2(255),
  lastname varchar2(255),
  address varchar2(255),
  role varchar2(255),
  profile_picture varchar2(255),
  vkey varchar2(255),
  verified integer DEFAULT 0,
  created_at date DEFAULT sysdate,
  phone varchar2(255), 
  product_line varchar2(255) UNIQUE,
  status varchar2(255) DEFAULT 'active'
  
);

ALTER TABLE users ADD CONSTRAINT pk_users
  PRIMARY KEY (user_id);

ALTER TABLE product ADD CONSTRAINT fk_product_category
  FOREIGN KEY (fk_cat_id) REFERENCES category (cat_id) ON DELETE CASCADE;

ALTER TABLE product ADD CONSTRAINT fk_product_shop
  FOREIGN KEY (fk_shop_id) REFERENCES shop (shop_id) ON DELETE CASCADE;

ALTER TABLE shop ADD CONSTRAINT fk_shop_users
  FOREIGN KEY (fk_trader_id) REFERENCES users (user_id) ON DELETE CASCADE;


CREATE SEQUENCE user_id
START WITH 1
MINVALUE 1
INCREMENT BY 1
CACHE 20;

CREATE SEQUENCE prod_id
START WITH 1
MINVALUE 1
INCREMENT BY 1
CACHE 20;

CREATE SEQUENCE invoice_id
START WITH 1
MINVALUE 1
INCREMENT BY 1
CACHE 20;

CREATE SEQUENCE order_id
START WITH 1
MINVALUE 1
INCREMENT BY 1
CACHE 20;

CREATE SEQUENCE shop_id
START WITH 1
MINVALUE 1
INCREMENT BY 1
CACHE 20;

