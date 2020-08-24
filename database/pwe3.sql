
create database if not exists pwe3;
USE pwe3;

create table if not exists aux_em(
	id int auto_increment,
    student_name varchar(60) not null,
    student_city varchar(40) not null,
    student_state varchar(20) not null,
    student_school varchar(60) not null,
    student_rm char(6) not null,
    primary key(id)
);