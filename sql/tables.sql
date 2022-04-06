DROP TABLE IF EXISTS 
user_region, role, region, user, 
item, product, sell_point,
orders, reservation, cart 
CASCADE;

CREATE TABLE IF NOT EXISTS role(
    id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS region(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS user_region(
    user_id INT NOT NULL,
    region_id INT NOT NULL,
    PRIMARY KEY(user_id, region_id)
);

CREATE TABLE IF NOT EXISTS user(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(320) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS reservation(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    status INT NOT NULL,
    sell_point_id INT NOT NULL,
    cart_id INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS product(
    sku VARCHAR(10) NOT NULL,
    brand VARCHAR(30) NOT NULL,
    category VARCHAR(30) NOT NULL,
    name VARCHAR(30) NOT NULL,
    price FLOAT NOT NULL,
    PRIMARY KEY(sku)
);

CREATE TABLE IF NOT EXISTS item(
    id INT NOT NULL AUTO_INCREMENT,
    product_sku VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    stock INT NOT NULL,
    category VARCHAR(30) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS sell_point (
    id INT NOT NULL AUTO_INCREMENT,
    region_id INT NOT NULL,
    address VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS cart_item (
    cart_id INT NOT NULL, 
    item_sku INT NOT NULL,
    shipping_status INT NOT NULL,
    PRIMARY KEY(cart_id, item_sku)
);

CREATE TABLE IF NOT EXISTS cart(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    status INT NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE user_state
ADD CONSTRAINT FK_user_id
FOREIGN KEY (user_id) REFERENCES user(id),
ADD CONSTRAINT FK_user_region
FOREIGN KEY (region_id) REFERENCES region(id);

ALTER TABLE user
ADD CONSTRAINT FK_role_id
FOREIGN KEY (role_id) REFERENCES role(id);

ALTER TABLE item
ADD CONSTRAINT FK_product_sku
FOREIGN KEY (product_sku) REFERENCES product(sku);

ALTER TABLE sell_point
ADD CONSTRAINT FK_sell_point_region
FOREIGN KEY (region_id) REFERENCES region(id);

/* DEFAULT VALUES */
INSERT INTO role (id, name) VALUES 
(0, "developer"),
(1, "national administrator"),
(2, "state group administrator"),
(3, "state administrator");

/* EXAMPLE ROWS */

/* DEV USER */
INSERT INTO user (email, password, role_id)
VALUES ("dev@dev.com", "dev", 0); /* change with hash of password */