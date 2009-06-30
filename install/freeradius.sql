-- MySQL dump 9.10
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version	4.0.18

--
-- Current Database: radius
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ radius;

USE radius;

--
-- Table structure for table `nas`
--

CREATE TABLE nas (
  id int(10) NOT NULL auto_increment,
  nasname varchar(128) NOT NULL default '',
  shortname varchar(32) default NULL,
  type varchar(30) default 'other',
  ports int(5) default NULL,
  secret varchar(60) NOT NULL default 'secret',
  community varchar(50) default NULL,
  description varchar(200) default 'RADIUS Client',
  PRIMARY KEY  (id),
  KEY nasname (nasname)
) TYPE=MyISAM;

--
-- Table structure for table `radacct`
--

CREATE TABLE radacct (
  RadAcctId bigint(21) NOT NULL auto_increment,
  AcctSessionId varchar(32) NOT NULL default '',
  AcctUniqueId varchar(32) NOT NULL default '',
  UserName varchar(64) NOT NULL default '',
  Realm varchar(64) default '',
  NASIPAddress varchar(15) NOT NULL default '',
  NASPortId varchar(15) default NULL,
  NASPortType varchar(32) default NULL,
  AcctStartTime datetime NOT NULL default '0000-00-00 00:00:00',
  AcctStopTime datetime NOT NULL default '0000-00-00 00:00:00',
  AcctSessionTime int(12) default NULL,
  AcctAuthentic varchar(32) default NULL,
  ConnectInfo_start varchar(50) default NULL,
  ConnectInfo_stop varchar(50) default NULL,
  AcctInputOctets bigint(20) default NULL,
  AcctOutputOctets bigint(20) default NULL,
  CalledStationId varchar(50) NOT NULL default '',
  CallingStationId varchar(50) NOT NULL default '',
  AcctTerminateCause varchar(32) NOT NULL default '',
  ServiceType varchar(32) default NULL,
  FramedProtocol varchar(32) default NULL,
  FramedIPAddress varchar(15) NOT NULL default '',
  AcctStartDelay int(12) default NULL,
  AcctStopDelay int(12) default NULL,
  XAscendSessionSvrKey varchar(10) default NULL,
  PRIMARY KEY  (RadAcctId),
  KEY UserName (UserName),
  KEY FramedIPAddress (FramedIPAddress),
  KEY AcctSessionId (AcctSessionId),
  KEY AcctUniqueId (AcctUniqueId),
  KEY AcctStartTime (AcctStartTime),
  KEY AcctStopTime (AcctStopTime),
  KEY NASIPAddress (NASIPAddress)
) TYPE=MyISAM;

--
-- Table structure for table `radcheck`
--

CREATE TABLE radcheck (
  id int(11) unsigned NOT NULL auto_increment,
  UserName varchar(64) NOT NULL default '',
  Attribute varchar(32) NOT NULL default '',
  op char(2) NOT NULL default '==',
  Value varchar(253) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY UserName (UserName(32))
) TYPE=MyISAM;

--
-- Table structure for table `radgroupcheck`
--

CREATE TABLE radgroupcheck (
  id int(11) unsigned NOT NULL auto_increment,
  GroupName varchar(64) NOT NULL default '',
  Attribute varchar(32) NOT NULL default '',
  op char(2) NOT NULL default '==',
  Value varchar(253) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY GroupName (GroupName(32))
) TYPE=MyISAM;

--
-- Table structure for table `radgroupreply`
--

CREATE TABLE radgroupreply (
  id int(11) unsigned NOT NULL auto_increment,
  GroupName varchar(64) NOT NULL default '',
  Attribute varchar(32) NOT NULL default '',
  op char(2) NOT NULL default '=',
  Value varchar(253) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY GroupName (GroupName(32))
) TYPE=MyISAM;

--
-- Table structure for table `radippool`
--

CREATE TABLE radippool (
  id int(11) unsigned NOT NULL auto_increment,
  pool_name varchar(30) NOT NULL default '',
  FramedIPAddress varchar(15) NOT NULL default '',
  NASIPAddress varchar(15) NOT NULL default '',
  CalledStationId varchar(30) NOT NULL default '',
  CallingStationID varchar(30) NOT NULL default '',
  expiry_time datetime NOT NULL default '0000-00-00 00:00:00',
  username varchar(64) NOT NULL default '',
  pool_key varchar(30) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `radpostauth`
--

CREATE TABLE radpostauth (
  id int(11) NOT NULL auto_increment,
  user varchar(64) NOT NULL default '',
  pass varchar(64) NOT NULL default '',
  reply varchar(32) NOT NULL default '',
  date timestamp(14) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `radreply`
--

CREATE TABLE radreply (
  id int(11) unsigned NOT NULL auto_increment,
  UserName varchar(64) NOT NULL default '',
  Attribute varchar(32) NOT NULL default '',
  op char(2) NOT NULL default '=',
  Value varchar(253) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY UserName (UserName(32))
) TYPE=MyISAM;

--
-- Table structure for table `usergroup`
--

CREATE TABLE usergroup (
  UserName varchar(64) NOT NULL default '',
  GroupName varchar(64) NOT NULL default '',
  priority int(11) NOT NULL default '1',
  KEY UserName (UserName(32))
) TYPE=MyISAM;
