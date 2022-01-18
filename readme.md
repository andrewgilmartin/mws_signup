Volunteer Signup Application

This application was written to support the Meadowbrook Waldorf school's annual holiday fair. This fair is a multi-day event with many activites and much need of volunteers. The school was using a Google sheet before and it was not going well. This application was written over a few weeks and was the author's only PHP application!

Installation

To install the application you will need a PHP server, commonly an Apache HTTPd server with the PHP7 module, and a MySql database server.

The application is easilly installed by adding it to your web servers document root. Since the name of the directory containing the application is not significant to its operation (see site-include.php) name the directory whatever seems appropriate for your organization.

Create the database and database user before using the application. To do that, pick a database name, user name, and user password

	drop database if exists MWS_SIGNUP_DATABASE;
	drop user if exists MWS_SIGNUP_USER;

	create database MWS_SIGNUP_DATABASE character set 'utf8mb4' collate 'utf8mb4_unicode_ci';
	create user MWS_SIGNUP_USER identified by 'MWS_SIGNUP_PASSWORD';
	grant all privileges on MWS_SIGNUP_DATABASE.* to MWS_SIGNUP_USER with grant option;

	flush privileges;

Next update the site-include.php file with the database details and the other configurations. The magicnumber is used to protect unauthorized creation or updating of events. Use a value that is obscure but easy to copy and paste from an email!




