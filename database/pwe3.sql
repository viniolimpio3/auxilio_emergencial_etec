drop database if exists pwe3;
create database if not exists pwe3;
use pwe3;
drop table if exists aux_em;
drop table if exists user;

create table user(
	id bigint auto_increment unique not null,
    name varchar(60) not null,
    email varchar(60) not null,
    city varchar(40) not null,
    state varchar(20) not null,
    school varchar(60) not null,
    senha text not null,/*hash - sha1*/
    rm char(6) not null unique, 
    
    answered_questions boolean default false comment 'user_questions',
    
    vf_code varchar(255) default 0,/*verify account code*/
    vf_code_created_at datetime default now(),
    
    has_bank_account boolean default false,
    
    forgot_pass boolean not null default false,
    url_hash varchar(255), /*sha1*/
    
    primary key(id)
);

create table if not exists aux_em(
	id bigint auto_increment unique,
    user_id bigint unique not null,/*not null - indica relacionamento obrigatório!*/
    foreign key(user_id) references user ( id ),
    primary key(id)
);

create table if not exists user_questions(
	id bigint auto_increment unique,
    primary key( id ),
    
    user_id bigint unique not null,
    foreign key(user_id) references user ( id ),

    rg varchar(15) not null,
    uf_rg char(2) not null,
    cep varchar(9) not null,
    cpf varchar(15) not null unique,
    link_photo text default 'https://www.computerhope.com/jargon/g/guest-user.jpg',

    internet boolean not null,
    isp_name varchar(20) comment 'internet service provider name',
    isp_configs longtext comment 'internet config',
    
    qt_pc_desktop int(2) not null,
    qt_pc_notebook int(2) not null,
    qt_sm_phone int(2) not null,
    
    pc_desktop_configs longtext default false,
    pc_notebook_configs longtext default false,
    sm_phone_configs longtext default false,

    
    qtd_in_house int(2),
    renda_per_capita decimal(6,2) not null,
    renda_ind decimal(6,2) not null,
    
    reason longtext not null comment 'reason of solicitation'
);

create table if not exists bank_info(
    id bigint auto_increment unique,
    primary key(id),
    
	user_id bigint unique not null,
	foreign key(user_id) references user ( id ),
    
    agencia int(8) not null,
    cc float(15) not null,
    bank_name varchar(60) not null
);

insert into user(name, email, city, state, school, senha, rm)
values('admin', 'viniolimpio3@gmail.com','cidade', 'estado', 'escola', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '000000');