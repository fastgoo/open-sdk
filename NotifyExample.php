<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/31
 * Time: 上午10:45
 */
include "./vendor/autoload.php";

use FastGoo\Payment;

$config = ['app_id' => '5abed4bfb179e', 'app_secret' => 'ef04a39f8f55c2c998491f0236f3f1cf'];
$payment = new Payment($config);
file_put_contents('./log.log',json_encode($_POST,JSON_UNESCAPED_UNICODE),PHP_EOL,FILE_APPEND);
$data = $payment->dataDecrypt($_POST['encryptedData'],$_POST['iv']);
file_put_contents('./log.log',json_encode($data,JSON_UNESCAPED_UNICODE),PHP_EOL,FILE_APPEND);