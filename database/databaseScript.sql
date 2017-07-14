#start fresh
DROP DATABASE edel;

#create a new database called edel
CREATE DATABASE edel;
 
USE edel;
 
#the partners table.
CREATE TABLE Users(
	user_id int not null auto_increment primary key,
	user_name varchar(255),
	user_email varchar(255) not null,
	user_imei varchar(255),
	user_private_key varchar(2050) not null,
	user_public_key varchar(2050) not null,
	user_salt varchar(255) not null,
	user_hashed_password varchar(255) not null,
	user_karma int,
	UNIQUE(user_id)
);

#creating an index based on the email address
CREATE INDEX UserIndexEmail ON Users(user_email(60));
 
CREATE TABLE Posts(
   post_id int not null auto_increment primary key,
   post_type varchar(255),
   post_document_type varchar(255),
   post_document_size int,
   post_document_name varchar(255),
   post_date Date,
   user_id int not null,
   post_rating int,
   post_document_content MEDIUMBLOB,
   post_text varchar(255),
   FOREIGN KEY (user_id)
   REFERENCES Users(user_id)
   ON UPDATE CASCADE
   ON DELETE RESTRICT
);

#creating a voting system
CREATE TABLE Votes(
   vote_id int not null auto_increment primary key,
   user_id int not null,
   post_id int not null,
   vote_date Date,
   vote_value int,
   FOREIGN KEY (user_id)
   REFERENCES Users(user_id)
   ON UPDATE CASCADE
   ON DELETE RESTRICT,
   FOREIGN KEY (post_id)
   REFERENCES Posts(post_id)
   ON UPDATE CASCADE
   ON DELETE RESTRICT
);

#create tags
CREATE TABLE Tags(
	tag_id int not null auto_increment primary key,
	tag_name varchar(255),
);

#create a tagpost table
CREATE TABLE TagPosts(
	tagpost_id int not null auto_increment primary key,
	tag_id int not null,
	post_id int not null,
	FOREIGN KEY (tag_id)
   	REFERENCES Tags(tag_id)
   	ON UPDATE CASCADE
   	ON DELETE RESTRICT,
   	FOREIGN KEY (post_id)
   	REFERENCES Posts(post_id)
   	ON UPDATE CASCADE
   	ON DELETE RESTRICT
);