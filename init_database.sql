drop database if exists fudan_info;
create database fudan_info;

use fudan_info;

create table login_serial ( 
	username varchar(40) not null,
	serial varchar(32) not null primary key
);

create table users (
	username varchar(40) not null primary key,
	fullname varchar(20) not null,
	email varchar(40) not null,
	password varchar(32) not null,
	event_limit integer not null default 2,
	recruit_limit integer not null default 2
);

create table event_info (
	event_id integer not null auto_increment primary key,
	title varchar(70) not null,
	username varchar(40) not null,
	speaker varchar(50),
	location varchar(40) not null,
	date_st datetime not null,
	category varchar(20) not null,
	register boolean not null,
	register_st datetime,
	register_ed datetime,
	notification boolean default false,
	publish boolean default false,
	details varchar(300),
	review_url varchar(300),
	edit_time timestamp default current_timestamp
);

create table recruit_info (
	recruit_id integer not null auto_increment primary key,
	username varchar(40) not null,
	publish boolean not null default false,
	details varchar(300) not null,
	edit_time timestamp default current_timestamp
);

create table published_event (
	order_id integer not null primary key,
	event_id integer not null
);

insert into users value ('admin', 'admin', 'root@lyq.me', '73a694ee2938d0d0f12531e2de0643ea', 2, 2);
