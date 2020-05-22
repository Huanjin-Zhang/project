DROP DATABASE `dbproject1`;
CREATE DATABASE `dbproject1`;

USE `dbproject1`;

/*Table structure for table `user` */
CREATE TABLE `user` (
  `uemail` varchar(40) NOT NULL,
  `username` varchar(40) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `displayname` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`uemail`)
);

/*Data for the table `user` */
insert  into `user` (`uemail`,`username`,`password`,`displayname`) values 
('ml2169@nyu.edu','Mary Lopez','123456789','displayname1'),
('js3245@nyu.edu','John Smith','123456789','displayname2'),
('bj9685@nyu.edu','Bob Jones','123456789','displayname3'),
('jw5938@nyu.edu','Jake Weber','123456789','displayname4'),
('jb1932@nyu.edu','Jeff Bezos','123456789','displayname5');

/*Table structure for table `project` */
CREATE TABLE `project` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `creator` varchar(40) DEFAULT NULL,
  `ptitle` varchar(40) DEFAULT NULL,
  `pdescription` varchar(100) DEFAULT NULL,
  `pcreatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `creator` (`creator`),
  CONSTRAINT `proj_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `user` (`uemail`)
);

/*Data for the table `project` */
insert  into `project`(`pid`,`creator`,`ptitle`,`pdescription`,`pcreatetime`) values 
(1,'ml2169@nyu.edu','Byte Dance','it is a short video project','2020-03-12 23:43:23'),
(2,'js3245@nyu.edu','Tencent Game','it is a game center project','2019-10-20 10:43:23'),
(3,'jw5938@nyu.edu','Alibaba', 'it is an online shopping project','2015-05-15 12:59:23'),
(4,'jb1932@nyu.edu','Amazon Kindle', 'it is an electronic reading project','2016-12-23 12:23:23'),
(5,'jb1932@nyu.edu','Amazon Fresh', 'it is an online fresh shopping project','2017-04-04 08:20:23');


/*Table structure for table `project_leads` */
CREATE TABLE `project_leads` (
  `pid` int NOT NULL,
  `uemail` varchar(40) NOT NULL,
  `addtime`datetime DEFAULT NULL,
  PRIMARY KEY (`pid`,`uemail`),
  KEY `uemail` (`uemail`),
  KEY `pid` (`pid`),
  CONSTRAINT `lead_ibfk_1` FOREIGN KEY (`uemail`) REFERENCES `user` (`uemail`),
  CONSTRAINT `lead_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `project` (`pid`)
);

/*Data for the table `project_leads` */
insert  into `project_leads`(`pid`,`uemail`,`addtime`) values 
(1,'ml2169@nyu.edu','2020-03-12 23:43:23'),
(1,'bj9685@nyu.edu','2020-03-22 23:43:23'),
(2,'js3245@nyu.edu','2019-10-20 10:43:23'),
(3,'jw5938@nyu.edu','2015-05-15 12:59:23'),
(3,'bj9685@nyu.edu','2015-05-25 12:59:23'),
(3,'jb1932@nyu.edu','2015-06-01 12:59:23'),
(4,'jb1932@nyu.edu','2016-12-23 12:23:23'),
(4,'ml2169@nyu.edu','2020-03-12 23:43:23'),
(5,'jb1932@nyu.edu','2017-04-04 08:20:23'),
(5,'js3245@nyu.edu','2019-10-20 10:43:23'),
(5,'jw5938@nyu.edu','2020-05-15 12:59:23');



/*Table structure for table `issue` */
CREATE TABLE `issue` (
  `iid` int NOT NULL AUTO_INCREMENT,
  `pid` int DEFAULT NULL,
  `ititle` varchar(40) DEFAULT NULL,
  `idescription` varchar(100) DEFAULT NULL,
  `reporter` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`iid`),
  KEY `pid` (`pid`),
  KEY `reporter` (`reporter`),
  CONSTRAINT `issue_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `project` (`pid`),
  CONSTRAINT `issue_ibfk_2` FOREIGN KEY (`reporter`) REFERENCES `user` (`uemail`)
);

/*Data for the table `issue` */
insert  into `issue`(`iid`,`pid`,`ititle`,`idescription`,`reporter`) values 
(1,1,'front-end bug','there is a bug in the front end','js3245@nyu.edu'),
(2,1,'back-end bug','there is a bug in the back end','ml2169@nyu.edu'),
(3,2,'front-end bug','there is a bug in the front end','jw5938@nyu.edu'),
(4,3,'front-end bug','there is a bug in the front end','bj9685@nyu.edu'),
(5,3,'back-end bug','there is a bug in the back end','ml2169@nyu.edu'),
(6,4,'front-end screen','there is a bug in the front end screen','js3245@nyu.edu'),
(7,5,'front-end bug','there is a bug in the front end','jb1932@nyu.edu'),
(8,5,'back-end bug','there is a bug in the front end','jb1932@nyu.edu'),
(9,5,'database bug','there is a bug in the database','bj9685@nyu.edu'),
(10,5,'server bug','there is a bug in the server','ml2169@nyu.edu');


/*Table structure for table `assignee` */
CREATE TABLE `assignee` (
  `iid` int NOT NULL,
  `uemail` varchar(40) NOT NULL,
  `assigndate` datetime DEFAULT NULL,
  PRIMARY KEY (`iid`,`uemail`),
  KEY `uemail` (`uemail`),
  KEY `iid` (`iid`),
  CONSTRAINT `assignee_ibfk_1` FOREIGN KEY (`uemail`) REFERENCES `user` (`uemail`),
  CONSTRAINT `assignee_ibfk_2` FOREIGN KEY (`iid`) REFERENCES `issue` (`iid`)
);

/*Data for the table `assignee` */
insert  into `assignee`(`iid`,`uemail`,`assigndate`) values 
(1,'js3245@nyu.edu','2020-04-27 12:00:00'),
(2,'jb1932@nyu.edu','2020-04-27 12:00:00'),
(3,'ml2169@nyu.edu','2020-04-27 12:00:00'),
(3,'jw5938@nyu.edu','2020-04-27 12:00:00'),
(3,'jb1932@nyu.edu','2020-04-27 12:00:00'),
(4,'ml2169@nyu.edu','2020-04-27 12:00:00'),
(5,'js3245@nyu.edu','2020-04-27 12:00:00'),
(6,'jw5938@nyu.edu','2020-04-27 12:00:00'),
(6,'jb1932@nyu.edu','2020-04-27 12:00:00'),
(7,'ml2169@nyu.edu','2020-04-27 12:00:00'),
(8,'bj9685@nyu.edu','2020-04-27 12:00:00'),
(9,'ml2169@nyu.edu','2020-04-27 12:00:00'),
(10,'bj9685@nyu.edu','2020-04-27 12:00:00');

/* Table structure for table `project_status`*/
CREATE TABLE `project_status` (
  `pid` int NOT NULL,
  `status` varchar(40) NOT NULL,
  PRIMARY KEY (`pid`, `status`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  CONSTRAINT `status_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `project` (`pid`)
);
/* Data for the table `project_status` */
insert into `project_status`(`pid`, `status`) values
(1,'OPEN'),
(1,'IN PROGRESS'),
(1,'UNDER REVIEW'),
(1,'FINAL APPROVAL'),
(1,'CLOSED'),
(2,'OPEN'),
(2,'IN PROGRESS'),
(2,'RESOLVED'),
(2,'REOPEN'),
(2,'CLOSED'),
(3,'OPEN'),
(3,'IN PROGRESS'),
(3,'UNDER REVIEW'),
(3,'FINAL APPROVAL'),
(3,'CLOSED'),
(4,'OPEN'),
(4,'IN PROGRESS'),
(4,'RESOLVED'),
(4,'REOPEN'),
(4,'CLOSED'),
(5,'OPEN'),
(5,'IN PROGRESS'),
(5,'UNDER REVIEW'),
(5,'FINAL APPROVAL'),
(5,'CLOSED');


/* Table structure for table `workflow`*/
CREATE TABLE `workflow` (
  `pid` int NOT NULL,
  `beginstatus` varchar(40) NOT NULL,
  `endstatus` varchar(40) NOT NULL,
  PRIMARY KEY (`pid`, `beginstatus`, `endstatus`),
  KEY `pid` (`pid`),
  KEY `beginstatus` (`beginstatus`),
  KEY `endstatus` (`endstatus`),
  CONSTRAINT `workflow_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `project` (`pid`),
  CONSTRAINT `workflow_ibfk_2` FOREIGN KEY (`beginstatus`) REFERENCES `project_status` (`status`),
  CONSTRAINT `workflow_ibfk_3` FOREIGN KEY (`endstatus`) REFERENCES `project_status` (`status`)
);
/* Data for the table `workflow` */
insert into `workflow`(`pid`, `beginstatus`, `endstatus`) values
(1,'OPEN','IN PROGRESS'),
(1,'IN PROGRESS','UNDER REVIEW'),
(1,'IN PROGRESS','OPEN'),
(1,'UNDER REVIEW','FINAL APPROVAL'),
(1,'UNDER REVIEW','IN PROGRESS'),
(1,'FINAL APPROVAL','CLOSED'),
(1,'FINAL APPROVAL','UNDER REVIEW'),
(2,'OPEN','RESOLVED'),
(2,'OPEN','CLOSED'),
(2,'OPEN','IN PROGRESS'),
(2,'RESOLVED','REOPEN'),
(2,'RESOLVED','CLOSED'),
(2,'REOPEN','IN PROGRESS'),
(2,'REOPEN','RESOLVED'),
(2,'REOPEN','CLOSED'),
(2,'IN PROGRESS','OPEN'),
(2,'IN PROGRESS','RESOLVED'),
(2,'IN PROGRESS','CLOSED'),
(3,'OPEN','IN PROGRESS'),
(3,'IN PROGRESS','UNDER REVIEW'),
(3,'IN PROGRESS','OPEN'),
(3,'UNDER REVIEW','FINAL APPROVAL'),
(3,'UNDER REVIEW','IN PROGRESS'),
(3,'FINAL APPROVAL','CLOSED'),
(3,'FINAL APPROVAL','UNDER REVIEW'),
(4,'OPEN','RESOLVED'),
(4,'OPEN','CLOSED'),
(4,'OPEN','IN PROGRESS'),
(4,'RESOLVED','REOPEN'),
(4,'RESOLVED','CLOSED'),
(4,'REOPEN','IN PROGRESS'),
(4,'REOPEN','RESOLVED'),
(4,'REOPEN','CLOSED'),
(4,'IN PROGRESS','OPEN'),
(4,'IN PROGRESS','RESOLVED'),
(4,'IN PROGRESS','CLOSED'),
(5,'OPEN','IN PROGRESS'),
(5,'IN PROGRESS','UNDER REVIEW'),
(5,'IN PROGRESS','OPEN'),
(5,'UNDER REVIEW','FINAL APPROVAL'),
(5,'UNDER REVIEW','IN PROGRESS'),
(5,'FINAL APPROVAL','CLOSED'),
(5,'FINAL APPROVAL','UNDER REVIEW');

/* Table structure for table `status_history`*/
CREATE TABLE `status_history` (
  `iid` int NOT NULL,
  `currentstatus` varchar(40) NOT NULL,
  `modifytime` datetime NOT NULL,
  PRIMARY KEY (`iid`, `currentstatus`, `modifytime`),
  KEY `iid` (`iid`),
  KEY `currentstatus` (`currentstatus`),
  CONSTRAINT `history_ibfk_1` FOREIGN KEY (`iid`) REFERENCES `issue` (`iid`),
  CONSTRAINT `history_ibfk_2` FOREIGN KEY (`currentstatus`) REFERENCES `project_status` (`status`)
);
/* Data for the table `status_history` */
insert into `status_history`(`iid`, `currentstatus`, `modifytime`) values
(1,'OPEN','2020-01-01 12:00:00'),
(1,'IN PROGRESS','2020-01-02 12:00:00'),
(1,'UNDER REVIEW','2020-01-03 12:00:00'),
(1,'FINAL APPROVAL','2020-01-04 12:00:00'),
(1,'UNDER REVIEW','2020-01-05 12:00:00'),
(1,'FINAL APPROVAL','2020-01-06 12:00:00'),
(1,'CLOSED','2020-01-07 12:00:00'),
(2,'OPEN','2020-01-01 12:00:00'),
(3,'OPEN','2020-01-01 12:00:00'),
(3,'IN PROGRESS','2020-01-02 12:00:00'),
(3,'RESOLVED','2020-01-03 12:00:00'),
(4,'OPEN','2020-01-01 12:00:00'),
(5,'OPEN','2020-01-01 12:00:00'),
(5,'IN PROGRESS','2020-01-02 12:00:00'),
(6,'OPEN','2020-01-01 12:00:00'),
(7,'OPEN','2020-01-01 12:00:00'),
(7,'IN PROGRESS','2020-01-02 12:00:00'),
(7,'OPEN','2020-01-03 12:00:00'),
(8,'OPEN','2020-01-01 12:00:00'),
(9,'OPEN','2020-01-01 12:00:00'),
(10,'OPEN','2020-01-01 12:00:00'),
(10,'IN PROGRESS','2020-01-02 12:00:00'),
(10,'UNDER REVIEW','2020-01-03 12:00:00'),
(10,'FINAL APPROVAL','2020-01-04 12:00:00'),
(10,'UNDER REVIEW','2020-01-05 12:00:00'),
(10,'FINAL APPROVAL','2020-01-06 12:00:00');