CREATE DATABASE IF NOT EXISTS hotelaria;

USE hotelaria;

CREATE TABLE hotels (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,

    PRIMARY KEY (id)
);

CREATE TABLE rooms (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    hotel_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_rooms_hotel
        FOREIGN KEY (hotel_id)
        REFERENCES hotels(id)
);

CREATE TABLE reserves (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    hotel_id BIGINT UNSIGNED NOT NULL,
    room_id BIGINT UNSIGNED NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_reserves_hotel
        FOREIGN KEY (hotel_id)
        REFERENCES hotels(id),

    CONSTRAINT fk_reserves_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
);

CREATE TABLE guests (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reserve_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_guests_reserve
        FOREIGN KEY (reserve_id)
        REFERENCES reserves(id)
);

CREATE TABLE dailies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reserve_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    value DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_dailies_reserve
        FOREIGN KEY (reserve_id)
        REFERENCES reserves(id)
);

CREATE TABLE payments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reserve_id BIGINT UNSIGNED NOT NULL,
    method INT UNSIGNED NOT NULL,
    value DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_payments_reserve
        FOREIGN KEY (reserve_id)
        REFERENCES reserves(id)
);
