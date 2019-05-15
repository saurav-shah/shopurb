ALTER TABLE product
  DROP CONSTRAINT fk_products_category;

ALTER TABLE product
  DROP CONSTRAINT fk_products_shop;

ALTER TABLE shop
  DROP CONSTRAINT fk_shop_users;

DROP TABLE cart cascade constraints;

DROP TABLE category cascade constraints;

DROP TABLE invoice cascade constraints;

DROP TABLE orders cascade constraints;

DROP TABLE product cascade constraints;

DROP TABLE shop cascade constraints;

DROP TABLE users cascade constraints;

DROP TABLE feedback cascade constraints;

DROP SEQUENCE user_id;

DROP SEQUENCE prod_id;

DROP SEQUENCE invoice_id;

DROP SEQUENCE order_id;

DROP SEQUENCE cat_id;

DROP SEQUENCE shop_id;
