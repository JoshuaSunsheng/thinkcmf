-- 2017-7-15
alter table `cmf_doctor` add column `score` INT4  DEFAULT 0;


-- 2017-7-30
-- alter table `cmf_appointment` add column `cureTime` INT4  DEFAULT 0;
-- update  `cmf_appointment` set cureTime = "2017-07-30";
alter table `cmf_appointment` drop column `beginDate`;
alter table `cmf_appointment` drop column `endDate`;

alter table `cmf_appointment` add column `cureTime` date;
alter table `cmf_appointment` add column `flag` int4;


-- 2017-8-21
CREATE TABLE `cmf_card` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '银行卡id',
  `realName` varchar(20) DEFAULT '' COMMENT '银行卡开户名',
  `cardAsn` varchar(30) DEFAULT '1' COMMENT '银行卡号',
  `bankName` varchar(300) DEFAULT NULL COMMENT '银行名称',
  `province` varchar(255) DEFAULT '' COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `district` varchar(255) DEFAULT '' COMMENT '区',
  `subbranch` varchar(255) DEFAULT '1' COMMENT '支行',
  `doctorId` int(11) DEFAULT '0' COMMENT '医生id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='权限规则表';



-- 2017-8-21  增加appointment mobile字段
alter table `cmf_appointment` add column `mobile` varchar(11);
