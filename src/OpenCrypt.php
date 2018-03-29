<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/28
 * Time: 下午10:06
 */

namespace FastGoo;

use OpenCrypt\OpenCrypt AS OpenSSLCrypt;

trait OpenCrypt
{
    private $secret;
    private $iv;

    /**
     * 设置解密secret
     * @param string $secret
     */
    private function setCryptSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * 设置解密iv
     * @param string $iv
     */
    private function setIv(string $iv)
    {
        $this->iv = base64_decode(rawurldecode($iv));
    }

    /**
     * 数据解密
     * @param string $encryptData
     * @return string
     */
    private function decrypt(string $encryptData)
    {
        $openCrypt = new OpenSSLCrypt($this->secret, $this->iv);
        $decryptData = $openCrypt->decrypt(rawurldecode($encryptData));
        $decryptData && $decryptData = json_decode($decryptData, true);
        return $decryptData;
    }


}