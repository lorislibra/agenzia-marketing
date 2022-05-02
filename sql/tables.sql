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
    sell_point_id INT NOT NULL,
    date_order TIMESTAMP NOT NULL,
    date_delivery TIMESTAMP,
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

INSERT INTO user (id, email, password, role_id) VALUES 
(1, "dev1@dev.com", "ciao1234", 1),
(2, "dev2@dev.com", "ciao1234", 1),
(3, "dev3@dev.com", "ciao1234", 1);

INSERT INTO user_region (user_id, region_id) VALUES 
(1, 20), (1, 19), (1, 18), (1, 17), (1, 16), (1, 15), (1, 14), (1, 13),
(1, 12), (1, 11), (1, 10), (1, 9), (1, 8), (1, 7), (1, 6), (1, 5),
(1, 4), (1, 3), (1, 2), (1, 1);

INSERT INTO sell_point (id, name, address, region_id) VALUES
(1, "iper", "via roma 1, castelfranco veneto, 35010", 20);

INSERT INTO product (sku, brand, category, name, image, price) VALUES 
("PRN1", "Peroni", "Bevanda", "Peroni", "https://peroni.it/wp-content/uploads/2019/09/peroni-33-feat.jpg", 1.5),
("PRC1", "Peroni", "Bevanda", "Peroni cruda", "https://peroni.it/wp-content/uploads/2021/04/peroni-cruda-33-feat.jpg", 1.4),
("PCL1", "Peroni", "Bevanda", "Peroni chill lemon", "https://peroni.it/wp-content/uploads/2019/09/peroni-chill-lemon-feat.jpg", 1.6),
("PSG1", "Peroni", "Bevanda", "Peroni senza glutine", "https://peroni.it/wp-content/uploads/2019/09/peroni-senza-glutine-feat.jpg", 1.3),
("PRB1", "Peroni", "Bevanda", "Peroni gr bianca", "https://peroni.it/wp-content/uploads/2021/04/pgr-bianca-feat.jpg", 1.7),
("PRD1", "Peroni", "Bevanda", "Peroni gr doppio malto", "https://peroni.it/wp-content/uploads/2019/09/peroni-gran-riserva-doppio-malto-feat.jpg", 1.8),
("PRR1", "Peroni", "Bevanda", "Peroni gr rossa", "https://peroni.it/wp-content/uploads/2019/09/peroni-gran-riserva-rossa-feat.jpg", 2.1),
("PRM1", "Peroni", "Bevanda", "Peroni gr puro malto", "https://peroni.it/wp-content/uploads/2019/09/peroni-gran-riserva-puro-malto-feat.jpg", 1.2),
("PNC1", "Peroni", "Bevanda", "Peroncino", "https://peroni.it/wp-content/uploads/2019/10/454x626__cGFVLpn.jpg", 3.0);

INSERT INTO item (id, product_sku, quantity, stock, image, category) VALUES 
(1, "PRN1", 30, 100, "", "cartonato"),
(2, "PRC1", 29, 101, "", "cartonato"),
(3, "PCL1", 31, 99, "", "cartonato"),
(4, "PSG1", 28, 102, "", "cartonato"),
(5, "PRB1", 32, 98, "", "cartonato"),
(6, "PRD1", 27, 103, "", "cartonato"),
(7, "PRR1", 33, 97, "", "cartonato"),
(8, "PRM1", 26, 104, "", "cartonato"),
(9, "PNC1", 34, 96, "", "cartonato");

INSERT INTO cart_item (user_id, item_id, quantity) VALUES 
(1, 1, 3);

INSERT INTO reservation (id, user_id, status, sell_point_id, date_order) VALUES
(1, 1, 1, 1, "2022-04-08 19:25:14");

INSERT INTO reservation_item (reservation_id, item_id, quantity) VALUES
(1, 1, 2);