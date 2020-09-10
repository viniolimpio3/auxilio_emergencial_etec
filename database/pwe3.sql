drop database if exists pwe3;
create database if not exists pwe3;
use pwe3;
drop table if exists aux_em;
drop table if exists user;

create table user(
	id int auto_increment unique not null,
    name varchar(60) not null,
    email varchar(60) not null,
    city varchar(40) not null,
    state varchar(20) not null,
    school varchar(60) not null,
    senha text not null,/*hash - sha1*/
    rm char(6) not null unique, 
    forgot_pass boolean not null default false,
    url_hash varchar(255),
    primary key(id)
);

create table if not exists aux_em(
	id int auto_increment,
    user_id int not null,/*not null - indica relacionamento obrigatório!*/
    foreign key(user_id) references user ( id ),
    primary key(id)
);

insert into user(name, email, city, state, school, senha, rm)
values('admin', 'viniolimpio3@gmail.com','cidade', 'estado', 'escola', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '000000');