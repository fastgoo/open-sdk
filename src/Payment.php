<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2018/3/28
 * Time: 下午9:51
 */

namespace FastGoo;

use GuzzleHttp\Client AS HttpClient;

class Payment
{
    use OpenCrypt;

    private $app_id;
    private $app_secret;
    /**
     * @var HttpClient
     */
    private $client;
    private $base_url = "https://open.fastgoo.net";
    private $unified_order_url = "/base.api/order/make";
    private $order_info_url = "/base.api/order/getOrderInfo";
    private $pay_qrcode_url = "/base.api/payment/getPayQrcode";
    private $pay_redirect_url = "/base.api/payment/payOrder";

    public function __construct(Array $config = [])
    {
        if (empty($config['app_id']) || empty(!empty($config['app_secret']))) {
            exit("app_id or app_secret is undefined");
        }
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->setCryptSecret($this->app_secret);
        $this->client = new HttpClient(['base_uri' => $this->base_url, 'timeout' => 10.0,]);
    }

    /**
     * 跳转到支付页面支付
     * @param string $order_no
     */
    public function redirect(string $order_no)
    {
        header("Location: {$this->base_url}{$this->pay_redirect_url}/{$order_no}");
        exit();
    }

    /**
     * 生成订单，可能会请求异常，需要用户自主去try catch
     * @param array $params app_id title amount content notify_url pay_type return_url params
     * @return mixed
     * @throws \Exception
     */
    public function makeOrder(array $params)
    {
        if (empty($params['title']) || empty($params['amount']) || empty($params['content']) || empty($params['notify_url']) || empty($params['pay_type'])) {
            exit("缺少关键参数");
        }
        return $this->request($this->unified_order_url,$params);
    }

    /**
     * 获取支付二维码
     * @param int $order_no
     * @return mixed
     * @throws \Exception
     */
    public function getQrcode(int $order_no)
    {
        return $this->request($this->pay_qrcode_url,compact('order_no'));
    }

    /**
     * 根据订单号获取订单信息
     * @param int $order_no
     * @return mixed
     * @throws \Exception
     */
    public function getOrderInfo(int $order_no)
    {
        return $this->request($this->order_info_url,compact('order_no'));
    }

    /**
     * 请求处理
     * @param string $url
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function request(string $url, array $params)
    {
        $params['app_id'] = $this->app_id;
        $response = $this->client->post($url, [
            'form_params' => $params
        ]);
        if (!$response) {
            throw new \Exception("请求接口地址失败，可能是服务器无响应或者超时");
        }
        $ret = json_decode($response->getBody()->getContents(), true);
        if (!$ret) {
            throw new \Exception("返回结果好像出错了");
        }
        if ($ret['code'] != 1) {
            throw new \Exception($ret['msg']);
        }
        return $ret['data'];
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