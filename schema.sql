-- create database recipeApp DEFAULT CHARACTER SET utf8mb4;

-- use recipeApp;

-- drop table users;
-- drop table menus;
-- drop table comments;
-- drop table favorites;

create table users (
  id int auto_increment primary key not null,
  user_name varchar(255) not null,
  password varchar(255) not null,
  icon_data blob,
  icon_ext varchar(10),
  created_at datetime not null,
  updated_at datetime not null,
  deleted_at datetime DEFAULT null
)DEFAULT CHARSET=utf8mb4;

create table favorites (
  id int auto_increment primary key not null,
  user_id int not null,
  menu_id int not null,
  created_at datetime not null
)DEFAULT CHARSET=utf8mb4;

create table menus (
  id int auto_increment primary key not null,
  user_id int not null,
  title varchar(255) not null,
  category varchar(255),
  body varchar(255),
  cost int not null,
  img varchar(255),
  created_at datetime not null,
  updated_at datetime not null,
  openRange tinyint(1) default false
)DEFAULT CHARSET=utf8mb4;

create table comments (
  id int auto_increment primary key not null,
  menu_id int not null,
  user_id int not null,
  body varchar(255) not null,
  created_at datetime not null
)DEFAULT CHARSET=utf8mb4;
