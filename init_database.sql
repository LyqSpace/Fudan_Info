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
	speaker varchar(100),
	location varchar(40) not null,
	hostname varchar(40),
	date_type varchar(10) not null default 'date_st',
	date datetime not null,
	category varchar(20) not null,
	register boolean not null,
	register_date_type varchar(10) default 'date_st',
	register_date datetime,
	notification boolean default false,
	publish boolean default false,
	details varchar(300),
	short_url varchar(5),
	propa_url varchar(600),
	review_url varchar(600),
	edit_time timestamp default current_timestamp
);

create table event_registration (
	event_id integer not null,
	registration_id boolean not null default false,
  registration_name boolean not null default false,
  registration_major boolean not null default false,
  registration_phone boolean not null default false,
	ticket_per_person integer not null
);

create table event_date (
	registration_serial integer not null auto_increment primary key,
	event_id integer not null,
	event_date datetime not null,
	ticket_count integer not null,
	register_count integer not null default 0
);

create table event_register_list (
	register_serial varchar(10) not null primary key,
	registration_serial integer not null,
	register_id integer,
	register_name varchar(20),
	register_major varchar(40),
	register_phone varchar(20),
	ticket_num integer not null,
	message varchar(200),
	register_time timestamp default current_timestamp
);

create table recruit_info (
	recruit_id integer not null auto_increment primary key,
	username varchar(40) not null,
	publish boolean not null default false,
	details varchar(300) not null,
	edit_time timestamp default current_timestamp
);

create table published_event (
	order_id integer not null primary key auto_increment,
	event_id integer not null
);

create table review_read (
	count integer not null default 0
);

insert into users value ('admin', 'admin', 'root@lyq.me', '73a694ee2938d0d0f12531e2de0643ea', 2, 2);
