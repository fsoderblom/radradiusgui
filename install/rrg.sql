-- MySQL dump 9.10
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version	4.0.18

--
-- Current Database: freeradius
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ freeradius;

USE rrg;

--
-- Table structure for table `freeradius_gui_logdb`
--

CREATE TABLE freeradius_gui_logdb (
  ID int(11) NOT NULL auto_increment,
  MODIFIED timestamp(14) NOT NULL,
  HOST varchar(255) default NULL,
  DATETIME datetime default NULL,
  username varchar(16) default NULL,
  type varchar(64) default NULL,
  category varchar(64) default NULL,
  description varchar(255) default NULL,
  userdb_id int(11) default NULL,
  PRIMARY KEY  (ID)
) TYPE=MyISAM;

--
-- Table structure for table `freeradius_gui_macdb`
--

CREATE TABLE freeradius_gui_macdb (
  ID int(11) NOT NULL auto_increment,
  MODIFIED timestamp(14) NOT NULL,
  mac48 varchar(12) default NULL,
  vlandb_vlanid int(11) default NULL,
  userdb_id int(11) default NULL,
  PRIMARY KEY  (ID),
  UNIQUE KEY mac48 (mac48)
) TYPE=MyISAM;

--
-- Table structure for table `freeradius_gui_userdb`
--

CREATE TABLE freeradius_gui_userdb (
  ID int(11) NOT NULL auto_increment,
  MODIFIED timestamp(14) NOT NULL,
  firstname varchar(100) default NULL,
  lastname varchar(100) default NULL,
  username varchar(16) default NULL,
  password varchar(64) default NULL,
  permissions char(2) default NULL,
  vlandb_vlanid varchar(255) default NULL,
  userdb_id int(11) default NULL,
  PRIMARY KEY  (ID),
  UNIQUE KEY username (username)
) TYPE=MyISAM;

--
-- Table structure for table `freeradius_gui_vlandb`
--

CREATE TABLE freeradius_gui_vlandb (
  ID int(11) NOT NULL auto_increment,
  MODIFIED timestamp(14) NOT NULL,
  vlanid int(11) default NULL,
  vlanpseudo varchar(255) default NULL,
  userdb_id int(11) default NULL,
  PRIMARY KEY  (ID),
  UNIQUE KEY vlanid (vlanid)
) TYPE=MyISAM;

--
-- Table structure for table `freeradius_serverhealth`
--

CREATE TABLE freeradius_serverhealth (
  id int(10) NOT NULL auto_increment,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  host varchar(128) NOT NULL default '',
  message varchar(128) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Server health checks are stored here from the radping utilit';

