-- This script is used to create the stickydb database and the stickynotes table and stickyuser table.
-- This script will need to be run before the website is functional.
-- To run this script use the 'source' command giving in the script path 'drive:/pathToFile/create_stickydb.sql'.
-- This script was designed to be run as root and will require full privileges.

DROP DATABASE IF EXISTS stickydb;
CREATE DATABASE stickydb;

USE stickydb;

DROP TABLE IF EXISTS stickyuser;

CREATE TABLE `stickyuser` (
	`id` int(4) unsigned PRIMARY KEY AUTO_INCREMENT,
	`email` varchar(50) NOT NULL,
	`password` varchar(256) NOT NULL,
	`login_attempt` int (1) unsigned default '0',
	`last_login` timestamp
	)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS stickynotes;

CREATE TABLE `stickynotes` (
	`id` int(4) unsigned PRIMARY KEY AUTO_INCREMENT,
	`text` varchar(2000) NOT NULL,
	`leftpos` decimal(6,2) NOT NULL default '0.0',
	`toppos` decimal(6,2) NOT NULL default '0.0',
	`zindex` int(4) NOT NULL DEFAULT '0',
	`owner` int(4) unsigned NOT NULL,
	CONSTRAINT stickynotes_fk FOREIGN KEY (owner) REFERENCES stickyuser(id) ON DELETE CASCADE
	)DEFAULT CHARSET=utf8;
