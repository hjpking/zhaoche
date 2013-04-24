/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2013-4-24 11:36:21                           */
/*==============================================================*/


drop table if exists zc_be_user_pay_record;

drop table if exists zc_bill_rule;

drop table if exists zc_car_level;

drop table if exists zc_car_model;

drop index Index_card_no on zc_card;

drop table if exists zc_card;

drop table if exists zc_card_model;

drop index Index_phone on zc_chauffeur;

drop index index_cname on zc_chauffeur;

drop table if exists zc_chauffeur;

drop table if exists zc_chauffeur_location;

drop table if exists zc_chauffeur_to_user_pay_log;

drop index Index_phone on zc_chauffeur_verify;

drop table if exists zc_chauffeur_verify;

drop table if exists zc_city;

drop table if exists zc_city_airport;

drop table if exists zc_city_useful_addresse;

drop table if exists zc_competence;

drop index Index_staff_id_competence_id on zc_competence_correspond;

drop table if exists zc_competence_correspond;

drop table if exists zc_department;

drop table if exists zc_feedback;

drop table if exists zc_message;

drop table if exists zc_message_category;

drop table if exists zc_message_send_record;

drop table if exists zc_order;

drop table if exists zc_order_run_path;

drop table if exists zc_pay_record;

drop table if exists zc_service_type;

drop index Index_login_name on zc_staff;

drop table if exists zc_staff;

drop table if exists zc_token_restore;

drop index Index_phone on zc_user;

drop index Index_uname on zc_user;

drop table if exists zc_user;

drop table if exists zc_user_invoice;

/*==============================================================*/
/* Table: zc_be_user_pay_record                                 */
/*==============================================================*/
create table zc_be_user_pay_record
(
   id                   int not null auto_increment comment '自增ID',
   uid                  int not null comment '用户ID',
   uname                varchar(32) comment '用户名',
   pay_amount           int not null comment '充值金额',
   opera_people         int not null comment '操作人',
   opera_name           varchar(32) comment '操作人名称',
   status               tinyint not null comment '状态 0 取消  1 成功',
   create_time          datetime comment '充值时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_be_user_pay_record comment '给用户充值记录';

/*==============================================================*/
/* Table: zc_bill_rule                                          */
/*==============================================================*/
create table zc_bill_rule
(
   rule_id              int not null auto_increment comment '自增ID',
   sid                  int not null comment '服务类别ID',
   lid                  int comment '车辆级别ID',
   city_id              int not null comment '城市ID',
   base_price           int not null comment '基础价格',
   km_price             int not null comment '公里单价',
   service_km           int comment '服务公里数(单位为公里)',
   time_price           int not null comment '时间单价',
   time                 int comment '时长',
   service_time         int comment '服务时长(单位为分钟)',
   night_service_charge int not null comment '夜间服务费',
   kongshi_fee          int not null comment '空驶费',
   descr                varchar(255) comment '描述',
   is_del               tinyint not null default 0 comment '是否删除',
   create_time          datetime comment '创建时间',
   primary key (rule_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_bill_rule comment '计费规则';

/*==============================================================*/
/* Table: zc_car_level                                          */
/*==============================================================*/
create table zc_car_level
(
   lid                  int not null auto_increment comment '车辆级别ID',
   name                 varchar(32) comment '名称',
   price                int not null comment '价格',
   descr                varchar(255) comment '描述',
   create_time          datetime comment '创建时间',
   primary key (lid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_car_level comment '车辆级别';

INSERT INTO `zc_car_level` VALUES (1,'经济型', 30,'经济型','2013-03-13 15:49:41'),
(2,'舒适型',50,'舒适型','2013-03-13 15:49:51'),
(3,'豪华型',80,'豪华型','2013-03-13 15:50:04');

/*==============================================================*/
/* Table: zc_car_model                                          */
/*==============================================================*/
create table zc_car_model
(
   car_id               int not null auto_increment comment '自增ID',
   lid                  int comment '车辆级别ID',
   parent_id            int not null comment '父ID',
   name                 varchar(32) comment '名称',
   descr                varchar(255) comment '描述',
   is_car_model         tinyint not null default 0 comment '是否为车型 0不是 1是',
   create_time          datetime comment '创建时间',
   primary key (car_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_car_model comment '车辆型号';

INSERT INTO `zc_car_model` VALUES (1,1,0,'大众','大众',0,'2013-03-13 15:50:22'),(2,1,0,'雪佛兰','雪佛兰',0,'2013-03-13 15:50:40'),(3,1,1,'捷达','捷达',1,'2013-03-13 15:50:53'),(4,1,2,'科鲁兹','科鲁兹',1,'2013-03-13 15:51:08');

/*==============================================================*/
/* Table: zc_card                                               */
/*==============================================================*/
create table zc_card
(
   id                   int not null auto_increment comment '自增ID',
   card_no              varchar(18) comment '卡号',
   model_id             int comment '模型ID',
   amount               int not null comment '卡金额',
   password             varchar(32) comment '卡密码',
   uid                  int not null comment '用户ID',
   uname                varchar(32) comment '用户名',
   end_time             datetime comment '截止时间',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_card comment '优惠卡';

/*==============================================================*/
/* Index: Index_card_no                                         */
/*==============================================================*/
create unique index Index_card_no on zc_card
(
   card_no
);

/*==============================================================*/
/* Table: zc_card_model                                         */
/*==============================================================*/
create table zc_card_model
(
   model_id             int not null auto_increment comment '模型ID',
   name                 varchar(64) comment '卡名称',
   amount               int not null default 0 comment '卡金额',
   num                  int default 0 comment '卡数量',
   descr                varchar(255) comment '描述',
   is_genera            tinyint not null default 0 comment '是否生成过',
   recent_num           int default 0 comment '领取数量',
   end_time             datetime comment '过期时间',
   create_time          datetime comment '创建时间',
   primary key (model_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_card_model comment '优惠卡模型';

/*==============================================================*/
/* Table: zc_chauffeur                                          */
/*==============================================================*/
create table zc_chauffeur
(
   chauffeur_id         int not null auto_increment comment '司机ID',
   cname                varchar(32) not null comment '用户名',
   password             varchar(32) comment '密码',
   realname             varchar(16) not null comment '真实姓名',
   sex                  tinyint not null comment '性别',
   phone                varchar(16) comment '手机号码',
   id_card              varchar(20) comment '身份证号码',
   city_id              int not null comment '城市ID',
   car_id               int comment '车型',
   color_id             int comment '颜色',
   car_no               varchar(12) comment '车牌号',
   status               tinyint not null default 1 comment '服务状态 0暂停服务 1正常服务',
   descr                varchar(255) comment '司机描述',
   is_del               tinyint not null default 0 comment '是否删除 0正常 1删除',
   create_time          datetime comment '创建时间',
   primary key (chauffeur_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_chauffeur comment '司机表';

INSERT INTO `zc_chauffeur` VALUES (1,'hjpking','4297f44b13955235245b2497399d7a93','大佛爷',1,'15101559314','431028198702113418',1,3,1,'京P8888',1,'10年驾龄',0,'2013-03-13 15:57:48'),(2,'tgfc','4297f44b13955235245b2497399d7a93','东佛祖',1,'15101559313','431028198702113418',2,4,2,'京P6666',1,'拜一下吧',0,'2013-03-13 15:58:57');

/*==============================================================*/
/* Index: index_cname                                           */
/*==============================================================*/
create unique index index_cname on zc_chauffeur
(
   cname
);

/*==============================================================*/
/* Index: Index_phone                                           */
/*==============================================================*/
create unique index Index_phone on zc_chauffeur
(
   phone
);

/*==============================================================*/
/* Table: zc_chauffeur_location                                 */
/*==============================================================*/
create table zc_chauffeur_location
(
   lid                  int not null auto_increment comment '位置ID',
   chauffeur_id         int not null comment '司机ID',
   city_id              int not null comment '城市ID',
   longitude            varchar(64) comment '经纬度',
   latitude             varchar(64) comment '纬度',
   update_time          datetime comment '更新时间',
   primary key (lid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_chauffeur_location comment '司机当前位置';

INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (1,1,1,'39.948437','2013-03-18 12:23:32','116.402893');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (2,2,1,'39.885899','2013-03-18 12:23:32','116.448383');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (3,3,1,'39.946463','2013-03-18 12:23:32','116.502113');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (4,4,1,'39.87984','2013-03-18 12:23:32','116.372509');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (5,5,1,'39.943436','2013-03-18 12:23:32','116.383324');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (6,6,1,'39.929484','2013-03-18 12:23:32','116.419373');
INSERT INTO `zc_chauffeur_location` (`lid`,`chauffeur_id`,`city_id`,`longitude`,`update_time`,`latitude`) VALUES (7,7,1,'39.890246','2013-03-18 12:23:32','116.401005');

/*==============================================================*/
/* Table: zc_chauffeur_to_user_pay_log                          */
/*==============================================================*/
create table zc_chauffeur_to_user_pay_log
(
   id                   int not null auto_increment comment '自增ID',
   chauffeur_id         int comment '司机ID',
   chauffeur_name       varchar(64) comment '司机名',
   chauffeur_phone      varchar(16) comment '司机手机号',
   uid                  int not null comment '用户ID',
   uname                varchar(32) comment '用户名',
   user_phone           varchar(16) comment '用户手机号',
   amount               int not null comment '金额',
   descr                varchar(255) comment '描述',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_chauffeur_to_user_pay_log comment '司机给用户充值记录';

/*==============================================================*/
/* Table: zc_chauffeur_verify                                   */
/*==============================================================*/
create table zc_chauffeur_verify
(
   id                   int not null auto_increment comment '自增ID',
   phone                varchar(16) comment '手机号码',
   verify_code          varchar(6) comment '验证码',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_chauffeur_verify comment '司机登陆手机验证码';

/*==============================================================*/
/* Index: Index_phone                                           */
/*==============================================================*/
create unique index Index_phone on zc_chauffeur_verify
(
   phone
);

/*==============================================================*/
/* Table: zc_city                                               */
/*==============================================================*/
create table zc_city
(
   city_id              int not null auto_increment comment '城市ID',
   city_name            varchar(32) comment '城市名称',
   parent_id            int comment '所属省份',
   city_code            varchar(32) comment '城市代码',
   is_del               tinyint not null default 0 comment '是否删除 0正常 1删除',
   is_city              tinyint not null comment '是否为城市 0不是城市 1是城市',
   descr                varchar(255) comment '描述',
   create_time          datetime comment '创建时间',
   primary key (city_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_city comment '城市表';

INSERT INTO `zc_city` VALUES (1,'北京市',0,'bj',0,1,'北京市','2013-03-13 15:42:20'),(2,'上海市',0,'sh',0,1,'上海市','2013-03-13 15:43:31'),(3,'广州市',0,'gz',0,1,'广州市','2013-03-13 15:43:46'),(4,'深圳市',0,'sz',0,1,'深圳市','2013-03-13 15:44:06'),(5,'湖南省',0,'hn',0,0,'湖南省','2013-03-13 15:44:33'),(6,'长沙市',5,'cs',0,1,'长沙市','2013-03-13 15:44:49'),(7,'郴州市',5,'cz',0,1,'郴州市','2013-03-13 15:45:12'),(8,'11',0,'11',1,1,'11','2013-03-13 15:45:24');

/*==============================================================*/
/* Table: zc_city_airport                                       */
/*==============================================================*/
create table zc_city_airport
(
   id                   int not null auto_increment comment '自增ID',
   city_id              int comment '城市ID',
   airport_name         varchar(64) comment '机场名',
   longitude            varchar(64) comment '经度',
   latitude             varchar(64) comment '纬度',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_city_airport comment '城市机场';

INSERT INTO `zc_city_airport` (`id`,`city_id`,`airport_name`,`longitude`,`latitude`) VALUES (1,1,'北京首都机场1号航站楼','40.079122','116.59265');
INSERT INTO `zc_city_airport` (`id`,`city_id`,`airport_name`,`longitude`,`latitude`) VALUES (2,1,'北京首都机场2号航站楼','40.078597','116.590333');
INSERT INTO `zc_city_airport` (`id`,`city_id`,`airport_name`,`longitude`,`latitude`) VALUES (3,1,'北京首都机场3号航站楼','40.078334','116.593595');

/*==============================================================*/
/* Table: zc_city_useful_addresse                               */
/*==============================================================*/
create table zc_city_useful_addresse
(
   ua_id                int not null auto_increment comment '常用地址ID',
   city_id              int not null comment '城市ID',
   name                 varchar(32) comment '名称',
   descr                varchar(255) comment '描述',
   longitude            varchar(64) comment '经度',
   latitude             varchar(64) comment '纬度',
   create_time          datetime comment '创建时间',
   primary key (ua_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_city_useful_addresse comment '城市常用下车地址';

/*==============================================================*/
/* Table: zc_competence                                         */
/*==============================================================*/
create table zc_competence
(
   competence_id        int not null auto_increment comment '权限ID',
   name                 varchar(32) comment '权限名称',
   descr                varchar(64) comment '权限描述',
   primary key (competence_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_competence comment '权限表';

/*==============================================================*/
/* Table: zc_competence_correspond                              */
/*==============================================================*/
create table zc_competence_correspond
(
   id                   int not null auto_increment comment '自增ID',
   staff_id             int comment '员工ID',
   competence_id        int comment '权限ID',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_competence_correspond comment '权限对应表';

INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (1,2,1,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (2,2,2,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (3,2,3,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (4,2,4,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (5,2,5,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (6,2,6,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (7,2,7,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (8,2,8,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (9,2,9,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (10,2,10,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (11,2,11,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (12,2,12,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (13,2,13,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (14,2,14,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (15,2,15,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (16,2,16,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (17,2,17,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (18,2,18,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (19,2,19,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (20,2,20,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (21,2,21,'2013-03-13 16:10:59');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (63,1,62,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (62,1,61,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (61,1,60,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (60,1,6,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (59,1,50,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (58,1,5,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (57,1,43,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (56,1,42,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (55,1,41,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (54,1,40,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (53,1,4,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (52,1,31,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (51,1,30,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (50,1,3,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (49,1,21,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (48,1,20,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (47,1,2,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (46,1,12,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (45,1,11,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (44,1,10,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (43,1,1,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (64,1,63,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (65,1,7,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (66,1,70,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (67,1,71,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (68,1,72,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (69,1,8,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (70,1,80,'2013-03-15 12:37:50');
INSERT INTO `zc_competence_correspond` (`id`,`staff_id`,`competence_id`,`create_time`) VALUES (71,1,81,'2013-03-15 12:37:50');

/*==============================================================*/
/* Index: Index_staff_id_competence_id                          */
/*==============================================================*/
create unique index Index_staff_id_competence_id on zc_competence_correspond
(
   staff_id,
   competence_id
);

/*==============================================================*/
/* Table: zc_department                                         */
/*==============================================================*/
create table zc_department
(
   depart_id            int not null auto_increment comment '部门ID',
   parent_id            int comment '上级部门',
   name                 varchar(32) comment '部门名称',
   descr                varchar(255) comment '部门描述',
   is_del               tinyint not null default 0 comment '是否删除 0 正常 1删除',
   create_time          datetime comment '创建时间',
   primary key (depart_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_department comment '部门表';

INSERT INTO `zc_department` VALUES (1,0,'业务部','业务部',0,'2013-03-13 16:03:52'),(2,0,'财务部','财务部',0,'2013-03-13 16:04:01'),(3,0,'IT部','IT部',0,'2013-03-13 16:04:17'),(4,3,'美工团队','美工团队',0,'2013-03-13 16:04:43'),(5,3,'技术团队','技术团队',0,'2013-03-13 16:04:53');

/*==============================================================*/
/* Table: zc_feedback                                           */
/*==============================================================*/
create table zc_feedback
(
   id                   int not null auto_increment comment '自增ID',
   order_sn             int comment '订单ID',
   category_id          int comment '分类ID',
   user_type            tinyint comment '用户类型',
   uid                  int not null comment '用户ID',
   uname                varchar(32) comment '用户名称',
   phone                varchar(16) comment '手机号码',
   descr                varchar(255) comment '描述',
   process_status       tinyint not null default 0 comment '处理状态 0未处理 1已处理',
   process_result       varchar(128) comment '处理结果',
   create_time          datetime comment '提交时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_feedback comment '投诉建议';

/*==============================================================*/
/* Table: zc_message                                            */
/*==============================================================*/
create table zc_message
(
   mid                  int not null auto_increment comment '消息ID',
   cid                  int not null comment '分类ID',
   title                varchar(32) comment '消息标题',
   content              varchar(255) comment '消息内容',
   staff_id             int not null comment '员工ID',
   author               varchar(32) not null comment '消息作者',
   is_del               tinyint not null comment '是否删除 0正常 1删除',
   create_time          datetime comment '创建时间',
   primary key (mid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_message comment '消息表';

INSERT INTO `zc_message` VALUES (1,3,'天降大雨于北京城是也','天降大雨于北京城是也',1,'admin', '0','2013-03-13 16:14:52');

/*==============================================================*/
/* Table: zc_message_category                                   */
/*==============================================================*/
create table zc_message_category
(
   cid                  int not null auto_increment comment '分类ID',
   parent_id            int not null comment '父ID',
   name                 varchar(32) comment '分类名称',
   descr                varchar(255) comment '分类描述',
   create_time          datetime comment '创建时间',
   primary key (cid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_message_category comment '消息分类表';

INSERT INTO `zc_message_category` VALUES (1,0,'天气','天气','2013-03-13 16:13:51'),(2,0,'产品更新','产品更新','2013-03-13 16:14:09'),(3,1,'北京天气','北京天气','2013-03-13 16:14:40');

/*==============================================================*/
/* Table: zc_message_send_record                                */
/*==============================================================*/
create table zc_message_send_record
(
   id                   int not null auto_increment comment '自增ID',
   mid                  int not null comment '消息ID',
   title                varchar(32) comment '消息名字',
   content              varchar(255) comment '消息内容',
   staff_id             int not null comment '发送人',
   recipient_id         int comment '接收人ID',
   recipient            varchar(255) comment '接收人',
   user_type            tinyint comment '用户类别',
   types                tinyint not null comment '消息分类 0 系统推送消息，1手机短信',
   create_time          datetime comment '发送时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_message_send_record comment '消息发送记录';

/*==============================================================*/
/* Table: zc_order                                              */
/*==============================================================*/
create table zc_order
(
   order_sn             int not null auto_increment comment '订单ID',
   city_id              int not null comment '城市ID',
   sid                  int comment '服务类型',
   lid                  int comment '车辆级别',
   uid                  int comment '用户ID',
   uname                varchar(32) comment '用户姓名',
   user_phone           varchar(16) comment '用户手机',
   user_sex             tinyint not null comment '用户性别',
   chauffeur_id         int comment '司机ID',
   chauffeur_login_name varchar(32) comment '司机用户名',
   chauffeur_phone      varchar(16) comment '司机手机',
   amount               int not null default 0 comment '总金额',
   high_speed_charge    int not null default 0 comment '高速费',
   park_charge          int not null default 0 comment '停车费',
   air_service_charge   int not null default 0 comment '机场服务费',
   dispatch_charge      int not null default 0 comment '调度费',
   mileage              int default 0 comment '行驶里程',
   travel_time          int default 0 comment '行驶时间',
   exceed_time          int default 0 comment '超出时间',
   exceed_time_fee      int not null default 0 comment '超出时间费用',
   exceed_km            int default 0 comment '超出公里',
   exceed_km_fee        int not null default 0 comment '超出公里费用',
   base_price           int not null default 0 comment '基础价格',
   km_price             int not null default 0 comment '公里单价',
   service_km           int default 0 comment '服务公里数(单位为公里)',
   time_price           int not null default 0 comment '时间单价',
   time                 int default 0 comment '时长',
   service_time         int default 0 comment '服务时长(单位为分钟)',
   night_service_charge int not null default 0 comment '夜间服务费',
   kongshi_fee          int not null default 0 comment '空驶费',
   status               tinyint not null default 0 comment '订单状态 0初始 1成功 2 取消 3 司机已接单 4 服务开始  5 服务结束 6 车辆已出发  7  车辆已到，等待上车
            ',
   car_time             datetime comment '用车时间',
   car_length           int comment '用车时长',
   train_address        varchar(64) comment '上车地点',
   train_address_desc   varchar(64) comment '上车地址描述',
   address_supplemental varchar(255) comment '上车地址补充',
   getoff_address       varchar(64) comment '下车地点',
   getoff_address_desc  varchar(64) comment '下车地址描述',
   train_time           datetime comment '上车时间',
   getoff_time          datetime comment '下车时间',
   create_time          datetime comment '订车时间',
   is_invoice           tinyint not null comment '是否需要发票 0不需要 ，1需要 ',
   payable              varchar(64) comment '发票抬头',
   content              varchar(255) comment '发票内容',
   mailing_address      varchar(255) comment '发票寄送地址',
   leave_message        varchar(255) comment '留言',
   notice               varchar(255) comment '备注',
   pay_password         varchar(32) comment '订单充值密码',
   arrival_time         datetime comment '到达时间',
   pay_status           tinyint not null default 0 comment '支付状态,0初始，1成功',
   primary key (order_sn)
)
engine = MYISAM
auto_increment = 10000000;

alter table zc_order comment '订单表';

/*==============================================================*/
/* Table: zc_order_run_path                                     */
/*==============================================================*/
create table zc_order_run_path
(
   id                   int not null auto_increment comment '自增ID',
   order_sn             int comment '订单ID',
   chauffeur_id         int comment '司机ID',
   longitude            varchar(64) comment '经度',
   latitude             varchar(64) comment '纬度',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_order_run_path comment '订单运行路径';

/*==============================================================*/
/* Table: zc_pay_record                                         */
/*==============================================================*/
create table zc_pay_record
(
   pay_id               int not null auto_increment comment '充值ID',
   uid                  int comment '用户ID',
   uname                varchar(32) comment '用户名',
   pay_amount           int not null comment '充值金额 单位：分',
   pay_status           tinyint not null comment '充值状态 0 初始，1成功 2 充值失败 3 签名错误 ',
   source               tinyint comment '充值来源 0 客户端 1 其他 ',
   pay_type             tinyint comment '充值方式',
   pay_channel          varchar(32) comment '充值渠道 1支付宝，2 银联',
   opera_people         varchar(16) comment '操作人',
   is_post              tinyint not null default 0 comment '是否寄送发票 0不需要 1 需要',
   post_mode            tinyint comment '寄送方式 1 快递，2 平邮',
   invoice              varchar(64) comment '发票抬头',
   content              varchar(64) comment '发票内容',
   post_address         varchar(128) comment '邮寄地址',
   post_status          tinyint not null default 0 comment '寄送状态 0未寄 1已寄',
   create_time          datetime comment '充值时间',
   primary key (pay_id)
)
engine = MYISAM
auto_increment = 10000000;

alter table zc_pay_record comment '充值记录';

/*==============================================================*/
/* Table: zc_service_type                                       */
/*==============================================================*/
create table zc_service_type
(
   sid                  int not null auto_increment comment '自增ID',
   name                 varchar(32) comment '服务名称',
   descr                varchar(255) comment '服务描述',
   create_time          datetime comment '创建时间',
   primary key (sid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_service_type comment '服务类型';

INSERT INTO `zc_service_type` VALUES (1,'接机服务','接机服务','2013-03-13 15:46:17'),(2,'送机服务','送机服务','2013-03-13 15:46:27'),(3,'随叫随到','随叫随到','2013-03-13 15:46:37'),(5,'时租','时租','2013-03-13 15:49:33');

/*==============================================================*/
/* Table: zc_staff                                              */
/*==============================================================*/
create table zc_staff
(
   staff_id             int not null auto_increment comment '员工ID',
   login_name           varchar(32) not null comment '登陆名称',
   realname             varchar(16) not null comment '真实姓名',
   password             varchar(32) comment '密码',
   phone                varchar(16) comment '手机号码',
   email                varchar(32) not null comment '邮箱',
   sex                  tinyint not null comment '性别',
   depart_id            int not null comment '部门ID',
   id_card              varchar(20) comment '身份证号码',
   descr                varchar(255) comment '描述',
   is_del               tinyint not null default 0 comment '是否删除 0正常 1删除',
   create_time          datetime comment '创建时间',
   primary key (staff_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_staff comment '员工表';

INSERT INTO `zc_staff` VALUES (1,'admin','超级管理员','e10adc3949ba59abbe56e057f20f883e','13000000000','admin@admin.com',1,1,'1','超级管理员',0,'2013-03-13 15:38:33'),(2,'hjpking','侯积平','25f2261cd6924d96df4bf75227f3d5fa','15101559313','hjpking@gmail.com',0,5,'431028198702113418','侯积平',0,'2013-03-13 16:10:59');

/*==============================================================*/
/* Index: Index_login_name                                      */
/*==============================================================*/
create unique index Index_login_name on zc_staff
(
   login_name
);

/*==============================================================*/
/* Table: zc_token_restore                                      */
/*==============================================================*/
create table zc_token_restore
(
   id                   int not null auto_increment comment '自增ID',
   token_key            varchar(32) comment 'token key',
   token                varchar(255) comment 'token',
   create_time          datetime comment '创建时间',
   primary key (id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_token_restore comment 'token存储';

/*==============================================================*/
/* Table: zc_user                                               */
/*==============================================================*/
create table zc_user
(
   uid                  int not null auto_increment comment '用户ID',
   uname                varchar(32) not null comment '用户名',
   password             varchar(32) comment '用户密码',
   realname             varchar(16) not null comment '真实姓名',
   amount               int not null default 0 comment '金额',
   sex                  tinyint not null default 0 comment '性别 0男 1女',
   phone                varchar(16) comment '手机号码',
   binding_type         varchar(8) default '1' comment '绑定类型 1支付宝 2 银行卡',
   card_no              varchar(32) comment '卡账号',
   status               tinyint not null comment '用户状态 0黑名单 1白名单',
   descr                varchar(255) comment '用户描述',
   is_del               char(10) default '0' comment '是否删除0正常 1已删除',
   create_time          datetime comment '创建时间',
   primary key (uid)
)
engine = MYISAM
auto_increment = 1;

alter table zc_user comment '用户表';

INSERT INTO `zc_user` VALUES (1,'hjpking','4297f44b13955235245b2497399d7a93','花心油',0,1,'15101559313',1, '622262226222622266',1,'花心油','0','2013-03-13 16:03:09'),
(2,'tgfc','4297f44b13955235245b2497399d7a93','滑滑',0,2,'15101559314',2, '622262226222622266',1,'滑滑','0','2013-03-13 16:03:37');

/*==============================================================*/
/* Index: Index_uname                                           */
/*==============================================================*/
create unique index Index_uname on zc_user
(
   uname
);

/*==============================================================*/
/* Index: Index_phone                                           */
/*==============================================================*/
create unique index Index_phone on zc_user
(
   phone
);

/*==============================================================*/
/* Table: zc_user_invoice                                       */
/*==============================================================*/
create table zc_user_invoice
(
   invoice_id           int not null auto_increment comment '发票ID',
   uid                  int not null comment '用户ID',
   uname                varchar(32) comment '用户名',
   payable              varchar(64) comment '发票抬头',
   content              varchar(255) comment '发票内容',
   create_time          datetime comment '创建时间',
   primary key (invoice_id)
)
engine = MYISAM
auto_increment = 1;

alter table zc_user_invoice comment '用户发票';

