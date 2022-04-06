DROP TABLE IF EXISTS 
user_state, role, state, user, orders, reservation, product, cart 
CASCADE;

CREATE TABLE IF NOT EXISTS role(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS state(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS user_state(
    user_id INT NOT NULL,
    state_id INT NOT NULL,
    PRIMARY KEY(user_id, state_id)
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
    userId INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS product(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS cart(
    id INT NOT NULL AUTO_INCREMENT,
    userId INT NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE user_state
ADD CONSTRAINT FK_userId
FOREIGN KEY (user_id) REFERENCES user(id),
ADD CONSTRAINT FK_userState
FOREIGN KEY (state_id) REFERENCES state(id);

/* EXAMPLE ROWS */

INSERT INTO user (email, password, role_id) VALUES ("test@gmail.com", "test", 1);