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
	date datetime not null,
	location varchar(40) not null,
	category varchar(20) not null,
	notification boolean default false,
	publish boolean default false,
	details varchar(300) not null default '喵～信息都已经交代完整啦，祝您参加活动愉快哦～',
	edit_time timestamp default current_timestamp
);

create table recruit_info (
	recruit_id integer not null auto_increment primary key,
	username varchar(40) not null,
	publish boolean not null default false,
	details varchar(140) not null,
	edit_time timestamp default current_timestamp
);

create table published_event (
	order_id integer not null primary key,
	fullname varchar(20) not null,
	details varchar(300) not null
);

insert into users value ('admin', 'admin', 'root@lyq.me', '2dda1312d69f45a4c0f6e9c8ca20bf6b', 2, 2);
