-- create database recipeApp;
--
-- use recipeApp;
--
--
drop table users;
drop table menus;
create table users (
  id int auto_increment primary key,
  user_name varchar(255),
  password varchar(255),
  icon blob,
  bool boolean default true,
  created_at datetime,
  updated_at datetime,
  deleted_at datetime
);
--
-- create table favorites (
--   id int auto_increment primary key,
--   user_id int,
--   menu_id int,
--   created_at datetime,
--   updated_at datetime
-- );
--
create table menus (
  id int auto_increment primary key,
  user_id int,
  title varchar(255),
  category varchar(255),
  body varchar(255),
  cost int,
  img varchar(255),
  created_at datetime,
  updated_at datetime,
  bool boolean default false
);
--
-- create table comments (
--   id int auto_increment primary key,
--   menu_id int,
--   user_id int,
--   body varchar(255),
--   created_at datetime
-- );
