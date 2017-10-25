alter table confirm add techscale varchar(20);
alter table confirm add techaccu varchar(20);

-- 2017-10-22
insert into staff_func ()

insert into staff_role (id, title) 
	values 
	(9, '安检部'), 
	(10, '安检设备管理'),
	(11, '安检设备检修'),
	(12, '安检设备浏览');

insert into staff_role_func (rid, fid)
	values 
	(9, 15),
	(9, 16),
	(9, 17),
	(9, 10),
	(9, 11),
	(10, 15),
	(10, 16),
	(10, 17),
	(11, 15),
	(11, 17),
	(12, 15);

drop table if exists `safe`;
create table `safe`(
	id int(11) not null auto_increment primary key,
	name varchar(15) not null,
	codeManu varchar(50) not null,
	loc varchar(50) not null,
	takeTime date not null,
	valid date not null,
	circle int(11) not null,
	takeDpt int(11) not null
)engine=innodb default charset=utf8;

DROP TABLE IF EXISTS `safe_check`;
CREATE TABLE `safe_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) DEFAULT NULL,
  `time` date DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `res` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devid` (`devid`) USING BTREE,
  KEY `user` (`user`),
  CONSTRAINT `safe_check_ibfk_1` FOREIGN KEY (`devid`) REFERENCES `safe` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
