
drop user if exists 'signup'@'%';
drop database if exists signup;

create database signup character set utf8mb4 collate utf8mb4_unicode_ci;
create user 'signup'@'%' identified by 'signup';

grant all privileges on signup.* to 'signup'@'%' with grant option;
set password for 'signup'@'%' = 'signup';

use signup;

create table su_contacts
(
    id         integer auto_increment,
    created_on timestamp not null default current_timestamp,
    updated_on timestamp null,
    name       varchar(100),
    email      varchar(100),
    telephone  varchar(12),
    primary key (id)
);
create trigger su_contacts_before_update before update on su_contacts for each row set new.updated_on = current_timestamp;

create table su_events
(
    id         integer auto_increment,
    version    integer      not null default 1,
    created_on timestamp    not null default current_timestamp,
    updated_on timestamp null,
    contact_id integer,
    name       varchar(255) not null,
    script     blob         not null,
    passcode   varchar(36)  not null,
    primary key (id)
);
create trigger su_events_before_update before update on su_events for each row set new.updated_on = current_timestamp;

create table su_event_histories
(
    id         integer auto_increment,
    created_on timestamp not null default current_timestamp,
    updated_on timestamp null,
    event_id   integer   not null,
    script     blob      not null,
    version    integer   not null,
    primary key (id)
);
create trigger su_event_histories_before_update before update on su_event_histories for each row set new.updated_on = current_timestamp;

create table su_volunteers
(
    id         integer auto_increment,
    created_on timestamp not null default current_timestamp,
    updated_on timestamp null,
    event_id   integer   not null,
    shift_id   integer   not null,
    contact_id integer   not null,
    primary key (id)
);
create trigger su_volunteers_before_update before update on su_volunteers for each row set new.updated_on = current_timestamp;

create table su_volunteer_properties
(
    volunteer_id integer      not null,
    name         varchar(100) not null,
    value        blob         not null,
    primary key (volunteer_id, name)
);
