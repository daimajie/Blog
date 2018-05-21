<?php
$data = require __DIR__ . '/data.php';
return [
    'adminEmail' => 'git1314@163.com', //站点邮箱
    'user.passwordResetTokenExpire' => 3600,//重置密码token过期时间
    'content.tag.max' => 35, //每个话题下标签个数上限
    'pics' => $data['pics'],  //十张默认头像
];
