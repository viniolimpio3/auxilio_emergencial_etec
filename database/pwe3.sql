drop database if exists pwe3;
create database if not exists pwe3;
use pwe3;
drop table if exists aux_em;
drop table if exists user;

create table user(
	id bigint auto_increment unique not null,
    name varchar(60) not null,
    email varchar(60) not null,
    school varchar(60) not null,
    senha text not null,/*hash - sha1*/
    rm char(6) not null unique, 
            
    answered_questions boolean default false comment 'user_questions',
    
    link_photo text default 'https://www.computerhope.com/jargon/g/guest-user.jpg',
    
    vf_code varchar(255) default 0,/*verify account code*/
    vf_code_created_at datetime default now(),
    
    answered_bank_q boolean default false,
    has_bank_account boolean default false,
    
    is_verified_email boolean not null default false,
    forgot_pass boolean not null default false,
    url_hash varchar(255), /*sha1*/
    
    created_at datetime default now(),
    updated_at datetime,
    primary key(id)
);

create table if not exists aux_em(
	id bigint auto_increment unique,
    user_id bigint unique not null,/*not null - indica relacionamento obrigatório!*/
    
    status boolean default false,
    comments text not null default '', -- Parecer sobre os dados respondidos pelo user
    
    
    foreign key(user_id) references user ( id ),
    primary key(id)
);

create table if not exists user_questions(
	id bigint auto_increment unique,
    primary key( id ),
    
    user_id bigint unique not null,
    foreign key(user_id) references user ( id ),

    rg varchar(16) not null,
    uf_rg char(2) not null,
    city varchar(40) not null,
    cep varchar(12) not null,
    cpf varchar(20) not null unique,
    
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
    renda_per_capita varchar(10) not null,
    renda_ind varchar(10) not null,
    
    reason longtext not null comment 'reason of solicitation',
    
    bank_agency int(8) not null default 0,
    bank_account float(15) not null default 0,
    bank_name varchar(60) not null default 0    
);

create table admin(
	id bigint auto_increment unique not null,
    name varchar(60) not null,
    mail varchar(60) not null,
    pass varchar(255) not null,
    
    primary key(id),
        
    created_at datetime default now(),
    updated_at datetime    
);
create table unacceptable_hosts(
	id bigint auto_increment not null,
    ip_addr varchar(255) not null,
    user_agent varchar(255) not null,
    created_at datetime default now(),
    primary key(id)
);

-- https://avatars0.githubusercontent.com/u/56204477?s=400&u=c28c1517802d462612ae345b0c992cbf4f825637&v=4
insert into user(id, name, email, school, senha, rm, answered_questions, link_photo)
values(88,'VINI', 'viniolimpio3@gmail.com', 'escola', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '000000', true,  'https://avatars0.githubusercontent.com/u/56204477?s=400&u=c28c1517802d462612ae345b0c992cbf4f825637&v=4' );

INSERT INTO user_questions( user_id, rg, uf_rg, cpf, cep, qt_pc_desktop, qt_pc_notebook, qt_sm_phone, renda_per_capita, qtd_in_house, renda_ind, internet, reason, isp_configs, pc_desktop_configs, pc_notebook_configs, sm_phone_configs, city )
values( 88,  '00.000.000-0',  'SP',  '000.111.222-31',  '09-340340',  '2',  '2',  '1',  'R$232',  '2',  'R$3232,22',  '1',  'MOTIVOS',  'fasdf',  'wesdfadf',  'adfasd dfa',  'fdadfadfa', 'Mauá');


insert into admin(id, name, mail, pass)
values(88, 'admin', 'viniolimpio3@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef' );
