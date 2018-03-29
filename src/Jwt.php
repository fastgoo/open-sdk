<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/29
 * Time: 下午4:04
 */

namespace FastGoo;

use Firebase\JWT\JWT as PhpJwt;
use Exception;

trait Jwt
{
    private $app_secret;
    private $app_id;

    /**
     * 设置secret
     * @param string $secret
     */
    private function setJwtSecret(string $secret)
    {
        $this->app_secret = $secret;
    }

    /**
     * jwt解密
     * @param string $jwtStr
     * @return bool|object
     */
    private function decode(string $jwtStr)
    {
        try {
            $data = PhpJwt::decode($jwtStr, $this->app_secret, array('HS256'));
            if (!is_array($data) && !is_object($data)) {
                throw new Exception('授权认证失败');
            }
            return (array)$data;
        } catch (Exception $e) {
            return false;
        }
    }

}