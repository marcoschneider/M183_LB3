DROP DATABASE IF EXISTS m133_todo_app_beta;

CREATE DATABASE IF NOT EXISTS m133_todo_app_beta;

USE m133_todo_app_beta;

CREATE TABLE priority(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  niveau VARCHAR(255)
);

CREATE TABLE project(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  project_name VARCHAR(255) NOT NULL ,
  short_name VARCHAR(255) NOT NULL
);

CREATE TABLE `group`(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  group_name VARCHAR(255) NOT NULL ,
  group_short VARCHAR(255) NOT NULL
);

CREATE TABLE user(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstname VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL,
  password CHAR(64) NOT NULL ,
  fk_group INT(11) NOT NULL,
  FOREIGN KEY (fk_group) REFERENCES `group`(id)
);

CREATE TABLE link(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  link_url VARCHAR(512) NOT NULL ,
  link_name VARCHAR(255) NOT NULL ,
  fk_user INT(11) NOT NULL ,
  FOREIGN KEY (fk_user) REFERENCES user(id)
);

CREATE TABLE todo(
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL ,
  problem VARCHAR(255) NOT NULL ,
  todo_status INT(1) NOT NULL ,
  fixed_date INT(11),
  last_edit INT(11),
  creation_date INT(11),
  website_url VARCHAR(512),
  fk_project INT(11) NOT NULL ,
  fk_priority INT(11) NOT NULL ,
  fk_group INT(11) NOT NULL ,
  fk_user INT(11) NOT NULL ,
  FOREIGN KEY (fk_project) REFERENCES project(id),
  FOREIGN KEY (fk_priority) REFERENCES priority(id),
  FOREIGN KEY (fk_group) REFERENCES `group`(id),
  FOREIGN KEY (fk_user) REFERENCES user(id)
);

