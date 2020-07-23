<?php
return array (
  'cache' => 
  array (
    'enable' => true,
    'type' => 'mysql',
    'memcached' => 
    array (
      'host' => 'localhost',
      'port' => '11211',
      'cachepre' => '5isns_',
    ),
    'redis' => 
    array (
      'host' => 'localhost',
      'port' => '6379',
      'cachepre' => '5isns_',
    ),
    'xcache' => 
    array (
      'cachepre' => '5isns_',
    ),
    'yac' => 
    array (
      'cachepre' => '5isns_',
    ),
    'apc' => 
    array (
      'cachepre' => '5isns_',
    ),
    'mysql' => 
    array (
      'cachepre' => '5isns_',
    ),
  ),
  'batabase_export_config' => 
  array (
    'path' => './data/database/',
    'DATA_BACKUP_PART_SIZE' => 20971520,
    'DATA_BACKUP_COMPRESS' => 1,
    'DATA_BACKUP_COMPRESS_LEVEL' => 9,
  ),
  'trashlist' => 
  array (
    'menu' => 
    array (
      0 => '菜单',
      1 => 'name',
      2 => 'id',
    ),
    'topiccate' => 
    array (
      0 => '话题',
      1 => 'name',
      2 => 'id',
    ),
    'topic' => 
    array (
      0 => '帖子',
      1 => 'title',
      2 => 'id',
    ),
    'doccon' => 
    array (
      0 => '文档',
      1 => 'title',
      2 => 'id',
    ),
    'comment' => 
    array (
      0 => '评论',
      1 => 'content',
      2 => 'id',
    ),
    'user' => 
    array (
      0 => '用户',
      1 => 'nickname',
      2 => 'id',
    ),
    'usergrade' => 
    array (
      0 => '会员等级',
      1 => 'name',
      2 => 'id',
    ),
  ),
  'pointname' => 
  array (
    'point' => '财富值',
    'expoint1' => '经验',
    'expoint2' => '扩展积分2',
    'expoint3' => '扩展积分3',
  ),
  'pointrule' => 
  array (
    'yaoqing' => '邀请注册',
    'login' => '登录',
    'docadd' => '发布文档',
    'docdelete' => '文档被删除',
    'bindmobile' => '绑定手机',
    'bindmail' => '绑定邮箱',
    'topicadd' => '发布文章',
    'topicdelete' => '文章被删除',
    'commentadd' => '发布评论',
    'commentdelete' => '评论被删除',
  ),
  'tmp_path' => './data/tmp/',
  'log_path' => './data/log/',
  'view_url' => 'view/',
  'upload_url' => 'upload/',
  'upload_path' => './upload/',
  'sitename' => '餐服行业招标信息',
  'web_url' => 'http://zbsns.canyinyunfu.com/',
  'api_url' => 'http://api.imzaker.com:80',
  'beinum' => '',
  'shuiyin' => '',
  'sy_type' => 0,
  'sitebrief' => '餐服行业招标信息',
  'timezone' => 'Asia/Shanghai',
  'runlevel' => 5,
  'runlevel_reason' => 'The site is under maintenance, please visit later.',
  'postlist_pagesize' => 100,
  'cache_thread_list_pages' => 10,
  'session_delay_update' => 0,
  'upload_image_width' => 927,
  'admin_bind_ip' => 0,
  'update_views_on' => 1,
  'no_img_index' => 0,
  'cookie_domain' => '',
  'cookie_path' => '',
  'online_hold_time' => 3600,
  'allow_cate_show' => 20,
  'choose_cate_num' => 0,
  'pagesize' => 10,
  'user_create_email_on' => 0,
  'user_create_on' => 1,
  'user_resetpw_on' => 1,
  'url_rewrite_on' => 0,
  'version' => '1.0.9',
  'static_version' => '?1.0',
  'cdn_use' => 0,
  'online_trans' => 0,
  'appid' => '123',
  'module_index' => 'index',
  'module_theme' => 
  array (
    'index' => 'default',
    'topics' => 'default',
    'docs' => 'default',
  ),
  'module_arr' => 
  array (
    0 => 'index',
    1 => 'admin',
    3 => 'topics',
    4 => 'docs',
  ),
  'chongzhi' => 
  array (
    'bili' => 10,
  ),
  'tixian' => 
  array (
    'bili' => 2,
  ),
  'alipay' => 
  array (
    'app_id' => '2021001159671026',
    'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA87O+4Zqs5Xo8v5vrnm6iPpgf7terSbZS+KIRQwjguHzbQZKYlqZJ51r/ib0ITRhIfjUO2DsINbwXro2gpRfImTXE5hoWBhWcyKjm67jQ/W8cxg1aRnK+wZHNkxGI32Mvy2FjSYzQOh8MNMb3Y5iFQl8Ved7Z1xkdL2KJT5TOikElsOPspRTXbXJIsL6xzWjO2hZN7OjIiKhWKlaImROciWj51n1+hmjqP/49MJx+sQJMLqSlZZ+TT6hnxRJXJkK23YGYEX95o0F2oppBUhLRnfxrWQBHRx2ZTZiUCp3nYMJCM8dlTBEGYR02mwAN2BqG11ss39fFAITPybrGmAAWewIDAQAB',
    'private_key' => 'MIIEpAIBAAKCAQEApvXHAnr62LaCDLitk9SgEqTtyLxqgZTd/m/R8kKAn+FdgPEAcRfkuzRsha1qGB87vdkff9LIQSrK2cKLk++EU+DH3jsEJXCvjDSVyv6tdbZafkcFP04sVNveU3B7qIn6Jum4/HfeWUr2vas9Err0BxnnpI7FBupNBFst36WxFfDPoiugMXxPG8ZCpaOJfJ5sRE45mtGKyOtM/794mRsluq3QQSLOrXci5lXxtisPwmX4TBPDdIiMR81pdQX8NVjC8LRo74EfZcnl6OPc26uImyuiNNCe+NeGwMbAl7c6UmGun7SEC/1YRFQc1a+aUJV74zrgu/qhg5dNmmwMJ8AivQIDAQABAoIBAAFfhhp8HnIH1jv6jNrpZcOhfZ8oBYw5SDYef1CJwXsPJHC6CrHja2sqvDXBPl0ibKBcF8k6V5FJo890rSczUhhKgfpO6/LO4XK6v/yiaZTNQiNFvEJQB0dPY522EB5LvDXzuCTDa526YxsePGtaBEXGrcQJtI8hAC8n/NGZEELI69JQQE36HaJZqCkIZ4Zbcrc4Ro+0MYSgTrOYHNTJsP6cmnyLEwaJkvYkVKYlpDsrR8A6SB2JEgyxjNxPWcd7mLZxdV0kUYweghNgFr5IzziVDSrdfvjB9aW2hBRm3FVPm0PFZEyYMyhAB6QkN7RaJ3nnIQW7pfFZVv9fGwoxcqECgYEA4bcfJDQUHSKmOh/ftBSpEZj1SBStia8wknqMfKpG1+tCkZ4FtbCMBJYURdxEB5G6/6FRCRk1iNedz38nYPb5eAd9aGoL9ohYtMGAF/chNa63yrE9KqXj3uc0sldIGh8mMmK6t9cmkQUHAzzb511z1as7NGJr/g1tYEH3gWxTbrkCgYEAvVyHNCnaXmXAUzzUMS9zqD6WUP/YYZoMvWqwpRBNhTxfzJQfrVuf1rr7ejqnrv0hRdfdoyu9maK4mY1Hh1/qXHclC6KUp3hALeJxfIXDFAsx8TN3/ApRB3iaOnYh1Y0G94v7MG2YrWLVdptEz6a7JA3qVflMTIvgnqPsgoQZMiUCgYEA3ioaMs43xf2m06svytZTGwkM1CfWL6EKFAMfaE1JcZkUarO5Nv5QsurthV2qcDDWeGpVkrkHYmun3uZu4hf1sFje7PkEUWIevbt/0xbhzy7rpEwTwniJ84pq6ebTBzMFq65VzkGsqizinCM451+qdfMWURdW6DZbI9WKQg9Xh7ECgYAtjRWFCdBaxtVF8KsGjeiffza/k1vk1p7b7c9CVbKgK07MMpGQLSs9B0u5MmTl5kRbvbq2Jq58R7VqUp7zRLJmvc5uhxSFWJrvrron/zFXH/5KjFv/iCfHyU4oZARtj3gukkDBfIuuocjBuEYMQYpg+Ov29HpTK47D+qDJIKfC9QKBgQCYGXNwGRu3+UTveDZ4QWn8iRPjmbZ4WMmxqS1MAYrJ6VTMZXHLCXmhh44H6bRCcicttlT4NxNkLlh8v4lTfYd6qs7bWdSc1KkC15BWMolsoK4LZDdomVzHSXbM7i0CAdMWHs5q0I8qLEEqOKnvH4xzDBQruAB3eE7aeP0AD9PHCQ==',
  ),
  'paymethod' => 1,
  'wechat' => 
  array (
    'mch_id' => '',
    'app_id' => '',
    'mch_key' => '',
  ),
);
?>