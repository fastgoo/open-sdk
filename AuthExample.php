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
$config = ['app_id' => '', 'app_secret' => ''];
$wechatAuth = new WechatAuth($config);
switch ($type) {
    case 'decode':
        $ret = $wechatAuth->dataDecrypt($_GET['encryptedData'], $_GET['iv']);
        var_dump($ret);
        exit;
        break;
    case 'redirect':
        $wechatAuth->redirect('/example.php?demo_type=decode');
        break;
}

