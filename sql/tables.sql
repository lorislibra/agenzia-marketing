DROP TABLE IF EXISTS 
user_region, reservation_item, cart_item,
reservation, sell_point, item, product,
user, region, role
CASCADE;

/* ---------------------------------------------------------- */

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
    user_id INT NOT NULL, 
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY(user_id, item_id)
);

CREATE TABLE IF NOT EXISTS reservation(
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    status INT NOT NULL,
    sell_point_id INT NOT NULL,
    date_order DATE NOT NULL,
    date_delivery DATE NOT NULL,
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

ALTER TABLE user
ADD CONSTRAINT FK_user_role_id
FOREIGN KEY (role_id) REFERENCES role(id);

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

/* DEFAULT VALUES */
INSERT INTO role (id, name) VALUES 
(1, "developer"),
(2, "warehouse"),
(3, "group administrator"),
(4, "region administrator");

INSERT INTO region (id, name) VALUES
(1, "Basilicata"),
(2, "Abruzzo"),
(3, "Calabria"),
(4, "Campania"),
(5, "Emilia Romagna"),
(6, "Friuli Venezia Giulia"),
(7, "Liguria"),
(8, "Lazio"),
(9, "Lombardia"),
(10, "Marche"),
(11, "Molise"),
(12, "Piemonte"),
(13, "Puglia"),
(14, "Sardegna"),
(15, "Sicilia"),
(16, "Toscana"),
(17, "Trentino Alto Adige"),
(18, "Umbria"),
(19, "Valle d'Aosta"),
(20, "Veneto");

INSERT INTO user (email, password, role_id) VALUES 
("dev1@dev.com", "dev", 1),
("dev2@dev.com", "dev", 1),
("dev3@dev.com", "dev", 1);

INSERT INTO user_region (user_id, region_id) VALUES 
(1, 20), (1, 19), (1, 18), (1, 17), (1, 16), (1, 15), (1, 14), (1, 13),
(1, 12), (1, 11), (1, 10), (1, 9), (1, 8), (1, 7), (1, 6), (1, 5),
(1, 4), (1, 3), (1, 2), (1, 1);
