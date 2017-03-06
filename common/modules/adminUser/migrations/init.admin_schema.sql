DROP TABLE IF EXISTS adminuser_role;
CREATE TABLE adminuser_role (
  id INT (11) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  code VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  created_at DATETIME,
  updated_at DATETIME,
  created_by INT (11) UNSIGNED,
  updated_by INT (11) UNSIGNED,
  status SMALLINT(3) DEFAULT 1
);

DROP TABLE IF EXISTS adminuser_user;
CREATE TABLE adminuser_user (
  id INT (11) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  role_id INT (11) UNSIGNED,
  email TEXT,
  username TEXT,
  fullname TEXT,
  dob date,
  `desc` TEXT,
  avatar TEXT,
  position TEXT,
  auth_key TEXT,
  password_hash TEXT,
  password_reset_token TEXT,
  created_at DATETIME,
  updated_at DATETIME,
  created_by INT (11) UNSIGNED,
  updated_by INT (11) UNSIGNED,
  status SMALLINT(3) DEFAULT 1
);

DROP TABLE IF EXISTS adminuser_role_right;
CREATE TABLE adminuser_role_right (
  id INT (11) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  role_id INT (11) UNSIGNED,
  module VARCHAR(255),
  controller VARCHAR(255),
  action VARCHAR(255),
  is_owner SMALLINT,
  created_at DATETIME,
  updated_at DATETIME,
  created_by INT (11) UNSIGNED,
  updated_by INT (11) UNSIGNED,
  status SMALLINT(3) DEFAULT 1
);

INSERT INTO adminuser_role (code, name, status) VALUES ('super_admin', 'sadmin', 1);
INSERT INTO adminuser_user (role_id, email, username, position, password_hash ,status) VALUES (1, 'sadmin@sadmin.com' ,'sadmin', 'owner','$2y$13$V7wZt1TTMiDfEex8Xw/vLet2CBt1swqVKPipPR4g00rvBNoQ0lwXS', 1);