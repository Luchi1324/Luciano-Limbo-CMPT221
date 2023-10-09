-- Creates and populates a database for Limbo
-- Authors: Luciano Mattoli & Andrew Masone
-- Version: 1.0

-- Drops previous database if it exists so it can create and use a new one
DROP DATABASE IF EXISTS limbo_db;
CREATE DATABASE IF NOT EXISTS limbo_db;
USE limbo_db;

CREATE TABLE IF NOT EXISTS users
(
    user_id INT UNSIGNED   NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(40)  NOT NULL,
    email VARCHAR(60)      NOT NULL UNIQUE,
    pass TEXT(256)         NOT NULL,
    reg_date DATETIME      NOT NULL
);

-- Creates admin user and inserts into users table
INSERT INTO users (first_name, last_name, email, pass, reg_date)
VALUES ('admin', '', '', SHA2("gaze11e", 256), Now());

CREATE TABLE IF NOT EXISTS locations
(
    id INT      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    create_date DATETIME NOT NULL,
    update_date DATETIME NOT NULL,
    name TEXT   NOT NULL
);

-- Populates location table with all main buildings on campus
INSERT INTO locations (create_date, update_date, name)
VALUES (Now(), Now(), 'Hancock Center'),
       (Now(), Now(), 'Donnelly Hall'),
       (Now(), Now(), 'Fontaine Hall'),
       (Now(), Now(), 'Lowell Thomas Center'),
       (Now(), Now(), '51 Fulton'),
       (Now(), Now(), 'Steel Plant'),
       (Now(), Now(), 'James A. Library'),
       (Now(), Now(), 'Murray Student Center'),
       (Now(), Now(), 'New Fulton'),
       (Now(), Now(), 'Upper Fulton'),
       (Now(), Now(), 'Lower West Cedar'),
       (Now(), Now(), 'Upper West Cedar'),
       (Now(), Now(), 'OShea Hall'),
       (Now(), Now(), 'Lavelle Hall'),
       (Now(), Now(), 'McCormick Hall'),
       (Now(), Now(), 'Ward Hall'),
       (Now(), Now(), 'Foy Townhouses'),
       (Now(), Now(), 'Upper New Townhouses'),
       (Now(), Now(), 'Lower New Townhouses'),
       (Now(), Now(), 'Midrise Hall'),
       (Now(), Now(), 'Champagnat Hall'),
       (Now(), Now(), 'Leo Hall'),
       (Now(), Now(), 'Marian Hall'),
       (Now(), Now(), 'Sheahan Hall');

CREATE TABLE IF NOT EXISTS stuff 
(
  id INT                                  AUTO_INCREMENT PRIMARY KEY,
  location_id INT                         NOT NULL,
  description TEXT                        NOT NULL,
  identifiers TEXT                        NOT NULL,
  create_date DATETIME                    NOT NULL,
  update_date DATETIME                    NOT NULL, 
  room TEXT,
  owner TEXT,
  finder TEXT,
  status SET ('Found', 'Lost', 'Claimed') NOT NULL,
  bounty INT,
  FOREIGN KEY (location_id) REFERENCES locations(id)
);

INSERT INTO stuff (location_id, description, identifiers, create_date, update_date, room, owner, finder, status, bounty)
VALUES (1, 'Blue iPhone 13', 'Yellow Case, Beats sticker', '2022-10-27 09:23:00', '2022-10-27 09:23:00', 'HC 2020', 'John Doe, text at 123-456-7789', '', 'Lost', 50),
       (5, 'Grey Pencil Case', 'Stars Wars branding, Has two pens and a pencil inside', '2022-09-13 11:48:00', '2022-09-13 11:48:00', 'FF 139', 'James Doe, text at 123-456-7789', '', 'Lost', 0),
       (4, 'Green TI-84', 'Missing case, scratches on the back', '2022-11-02 09:45:00', '2022-11-02 09:45:00', 'LT 006', 'Jane Doe, text at 123-456-7789', '', 'Lost', 0),
       (8, 'Ray-Ban sunglasses', 'Green lenses', '2022-11-08 13:37:00', '2022-11-10 16:23:00', 'LB 322', 'Joe Don, text at 123-456-7789', 'Findy McGee, text at 123-456-7789','Found', 10),
       (9, 'Apple Watch', 'Green band', '2022-10-17 11:47:00', '2022-10-29 08:01:00', 'SC 3003', 'Jane Donna, text at 123-456-7789', 'Findy McGee, text at 123-456-7789','Found', 90),
       (9, 'Airpods', 'blue rubber case', '2022-10-23 17:30:00', '2022-11-02 20:00:00', 'SC 3007', 'James Donna, text at 123-456-7789', 'Findy McGee, text at 123-456-7789','Found', 10);


EXPLAIN users;
EXPLAIN locations;
EXPLAIN stuff;

SELECT * FROM users;
SELECT * FROM locations;
SELECT * FROM stuff;
