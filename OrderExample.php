<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/30
 * Time: 下午2:42
 */
include "./vendor/autoload.php";

use FastGoo\Payment;

$config = ['app_id' => '5a5bffe6dedb3', 'app_secret' => '86b1c50e6c5e068ae7d7b465d6758b8c'];
$payment = new Payment($config);
$type = !empty($_GET['set_type']) ? $_GET['set_type'] : 'get_qrcode';
try {
    $host = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}";
    $order_info = $payment->makeOrder([
        'title' => '测试-标题',
        'content' => '测试-标题',
        'amount' => 1,
        'pay_type' => 'qrcode',
        'params' => ['id' => 1],
        'notify_url' => $host . '/NotifyExample.php'
    ]);
    if($type == 'get_qrcode'){
        /** 获取二维码url信息 */
        $qrcode_info = $payment->getQrcode($order_info['order_no']);
        exit("<img src='{$qrcode_info['url']}' />");
    }
    if($type == 'get_order_info'){
        /** 获取订单信息 */
        $orderInfo = $payment->getOrderInfo($order_info['order_no']);
        var_dump($orderInfo);exit;
    }
    if($type == 'redirect'){
        /** 跳转到页面去支付 */
        $payment->redirect($order_info['order_no']);
    }
} catch (\Exception $exception) {
    var_dump($exception->getMessage());
}
