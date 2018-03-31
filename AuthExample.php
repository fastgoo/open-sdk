<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/29
 * Time: 下午2:08
 */

include "./vendor/autoload.php";

use FastGoo\WechatAuth;

$type = !empty($_GET['demo_type']) ? $_GET['demo_type'] : 'decode';
$config = ['app_id' => '5abed4bfb179e', 'app_secret' => 'ef04a39f8f55c2c998491f0236f3f1cf'];
$wechatAuth = new WechatAuth($config);
switch ($type) {
    case 'decode':
        $ret = $wechatAuth->dataDecrypt($_GET['encryptedData'], $_GET['iv']);
        var_dump($ret);
        exit;
        break;
    case 'redirect':
        $wechatAuth->redirect('/AuthExample.php?demo_type=decode');
        break;
}

