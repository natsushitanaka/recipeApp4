create database recipeApp;

use recipeApp;

create table users (
  id int auto_increment primary key,
  user_name varchar(255),
  password varchar(255),
  icon blob
);

create table favorite_(user_id) (
  id int auto_increment primary key,
  menu_id int
);

create table menus (
  id int auto_increment primary key,
  user_id int,
  title varchar(255),
  category varchar(255),
  body varchar(255),
  cost int,
  img varchar(255),
  created_at datetime
);

create table comments (
  id int auto_increment primary key,
  menu_id int,
  user_id int,
  body varchar(255),
  created_at datetime
);
