grant all on owazo_recipeapp.* to owazo_dbuser@mysql10009.xserver.jp;

-- CREATE TABLE users (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   user_name VARCHAR(255) UNIQUE NOT NULL,
--   password VARCHAR(255) NOT NULL,
--   icon_data BLOB,
--   icon_ext VARCHAR(10),
--   created_at DATETIME NOT NULL,
--   updated_at DATETIME NOT NULL,
--   deleted_at DATETIME DEFAULT NULL
-- )DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE favorites (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   user_id INT NOT NULL,
--   menu_id INT NOT NULL,
--   created_at DATETIME NOT NULL
-- )DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE menus (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   user_id INT NOT NULL,
--   title VARCHAR(255) NOT NULL,
--   category VARCHAR(255),
--   body VARCHAR(255),
--   cost INT,
--   img VARCHAR(255),
--   created_at DATETIME NOT NULL,
--   updated_at DATETIME NOT NULL,
--   is_displayed TINYINT(1) DEFAULT false
-- )DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE comments (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   menu_id INT NOT NULL,
--   user_id INT NOT NULL,
--   body VARCHAR(255) NOT NULL,
--   created_at DATETIME NOT NULL
-- )DEFAULT CHARSET=utf8mb4;
