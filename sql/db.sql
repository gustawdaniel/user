DROP DATABASE a;
CREATE DATABASE a;

use a;

CREATE TABLE user(
  id INT(6) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) UNIQUE NOT NULL,
  pass VARCHAR(255) NOT NULL
);
