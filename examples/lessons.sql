-------------------------------------------------------------------------------
--- Mysql db for lessons application
-------------------------------------------------------------------------------
CREATE DATABASE lessonsdb;
USE lessonsdb;

-------------------------------------------------------------------------------
--- Tables
-------------------------------------------------------------------------------

CREATE TABLE students (
  id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(50) DEFAULT NULL,
  last_name varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE teachers (
  id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(50) DEFAULT NULL,
  last_name varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(100) DEFAULT NULL,
  password varchar(100) DEFAULT NULL,
  locked int(11) DEFAULT NULL,
  disabled int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email)
);


CREATE TABLE month_names (
  id int(4) NOT NULL,
  name varchar(30) NOT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE lessons (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_teacher int(11) DEFAULT NULL,
  id_student int(11) DEFAULT NULL,
  start_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  duration decimal(4,2) DEFAULT NULL,
  subject varchar(30) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY id_teacher (id_teacher),
  KEY id_student (id_student),
  CONSTRAINT lessons_ibfk_1 FOREIGN KEY (id_teacher) REFERENCES teachers (id) ON UPDATE CASCADE,
  CONSTRAINT lessons_ibfk_2 FOREIGN KEY (id_student) REFERENCES students (id) ON UPDATE CASCADE
);

-------------------------------------------------------------------------------
--- Views
-------------------------------------------------------------------------------

CREATE VIEW report_1 AS (
    select 
        lessons.id AS id,
        lessons.id_student AS id_student,
        year(lessons.start_time) AS year_start_time,
        month(lessons.start_time) AS month_start_time,
        lessons.start_time AS start_time,
        lessons.duration AS duration,
        concat(teachers.last_name,' ',teachers.first_name) AS teacher,
        concat(students.last_name,' ',students.first_name) AS student,
        lessons.subject AS subject
    from (
    (lessons left join teachers on((lessons.id_teacher = teachers.id))) 
    left join students on((lessons.id_student = students.id))))
;

-------------------------------------------------------------------------------
--- Sample data
-------------------------------------------------------------------------------
insert into users (email,password,locked,disabled) values ('a@a.com','a',0,0);
insert into month_names(id,name) values (1,'January');
insert into month_names(id,name) values (2,'February');
insert into month_names(id,name) values (3,'March');
insert into month_names(id,name) values (4,'April');
insert into month_names(id,name) values (5,'May');
insert into month_names(id,name) values (6,'June');
insert into month_names(id,name) values (7,'July');
insert into month_names(id,name) values (8,'August');
insert into month_names(id,name) values (9,'September');
insert into month_names(id,name) values (10,'October');
insert into month_names(id,name) values (11,'November');
insert into month_names(id,name) values (12,'December');
commit;


