CREATE TABLE event_info
(
  event_id           INT PRIMARY KEY                     NOT NULL AUTO_INCREMENT,
  title              VARCHAR(70)                         NOT NULL,
  username           VARCHAR(40)                         NOT NULL,
  speaker            VARCHAR(100),
  location           VARCHAR(40)                         NOT NULL,
  hostname           VARCHAR(40),
  date_type          VARCHAR(10) DEFAULT 'date_st'       NOT NULL,
  date               DATETIME                            NOT NULL,
  category           VARCHAR(20)                         NOT NULL,
  register           TINYINT                             NOT NULL,
  register_date_type VARCHAR(10)                                  DEFAULT 'date_st',
  register_date      DATETIME,
  notification       TINYINT                                      DEFAULT 0,
  details            VARCHAR(300),
  short_url          VARCHAR(5),
  propa_url          VARCHAR(600),
  review_url         VARCHAR(600),
  edit_time          TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);
CREATE TABLE event_registration_common
(
  event_id          INT PRIMARY KEY NOT NULL,
  ticket_per_person INT             NOT NULL,
  confirm           TINYINT         NOT NULL
);
CREATE TABLE event_registration_date
(
  registration_serial INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  event_id            INT             NOT NULL,
  event_date          DATETIME        NOT NULL,
  ticket_count        INT             NOT NULL,
  register_count      INT DEFAULT 0   NOT NULL
);
CREATE TABLE event_registration_list
(
  registration_user_serial VARCHAR(10) DEFAULT ''              NOT NULL,
  registration_serial      INT                                 NOT NULL,
  registration_id          VARCHAR(20),
  registration_name        VARCHAR(20),
  registration_major       VARCHAR(40),
  registration_phone       VARCHAR(20),
  ticket_num               INT                                 NOT NULL,
  registration_message     VARCHAR(200),
  new_increment            TINYINT,
  register_time            TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (registration_user_serial, registration_serial)
);
CREATE TABLE login_serial
(
  username VARCHAR(40)             NOT NULL,
  serial   VARCHAR(32) PRIMARY KEY NOT NULL
);
CREATE TABLE published_event
(
  order_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  event_id INT             NOT NULL
);
CREATE TABLE recruit_info_activities
(
  recruit_id        INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  username          VARCHAR(40)     NOT NULL,
  activity_name     VARCHAR(40),
  activity_date     VARCHAR(40),
  activity_location VARCHAR(40)     NOT NULL,
  activity_details  VARCHAR(200)    NOT NULL
);
CREATE TABLE recruit_info_common
(
  username  VARCHAR(40) PRIMARY KEY             NOT NULL,
  details   VARCHAR(300)                        NOT NULL,
  edit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);
CREATE TABLE recruit_list
(
  recruit_register_id   INT PRIMARY KEY                     NOT NULL AUTO_INCREMENT,
  username              VARCHAR(40)                         NOT NULL,
  guest_id              VARCHAR(20)                         NOT NULL,
  guest_name            VARCHAR(40)                         NOT NULL,
  guest_phone           VARCHAR(20)                         NOT NULL,
  guest_major           VARCHAR(40)                         NOT NULL,
  guest_message         VARCHAR(200)                        NOT NULL,
  join_management       TINYINT DEFAULT 0                   NOT NULL,
  new_increment         TINYINT,
  recruit_register_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);
CREATE TABLE review_read
(
  count INT DEFAULT 0 NOT NULL
);
CREATE TABLE users
(
  username      VARCHAR(40) PRIMARY KEY NOT NULL,
  fullname      VARCHAR(20)             NOT NULL,
  email         VARCHAR(40)             NOT NULL,
  password      VARCHAR(32)             NOT NULL,
  event_limit   INT DEFAULT 2           NOT NULL,
  recruit_limit INT DEFAULT 2           NOT NULL,
  user_category VARCHAR(40)
);

INSERT INTO users VALUE ('admin', 'admin', 'root@lyq.me', '73a694ee2938d0d0f12531e2de0643ea', 2, 2);

