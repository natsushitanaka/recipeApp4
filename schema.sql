create database recipeApp;

use recipeApp;

create table users (
  id int auto_increment primary key not null,
  user_name varchar(255) not null,
  password varchar(255) not null,
  icon blob,
  created_at datetime not null,
  updated_at datetime not null,
  deleted_at datetime DEFAULT null
);

create table favorites (
  id int auto_increment primary key not null,
  user_id int not null,
  menu_id int not null,
  created_at datetime not null,
  updated_at datetime not null
);

create table menus (
  id int auto_increment primary key not null,
  user_id int not null,
  title varchar(255) not null,
  category varchar(255),
  body varchar(255) not null,
  cost int,
  img varchar(255),
  created_at datetime not null,
  updated_at datetime not null,
  bool tinyint(1) default false
);

create table comments (
  id int auto_increment primary key not null,
  menu_id int not null,
  user_id int not null,
  body varchar(255) not null,
  created_at datetime not null
);
