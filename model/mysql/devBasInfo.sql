INSERT INTO `configuration` (`confiId`, `confiName`, `confiNo`, `departId`, `confiChar`, `confiAdd`, `confiUse`) VALUES (1, '1号配置柜', 'apple', 1, 'cute', 'bush', 'eat');
INSERT INTO `configuration` (`confiId`, `confiName`, `confiNo`, `departId`, `confiChar`, `confiAdd`, `confiUse`) VALUES (2, '2号配置柜', 'pear', 3, 'smart', 'house', 'take');
INSERT INTO `configuration` (`confiId`, `confiName`, `confiNo`, `departId`, `confiChar`, `confiAdd`, `confiUse`) VALUES (3, '3号配置柜', 'grasp', 4, 'handsome', 'tip', 'eat');
INSERT INTO `configuration` (`confiId`, `confiName`, `confiNo`, `departId`, `confiChar`, `confiAdd`, `confiUse`) VALUES (4, '4号配置柜', 'banana', 2, 'noble', 'tree', 'look');

insert into repair (`repairTime`,  `devId`, `repairMan`, `repairInfo`) select `repairTime`,  `devId`, `repairMan`, `repairInfo` from repair;


-- 分厂记录表
create table factory(
facId int primary key auto_increment,
facName varchar(10) not null
);

-- 部门记录表
create table department(
departId int primary key auto_increment,
departName varchar(10) not null,
facId int,
foreign key (facId) references factory (facId) on delete set null on update cascade
);

-- 配置柜记录表
drop table if exists configuration;
create table configuration(
confiId int primary key auto_increment,
confiName varchar(10) not null,
departId int,
foreign key (departId) references department (departId) on delete set null on update cascade 
);

-- 设备基本记录表的外键设置
alter table devbasinfo add foreign key (confiId) references configuration (confiId) on delete set null on update cascade;

-- 巡检记录表
drop table if exists inspect;
create table inspect(
inspectId int primary key auto_increment,
devState varchar(10) not null,
inspecter varchar(10) not null,
inspeInfo TEXT,
inspeTime datetime,
devId int,
foreign key (devId) references devbasinfo (devId) on delete set null on update cascade 
);

-- 维修/更换记录表
drop table if exists repair;
create table repair(
repairId int primary key auto_increment,
repairState varchar(10) not null,
repairMan varchar(10) not null,
repairInfo text,
devId int,
foreign key (devId) references devbasinfo (devId) on delete set null on update cascade 
);

-- 设备具体信息记录表
create table devdetail(
detailId int primary key auto_increment,
devUse varchar(30),
devTime DATE not null,
devPrice varchar(10) not null,
devId int,
foreign key (devId) references devbasinfo (devId) on delete set null on update cascade 
);

-- test
create table student1(
name varchar(5),
subject varchar(10),
score int(4)
);
create table matching(
mid int primary key auto_increment,
hid int,
gid int,
mres varchar(20),
matime date
);

create table teaming(
tid int primary key auto_increment,
tname varchar(20)
);


insert into student VALUES
(' 张三 ',' 数学    ','    90 '),
(' 张三 ',' 语文    ','    50 '),
(' 张三 ',' 地理    ','    40 '),
(' 李四 ',' 语文    ','    55 '),
(' 李四 ',' 政治    ','    45 '),
(' 王五 ',' 政治    ','    30 ');
.

create table category(
cat_id int primary key auto_increment,
cat_name varchar(15) not null
);


insert into category (cat_name) values
('手机类型'),
('cdma手机'),
('gsm手机'),
('3g手机'),
('双模手机'),
('手机配件'),
('充电器'),
('耳机'),
('电池'),
('读卡器和内存卡'),
('充值卡'),
('小灵通/固话充值卡'),
('移动手机充值卡'),
('联通手机充值卡');

insert into matching values
('   1 ','    1 ','    2 ',' 2:0  ',' 2006-05-21 '),
('   2 ','    2 ','    3 ',' 1:2  ',' 2006-06-21 '),
('   3 ','    3 ','    1 ',' 2:5  ',' 2006-06-25 '),
('   4 ','    2 ','    1 ',' 3:2  ',' 2006-07-21 ');

insert into teaming values
('    1 ',' 国安     '),
('    2 ',' 申花     '),
('    3 ',' 传智联队 ');

select t1.tname as hname,mres,t2.tname as gname,matime
from 
matching left join teaming as t1
on matching.hid=t1.tid
left join teaming as t2
on matching.gid=t2.tid where matime like '%2006-6-%';

create table one(
oneid varchar,
onenums int(3)
);

create table one(
twoid varchar(2) not null,
nums int(3) not null
);

insert into two values
(' a    ','    5 '),
(' b    ','   10 '),
(' c    ','   15 '),
(' d    ','   10 ');

select goods_id,goods.cat_id,cat_name,goods_name,shop_price
from 
goods left join category
on goods.cat_id=category.cat_id;

create table boy(
name char(3),
flower char(5)
);

insert into boy
	values
	('林书豪','玫瑰'),
	('刘翔','桃花'),
	('周杰伦','茉莉'),
	('胡歌','荷花'),
	('刘德华','狗一把花');

create table girl(
name char(3),
flower char(5)
);

insert into girl
	values
	('艾薇儿','玫瑰'),
	('刘诗诗','桃花'),
	('芙蓉姐','茉莉'),
	('凤姐','茉莉'),
	('李志玲','荷花');

insert into g
values
(1,'apple',22),
(2,'banana',19),
(3,'grasp',12),
(4,'peach',8);

create trigger tg2
after insert on o
for each row
begin
update g set num =num-3 where id=2;
end

create trigger tg3
after insert on o
for each row
update g set num=num-new.much where id=new.gid;
	end

	create trigger tg4
		after delete on o
		for each row
		update g set num=num+old.much where id=old.gid

select devTime from devbasinfo  where devId>$id and devNo=(select devNo from devbasinfo where devId=$id) order by devId asc limit 1;
WHERE id > $id ORDER BY id ASC LIMIT 1  

select devTime from devbasinfo  where devId>3 and devNo=(select devNo  from devbasinfo where devId=3) order by devId asc limit 1;



-- 路径
SELECT id AS ID,pid AS 父ID ,levels AS 父到子之间级数, paths AS 父到子路径 FROM (
   SELECT id,pid,
   @le:= IF (pid = 0 ,0, 
     IF( LOCATE( CONCAT('|',pid,':'),@pathlevel)  > 0 ,   
         SUBSTRING_INDEX( SUBSTRING_INDEX(@pathlevel,CONCAT('|',pid,':'),-1),'|',1) +1
    ,@le+1) ) levels
   , @pathlevel:= CONCAT(@pathlevel,'|',id,':', @le ,'|') pathlevel
   , @pathnodes:= IF( pid =0,',0', 
      CONCAT_WS(',',
      IF( LOCATE( CONCAT('|',pid,':'),@pathall) > 0 , 
        SUBSTRING_INDEX( SUBSTRING_INDEX(@pathall,CONCAT('|',pid,':'),-1),'|',1)
       ,@pathnodes ) ,pid ) )paths
  ,@pathall:=CONCAT(@pathall,'|',id,':', @pathnodes ,'|') pathall 
    FROM treenodes, 
  (SELECT @le:=0,@pathlevel:='', @pathall:='',@pathnodes:='') vv
  ORDER BY pid,id
  ) src
ORDER BY id

select devId from devinfo where devId=5 or parentList like '%5%';


select repairTime,repairMan,repairInfo,repairId, old.devName as old_name, new.devName as new_name
from 
repair 
left join devinfo as old
on repair.old_code=old.devCode
left join devinfo as new
on repair.new_code=new.devCode