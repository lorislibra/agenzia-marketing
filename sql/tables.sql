DROP TABLE IF EXISTS 
user_region, reservation_item, cart_item,
reservation, sell_point, item, product,
user, region
CASCADE;

/* ---------------------------------------------------------- */

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

CREATE TABLE IF NOT EXISTS product(
    sku VARCHAR(10) NOT NULL,
    brand VARCHAR(30) NOT NULL,
    category VARCHAR(30) NOT NULL,
    name VARCHAR(30) NOT NULL,
    image VARCHAR(200) NOT NULL,
    price FLOAT NOT NULL,
    PRIMARY KEY(sku)
);

CREATE TABLE IF NOT EXISTS item(
    id INT NOT NULL AUTO_INCREMENT,
    product_sku VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(200) NOT NULL,
    category VARCHAR(30) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS sell_point (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    region_id INT NOT NULL,
    address VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS cart_item (
    user_id INT NOT NULL, 
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY(user_id, item_id)
);

CREATE TABLE IF NOT EXISTS reservation(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    status INT NOT NULL,
    comment VARCHAR(400) NOT NULL,
    sell_point_id INT NOT NULL,
    date_order TIMESTAMP NOT NULL,
    date_delivery TIMESTAMP NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS reservation_item(
    reservation_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY(reservation_id, item_id)
);

/* -------------------------------------------------------------- */

ALTER TABLE user_region
ADD CONSTRAINT FK_user_region_user_id
FOREIGN KEY (user_id) REFERENCES user(id),
ADD CONSTRAINT FK_user_region_region_id
FOREIGN KEY (region_id) REFERENCES region(id);

ALTER TABLE cart_item
ADD CONSTRAINT FK_cart_item_idem_id
FOREIGN KEY (item_id) REFERENCES item(id),
ADD CONSTRAINT FK_cart_item_user_id
FOREIGN KEY (user_id) REFERENCES user(id);

ALTER TABLE sell_point
ADD CONSTRAINT FK_sell_point_region_id
FOREIGN KEY (region_id) REFERENCES region(id);

ALTER TABLE item
ADD CONSTRAINT FK_item_product_sku
FOREIGN KEY (product_sku) REFERENCES product(sku);

ALTER TABLE reservation
ADD CONSTRAINT FK_reservation_user_id
FOREIGN KEY (user_id) REFERENCES user(id),
ADD CONSTRAINT FK_reservation_sell_point_id
FOREIGN KEY (sell_point_id) REFERENCES sell_point(id);

ALTER TABLE reservation_item
ADD CONSTRAINT FK_reservation_item_reservation_id
FOREIGN KEY (reservation_id) REFERENCES reservation(id),
ADD CONSTRAINT FK_reservation_item_item_id
FOREIGN KEY (item_id) REFERENCES item(id);

/* -------------------------------------------------------------- */

INSERT INTO product (sku, brand, category, name, image, price) VALUES 
("BF1", "Follina", "Birra", "Birra follina", "", 1.50),
("ICN1", "Ichnusa", "Birra", "Birra Ichnusa", "", 1.30),
("TN1", "Tennents", "Birra", "Birra Tennents", "", 1),
("BLL1", "Bellazzi", "Birra", "Birra Bellazzi", "", 2.5),
("WL1", "Weili", "Birra", "Birra Weili", "", 1.8);

INSERT INTO item (product_sku, quantity, stock, category, image) VALUES 
("BF1", 9, 20, "cartonato", "https://www.birrafollina-shoponline.it/wp-content/uploads/2020/05/birra-follina-espositore-9-bottiglie.jpg"),
("ICN1", 60, 4, "legno", "https://www.reclametotale.com/upload/documenti/5/51/_thumbs/design-515.jpg"),
("TN1", 160, 6, "cartonato", "https://www.espositoritalia.it/wp-content/uploads/2021/02/espositore_in_cartot.jpg" ),
("BLL1", 10, 20, "cartonato", "https://solutiongroup.it/wp-content/uploads/2021/01/vino.png"),
("WL1", 7, 15, "frighetto", "https://sc04.alicdn.com/kf/HTB19oTNk25TBuNjSspmq6yDRVXau.jpg");