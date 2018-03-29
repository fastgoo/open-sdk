<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/28
 * Time: 下午9:51
 */

namespace FastGoo;

class WechatAuth
{
    use OpenCrypt, Jwt;

    private $app_id;
    private $app_secret;
    private $web_auth_url = "https://open.fastgoo.net/wechat/web_auth/redirect";

    public function __construct(Array $config = [])
    {
        if (empty($config['app_id']) || empty(!empty($config['app_secret']))) {
            exit("app_id or app_secret is undefined");
        }
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->setJwtSecret($this->app_secret);
        $this->setCryptSecret($this->app_secret);
    }

    /**
     * 跳转到指定的地址微信授权
     * @param string $callbackUrl
     */
    public function redirect(string $callbackUrl = '')
    {
        $host = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}";
        $callback_url = urlencode($host . $callbackUrl);
        $redirect_url = $this->web_auth_url . "?redirect_url=$callback_url&app_id={$this->app_id}";
        header("Location: $redirect_url");
        exit();
    }

    /**
     * 解析用户token数据
     * @param string $jwtStr
     * @return bool|object
     */
    public function jwtDecode(string $jwtStr)
    {
        return $this->decode($jwtStr);
    }

    /**
     * 数据解密
     * @param string $encryptData
     * @param string $iv
     * @return bool|string
     */
    public function dataDecrypt(string $encryptData, string $iv)
    {
        if (!$encryptData || !$iv) {
            return false;
        }
        $this->setIv($iv);
        return $this->decrypt($encryptData);
    }

}