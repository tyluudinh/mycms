DROP TABLE IF EXISTS system_setting;
CREATE TABLE system_setting (
  id INT (11) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL,
  value text NOT NULL,
  type VARCHAR(255),
  `explain` TEXT,
  created_at DATETIME,
  updated_at DATETIME,
  created_by INT (11) UNSIGNED,
  updated_by INT (11) UNSIGNED,
  status SMALLINT(3) DEFAULT 1
);