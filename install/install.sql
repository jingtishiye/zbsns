DROP TABLE IF EXISTS `5isns_plugins`;
CREATE TABLE `5isns_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '插件文件名',
  `cnname` varchar(100) NOT NULL DEFAULT '' COMMENT '插件中文名称',
  `author` varchar(100) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(100) NOT NULL DEFAULT '' COMMENT '版本',   
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text DEFAULT NULL COMMENT '配置数据',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';


DROP TABLE IF EXISTS `5isns_tixian`;
CREATE TABLE `5isns_tixian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `score` int(11) NOT NULL default '0',   # 提现积分
  `rmb` varchar(100) NOT NULL DEFAULT '' COMMENT '金额',
  `account` varchar(100) NOT NULL DEFAULT '' COMMENT '账号',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1支付宝',    
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '通过时间',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现表';

DROP TABLE IF EXISTS `5isns_file`;
CREATE TABLE `5isns_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '我的附件，便于清理附件',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '我的帖子id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1帖子2文档3话题4会员组5认证材料6空7评论8文章轮播图9文档轮播图10文档封面',  
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` varchar(100) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` varchar(255) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(10) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(50) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(15) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `width` mediumint(8) unsigned NOT NULL default '0', # width > 0 则为图片
  `height` mediumint(8) unsigned NOT NULL default '0',  # height
  `comment` char(100) NOT NULL default '',    # 文件注释 方便于搜索
  `score` int(11) NOT NULL default '0',   # 金币
  `downloads` int(11) NOT NULL default '0',   # 下载次数，预留
  `isimage` tinyint(1) NOT NULL default '0',    # 是否为图片
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';


DROP TABLE IF EXISTS `5isns_user`;
CREATE TABLE IF NOT EXISTS `5isns_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userip` varchar(32) NOT NULL COMMENT 'IP',
  `nickname` char(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `username` varchar(32) NOT NULL COMMENT '名称',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `avatar` int(11) DEFAULT '0' COMMENT '头像',
  `usermail` varchar(32) NOT NULL COMMENT '邮箱',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机',
  `regtime` varchar(32) NOT NULL COMMENT '注册时间',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '正常',
  `rz` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证,什么认证类型',
  `statusdes` varchar(200) DEFAULT NULL COMMENT '认证描述',
  `userhome` varchar(32) DEFAULT NULL COMMENT '家乡',
  `description` varchar(200) DEFAULT NULL COMMENT '描述',
  `last_login_time` varchar(20) DEFAULT '0' COMMENT '最后登陆时间',
  `last_login_ip` varchar(50) DEFAULT '' COMMENT '最后登录IP',
  `salt` varchar(20) DEFAULT NULL COMMENT 'salt',
  `logins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登錄次數',
  `leader_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '上级会员ID',
  `is_inside` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为后台使用者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `usermail` (`usermail`) USING BTREE
)ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `5isns_user` (id, nickname, username,password,usermail,salt,is_inside) VALUES (1, 'admin', 'admin', '0dfc7612f607db6c17fd99388e9e5f9c', 'admin@admin.com','1dFlxLhiuLqnUZe9kA',1);

DROP TABLE IF EXISTS `5isns_user_extend`;
CREATE TABLE IF NOT EXISTS `5isns_user_extend` (
  `uid` int(11) NOT NULL COMMENT '会员',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `expoint1` int(11) DEFAULT '0' COMMENT '扩展积分1',
  `expoint2` int(11) DEFAULT '0' COMMENT '扩展积分2',
  `expoint3` int(11) DEFAULT '0' COMMENT '扩展积分3',
  `doc_num` int(11) DEFAULT '0' COMMENT '文档数量',
  `topic_num` int(11) DEFAULT '0' COMMENT '帖子数量',
  `fensi_num` int(11) DEFAULT '0' COMMENT '粉丝数量',
  `cate_num` int(11) DEFAULT '0' COMMENT '我的话题数量',
  `focus_mydoc_num` int(11) DEFAULT '0' COMMENT '被收藏文档数量',
  `focus_mytopic_num` int(11) DEFAULT '0' COMMENT '被收藏帖子数量',
  `focus_mycate_num` int(11) DEFAULT '0' COMMENT '被关注话题数量',
  `focus_user_num` int(11) DEFAULT '0' COMMENT '关注用户数量',
  `focus_doc_num` int(11) DEFAULT '0' COMMENT '收藏文档数量',
  `focus_topic_num` int(11) DEFAULT '0' COMMENT '收藏帖子数量',
  `focus_cate_num` int(11) DEFAULT '0' COMMENT '关注话题数量',
  `grades` tinyint(1) NOT NULL DEFAULT '0' COMMENT '购买等级',
  `grades_days` int(11) DEFAULT '-1' COMMENT '天数',
  `grades_nums` int(11) DEFAULT '-1' COMMENT '次数',
  `grades_bili` int(11) NOT NULL DEFAULT '0' COMMENT '比例涉及付费内容|下载附件|下载文档',
  `grades_limittime` varchar(255) NOT NULL DEFAULT '0' COMMENT '时间限制',
  `grades_type` varchar(255) NOT NULL DEFAULT '0' COMMENT '权限',  
  `grades_name` varchar(100) DEFAULT NULL COMMENT '购买等级名称',
  `grades_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买更新时间',
  `up_grades` tinyint(1) NOT NULL DEFAULT '0' COMMENT '升级等级',
  `up_grades_bili` int(11) NOT NULL DEFAULT '0' COMMENT '比例涉及付费内容|下载附件|下载文档',
  `up_grades_limittime` varchar(255) NOT NULL DEFAULT '0' COMMENT '时间限制',
  `up_grades_type` varchar(255) NOT NULL DEFAULT '0' COMMENT '权限',  
  `up_grades_name` varchar(100) DEFAULT NULL COMMENT '升级等级名称',
  `up_grades_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '升级更新时间',
  `keywords` varchar(200) DEFAULT NULL  COMMENT '关键词',
  `notify_set` text DEFAULT NULL COMMENT '请求数据',
  `mailstatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '邮箱是否认证',
  `mobilestatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '手机是否认证',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1通过',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`uid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户附加信息表';
INSERT INTO `5isns_user_extend` (uid) VALUES (1);
DROP TABLE IF EXISTS `5isns_rzuser`;
CREATE TABLE IF NOT EXISTS `5isns_rzuser` (
  `uid` int(11) NOT NULL COMMENT '会员',
  `mobile` varchar(200) DEFAULT NULL COMMENT '联系方式',
  `name` varchar(200) DEFAULT NULL COMMENT '真实姓名',
  `statusdes` varchar(200) DEFAULT NULL COMMENT '认证描述',
  `idcard` varchar(32) NOT NULL COMMENT '身份证或机构代码证',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '证件照片',
  `keywords` varchar(200) DEFAULT NULL  COMMENT '关键词',
  `rzdescrib` varchar(200) DEFAULT NULL  COMMENT '关键词',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1个人2企业',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1通过',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`uid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';

DROP TABLE IF EXISTS `5isns_usergrade`;
CREATE TABLE IF NOT EXISTS `5isns_usergrade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '名称',
  `score` int(11) NOT NULL COMMENT '等级所需积分',
  `gmtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1购买2升级',
  `type` varchar(32) NOT NULL DEFAULT '0' COMMENT '1发帖2发文档3回帖4查看付费内容5下载附件6下载文档',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '天数涉及查看付费内容|下载附件|下载文档',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '次数涉及下载附件|下载文档',
  `bili` int(11) NOT NULL DEFAULT '0' COMMENT '比例涉及付费内容|下载附件|下载文档',
  `limittime` varchar(255) NOT NULL DEFAULT '0' COMMENT '时间涉及发帖|发文档|回帖|下载',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '等级图标id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员等级表';
INSERT INTO `5isns_usergrade` VALUES ('2', '普通会员', '0', '2', '1,2,3,4', '0', '0', '100', '0,0,0,0', '0', '1', '1556592145', '0');


DROP TABLE IF EXISTS `5isns_chongzhi`;
CREATE TABLE IF NOT EXISTS `5isns_chongzhi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `score` int(10) NOT NULL,
  `rmb` int(10) NOT NULL,
  `errorcode` varchar(200) DEFAULT NULL COMMENT '错误代码',
  `trade_no` varchar(200) DEFAULT NULL COMMENT '单号',
  `out_trade_no` varchar(200) DEFAULT NULL COMMENT '商户单号',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1支付宝',
  `actiontype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1充值2打赏',
  `itemid` int(11) NOT NULL DEFAULT '0' COMMENT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1成功',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户充值表';

DROP TABLE IF EXISTS `5isns_raty_user`;
CREATE TABLE `5isns_raty_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `score` int(10) NOT NULL,
  `itemid` int(11) NOT NULL COMMENT '文档id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    
DROP TABLE IF EXISTS `5isns_menu`;
CREATE TABLE `5isns_menu` (
  `keyname` char(20) NOT NULL COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `controller` char(255) NOT NULL DEFAULT '' COMMENT 'controller',
  `action` char(255) NOT NULL DEFAULT '' COMMENT 'action',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of 5isns_menu
-- ----------------------------

INSERT INTO `5isns_menu` VALUES ('system', '系统管理', '0', '10', 'system', '', '', '0', 'fa-wrench', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('users', '用户管理', '0', '9', 'users', '', '', '0', 'fa-group', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('expand', '扩展管理', '0', '7', 'expand', '', '', '0', 'fa-linode', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('topics', '帖子管理', '0', '7', 'topics', '', '', '0', 'fa-file-text-o', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('docs', '文档管理', '0', '7', 'docs', '', '', '0', 'fa-file-word-o', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('records', '记录管理', '0', '6', 'records', '', '', '0', 'fa-bar-chart', '1', '1491578008', '9');
INSERT INTO `5isns_menu` VALUES ('front', '前台设置', '0', '8', 'front', '', '', '0', 'fa-laptop', '1', '1491578008', '9');

INSERT INTO `5isns_menu` VALUES ('topiccatelist', '话题列表', '1', '5', 'front', 'topiccate', 'list', '0', 'fa-th-large', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('navlist', '前台导航', '1', '4', 'front', 'nav', 'list', '0', 'fa-hand-o-right', '1', '1491668183', '0');

INSERT INTO `5isns_menu` VALUES ('topiclist', '文章列表', '1', '5', 'topics', 'topic', 'list', '0', 'fa-file-o', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('topic_commentlist', '文章评论', '1', '5', 'topics', 'topic_comment', 'list', '0', 'fa-comments', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('topicclasslist', '文章分类', '1', '5', 'topics', 'topicclass', 'list', '0', 'fa-comments', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('topicsliderlist', '文章轮播图', '1', '5', 'topics', 'topicslider', 'list', '0', 'fa-comments', '1', '1491318724', '0');


INSERT INTO `5isns_menu` VALUES ('doclist', '文档列表', '1', '5', 'docs', 'doc', 'list', '0', 'fa-file-o', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('doc_commentlist', '文档评论', '1', '5', 'docs', 'doc_comment', 'list', '0', 'fa-comments', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('docclasslist', '文档分类', '1', '5', 'docs', 'docclass', 'list', '0', 'fa-comments', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('docsliderlist', '文档轮播图', '1', '5', 'docs', 'docslider', 'list', '0', 'fa-comments', '1', '1491318724', '0');


INSERT INTO `5isns_menu` VALUES ('configsetting', '系统配置', '1', '6', 'system', 'config', 'setting', '0', 'fa-cogs', '1', '1491668183', '0');
INSERT INTO `5isns_menu` VALUES ('syscache', '清理缓存', '1', '0', 'system', 'sys', 'cache', '0', 'fa-sign-language', '1', '1491668183', '0');
INSERT INTO `5isns_menu` VALUES ('trashlist', '回收站', '1', '2', 'system', 'trash', 'list', '0', 'fa-recycle', '1', '1492320214', '1492311462');
INSERT INTO `5isns_menu` VALUES ('databaselist', '备份数据', '1', '1', 'system', 'database', 'list', '0', 'fa-inbox', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('databaseimportlist', '还原数据', '1', '0', 'system', 'database', 'importlist', '0', 'fa-undo', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('pointrulelist', '积分规则', '1', '5', 'system', 'pointrule', 'list', '0', 'fa-align-justify', '1', '1491318724', '0');


INSERT INTO `5isns_menu` VALUES ('userlist', '会员列表', '1', '3', 'users', 'user', 'list', '0', 'fa-user', '1', '1491837214', '0');
INSERT INTO `5isns_menu` VALUES ('usergradelist', '会员等级', '1', '2', 'users', 'usergrade', 'list', '0', 'fa-signal', '1', '1491837214', '0');
INSERT INTO `5isns_menu` VALUES ('rzuserlist', '会员审核', '1', '1', 'users', 'rzuser', 'list', '0', 'fa-id-card', '1', '1492000451', '0');

INSERT INTO `5isns_menu` VALUES ('pointnotelist', '积分记录', '1', '3', 'records', 'pointnote', 'list', '0', 'fa-database', '1', '1491837214', '0');
INSERT INTO `5isns_menu` VALUES ('messagelist', '消息记录', '1', '2', 'records', 'message', 'list', '0', 'fa-volume-up', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('jubaolist', '举报记录', '1', '2', 'records', 'jubao', 'list', '0', 'fa-volume-up', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('tixianlist', '提现记录', '1', '3', 'records', 'tixian', 'list', '0', 'fa-database', '1', '1491837214', '0');
INSERT INTO `5isns_menu` VALUES ('chongzhilist', '现金消费记录', '1', '3', 'records', 'chongzhi', 'list', '0', 'fa-database', '1', '1491837214', '0');


INSERT INTO `5isns_menu` VALUES ('pluginslist', '插件列表', '1', '5', 'expand', 'plugins', 'list', '0', 'fa-file-o', '1', '1491318724', '0');
INSERT INTO `5isns_menu` VALUES ('moduleslist', '模块列表', '1', '5', 'expand', 'modules', 'list', '0', 'fa-file-o', '1', '1491318724', '0');


DROP TABLE IF EXISTS `5isns_nav`;
CREATE TABLE `5isns_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` tinyint(3) unsigned NOT NULL COMMENT '顶部还是底部',
  `name` varchar(20) NOT NULL COMMENT '导航名称',
  `alias` varchar(20) DEFAULT '' COMMENT '导航别称',
  `link` varchar(255) DEFAULT '' COMMENT '导航链接',
  `icon` varchar(255) DEFAULT '' COMMENT '导航图标',
  `target` varchar(10) DEFAULT '' COMMENT '打开方式',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';
INSERT INTO `5isns_nav` (id, pid, name,alias,link,target) VALUES (1, 1, '文章', 'topics', 'index,topics', '_self');
INSERT INTO `5isns_nav` (id, pid, name,alias,link,target) VALUES (2, 1, '文档', 'docs', 'index,docs', '_self');
INSERT INTO `5isns_nav` (id, pid, name,alias,link,target) VALUES (3, 1, '话题', 'tags', 'tags-taglist,index', '_self');




DROP TABLE IF EXISTS `5isns_point_rule`;
CREATE TABLE IF NOT EXISTS `5isns_point_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则ID',
  `controller` varchar(30) NOT NULL DEFAULT '' COMMENT '规则名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为增加2为减少',
  `score` varchar(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `num` varchar(11) NOT NULL DEFAULT '0' COMMENT '二十四小时奖赏次数',
  `tasknum` varchar(11) NOT NULL DEFAULT '0' COMMENT '奖赏次数',
  `scoretype` varchar(30) NOT NULL DEFAULT '' COMMENT '积分类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='积分规则表';

DROP TABLE IF EXISTS `5isns_point_note`;
CREATE TABLE `5isns_point_note` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  
  `uid` int(10) unsigned NOT NULL,
  `inctype` char(1) NOT NULL DEFAULT '+',
  `score` int(10) NOT NULL,
  `itemid` varchar(11) NOT NULL DEFAULT '0' COMMENT '表示帖子或者其他各种类型的主键id',
  `to_uid` varchar(11) NOT NULL DEFAULT '0' COMMENT '规则id或者受益人uid',
  `scoretype` varchar(30) NOT NULL DEFAULT '' COMMENT '积分类型',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1下载附件2下载文档3付费阅读4充值5购买会员6打赏7提现8积分规则',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `description` varchar(200) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
)ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `5isns_doccon`;
CREATE TABLE IF NOT EXISTS `5isns_doccon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面id',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `choice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '精品',
  `settop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '顶置',
  `praise` int(11) NOT NULL DEFAULT '0' COMMENT '赞',
  `view` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `sc` int(11) NOT NULL DEFAULT '0' COMMENT '收藏量',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `down` int(11) NOT NULL DEFAULT '0' COMMENT '下载量',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `reply` int(11) NOT NULL DEFAULT '0' COMMENT '回复',
  `keywords` varchar(100) NOT NULL COMMENT '关键词',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `fileid` varchar(11) NOT NULL DEFAULT '0' COMMENT '文件id',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `pageid` int(11) NOT NULL DEFAULT '0' COMMENT '页数',
  `showpage` int(11) NOT NULL DEFAULT '2' COMMENT '默认显示页数',
  `raty` varchar(11) NOT NULL DEFAULT '0' COMMENT '评分',
  `ctype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '转换类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `5isns_docclass`;
CREATE TABLE IF NOT EXISTS `5isns_docclass` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `gradeid` varchar(255) NOT NULL DEFAULT '' COMMENT '限制发布的用户组',
  `choice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文档分类表';

DROP TABLE IF EXISTS `5isns_docslider`;
CREATE TABLE IF NOT EXISTS `5isns_docslider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1文档链接2外链广告',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '文章的ID或者外链',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文档轮播表';

DROP TABLE IF EXISTS `5isns_topicslider`;
CREATE TABLE IF NOT EXISTS `5isns_topicslider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1文章链接2外链广告',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '文章的ID或者外链',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章轮播表';

DROP TABLE IF EXISTS `5isns_topicclass`;
CREATE TABLE IF NOT EXISTS `5isns_topicclass` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `gradeid` varchar(255) NOT NULL DEFAULT '' COMMENT '限制发布的用户组',
  `choice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='话题表';

DROP TABLE IF EXISTS `5isns_topiccate`;
CREATE TABLE IF NOT EXISTS `5isns_topiccate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '话题ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '话题名称',
  `gradeid` varchar(255) NOT NULL DEFAULT '' COMMENT '限制发布的用户组',
  `choice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐话题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '话题描述',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '话题图片id',
  `characters` varchar(10) NOT NULL COMMENT '所属字母',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `doc_num` int(11) NOT NULL DEFAULT '0' COMMENT '文档数量',
  `topic_num` int(11) NOT NULL DEFAULT '0' COMMENT '帖子数量',
  `user_num` int(11) NOT NULL DEFAULT '0' COMMENT '关注数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='话题表';


DROP TABLE IF EXISTS `5isns_topic`;
CREATE TABLE IF NOT EXISTS `5isns_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1单页2帖子3视频',
  `free` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0免费1付费2部分收费',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '付费积分',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `choice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '精贴',
  `settop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '顶置',
  `sc` int(11) NOT NULL DEFAULT '0' COMMENT '收藏',
  `view` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `img_num` int(11) NOT NULL DEFAULT '0' COMMENT '图片数量',
  `file_num` int(11) NOT NULL DEFAULT '0' COMMENT '附件数量',
  `filelist` text DEFAULT NULL COMMENT '附件列表',
  `first_img` varchar(100) DEFAULT NULL  COMMENT '首图片',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `reply` int(11) NOT NULL DEFAULT '0' COMMENT '回复',
  `keywords` varchar(200) DEFAULT NULL  COMMENT '关键词',
  `description` varchar(200) DEFAULT NULL  COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `5isns_comment`;
CREATE TABLE IF NOT EXISTS `5isns_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '上级评论',
  `uid` int(11) NOT NULL COMMENT '所属会员',
  `fid` int(11) NOT NULL COMMENT '所属帖子',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1帖子2文档',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `ding` int(11) DEFAULT '0' COMMENT '赞',
  `reply` int(11) DEFAULT '0' COMMENT '回复',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论表';

DROP TABLE IF EXISTS `5isns_jubao`;
CREATE TABLE IF NOT EXISTS `5isns_jubao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '所属会员',
  `fid` int(11) NOT NULL COMMENT '帖子文档用户',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0用户1帖子2文档',
  `reason` tinyint(1) NOT NULL DEFAULT '1' COMMENT '原因',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='举报表';

DROP TABLE IF EXISTS `5isns_message`;
CREATE TABLE IF NOT EXISTS `5isns_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '所属会员',
  `touid` int(11) NOT NULL DEFAULT '0' COMMENT '发送对象',
  `type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '1系统消息2私信',
  `content` text NOT NULL COMMENT '内容',
  `id_to_id` varchar(100) NOT NULL COMMENT '对话',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态2表示已读',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='消息表';


DROP TABLE IF EXISTS `5isns_searchword`;
CREATE TABLE IF NOT EXISTS `5isns_searchword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '热搜关键词',
  `uid` int(10) NOT NULL,
  `num` int(20) NOT NULL DEFAULT '1' COMMENT '搜索次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户热搜表';



DROP TABLE IF EXISTS `5isns_tagsandother`;
CREATE TABLE `5isns_tagsandother` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '话题名称',
  `tid` int(11) NOT NULL COMMENT '话题id',
  `did` int(11) NOT NULL COMMENT '管理目标id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关联目标类型0用户1帖子2文档',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `5isns_usersandother`;
CREATE TABLE `5isns_usersandother` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `did` int(11) NOT NULL COMMENT '管理目标id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关联目标类型0用户1帖子2话题3文档4消息5评论点赞',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS 5isns_session;
CREATE TABLE 5isns_session (
  sid char(32) NOT NULL default '0',			# 随机生成 id 不能重复 uniqueid() 13 位
  uid int(11) unsigned NOT NULL default '0',		# 用户id 未登录为 0，可以重复
  fid tinyint(3) unsigned NOT NULL default '0',		# 所在的版块
  url char(32) NOT NULL default '',			# 当前访问 url
  ip int(11) unsigned NOT NULL default '0',		# 用户ip
  useragent char(128) NOT NULL default '',		# 用户浏览器信息
  data char(255) NOT NULL default '',			# session 数据，超大数据存入大表。
  bigdata tinyint(1) NOT NULL default '0',		# 是否有大数据。
  last_date int(11) unsigned NOT NULL default '0',	# 上次活动时间
  PRIMARY KEY (sid),
  KEY ip (ip),
  KEY fid (fid),
  KEY uid_last_date (uid, last_date)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS 5isns_session_data;
CREATE TABLE 5isns_session_data (
  sid char(32) NOT NULL default '0',			#
  last_date int(11) unsigned NOT NULL default '0',	# 上次活动时间
  data text NOT NULL,					# 存超大数据
  PRIMARY KEY (sid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


# 持久的 key value 数据存储, ttserver, mysql
DROP TABLE IF EXISTS 5isns_kv;
CREATE TABLE 5isns_kv (
  k char(32) NOT NULL default '',
  v mediumtext NOT NULL,
  expiry int(11) unsigned NOT NULL default '0',		# 过期时间
  PRIMARY KEY(k)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 缓存表，用来保存临时数据。
DROP TABLE IF EXISTS 5isns_cache;
CREATE TABLE 5isns_cache (
  k char(32) NOT NULL default '',
  v mediumtext NOT NULL,
  expiry int(11) unsigned NOT NULL default '0',		# 过期时间
  PRIMARY KEY(k)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 临时队列，用来保存临时数据。
DROP TABLE IF EXISTS 5isns_queue;
CREATE TABLE 5isns_queue (
  queueid int(11) unsigned NOT NULL default '0',		# 队列 id
  v int(11) NOT NULL default '0',			# 队列中存放的数据，只能为 int
  expiry int(11) unsigned NOT NULL default '0',		# 过期时间，默认 0，不过期
  UNIQUE KEY(queueid, v),
  KEY(expiry)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


