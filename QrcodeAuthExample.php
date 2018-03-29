<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/29
 * Time: 下午5:03
 */
include './vendor/autoload.php';

use FastGoo\WechatAuth;

$config = ['app_id' => '5a5bffe6dedb3', 'app_secret' => '86b1c50e6c5e068ae7d7b465d6758b8c'];
$token = !empty($_GET['token']) ? $_GET['token'] : '';
$auth = new WechatAuth($config);
if ($data = $auth->jwtDecode($token)) {
    var_dump($data);
    exit;
}
?>

<html>
<head>
    <meta HTTP-EQUIV="pragma" CONTENT="no-cache">
    <meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
    <meta HTTP-EQUIV="expires" CONTENT="0">
    <title>二维码授权范例</title>
</head>

<body>
<div id="showQrcode">
    正在获取二维码
</div>
<script src="https://resource.fastgoo.net/AuthWebsocket.js?v=1.0"></script>
<script>
    authWebsocket.setAppId("5a5bffe6dedb3");
    authWebsocket.setQrcodeCallback(function (ret) {
        document.getElementById("showQrcode").innerHTML = "<img src='" + ret.body.url + "'/>"
    });
    authWebsocket.setTokenCallback(function (ret) {
        location.href = window.location.href + '?token=' + ret.body.token;
    });
    setTimeout(function () {
        authWebsocket.getQrcode();
    }, 1000);

</script>
</body>
</html>
