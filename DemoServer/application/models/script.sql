CREATE TABLE test.users (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
   PRIMARY KEY (username)
);

INSERT INTO test.users (username, password, email) VALUES ('12345678', '12345678', '12');
INSERT INTO test.users (username, password, email) VALUES ('123456789', '123456789', '12');
INSERT INTO test.users (username, password, email) VALUES ('123456788', '123456788', '12');

alter table users drop primary key;
ALTER TABLE  users ADD  id INT( 11 ) NOT NULL  PRIMARY KEY AUTO_INCREMENT FIRST;