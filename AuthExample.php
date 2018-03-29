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
$config = ['app_id' => '5a5bffe6dedb3', 'app_secret' => '86b1c50e6c5e068ae7d7b465d6758b8c'];
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

