<?php

namespace EInvoice\Tools;

use Curl\Curl;
use EInvoice\Exceptions\InvalidResponseException;

class RequestTool {

    /**
     * curl请求
     *
     * @param       $url
     *                   请求url
     * @param array $data
     *                   附加数据
     *
     * @return mixed|null
     * @throws \ErrorException
     * Author: DQ
     */
    public static function get($url, $data = [], $headers = []) {
        $request = new Curl();
        if (!empty($headers)) {
            $request->setHeaders($headers);
        }
        $request->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $request->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $request->setHeader('Content-Type', 'application/json');
        $request->setTimeout(3);
        $request->get($url, $data);
        $request->close();
        $content = null;
        if ($request->httpStatusCode != 200) {
            self::_handlerHttpResponse($request);
        } else {
            $content = $request->getRawResponse();
        }

        return $content;
    }

    /**
     * post 请求
     *
     * @param       $url
     * @param array $data
     * @param array $headers
     *
     * @return null
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public static function post($url, $data = [], $headers = []) {
        $request = new Curl();
        if (!empty($headers)) {
            $request->setHeaders($headers);
        }
        $request->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $request->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $request->setTimeout(3);
        $request->post($url, $data);
        $request->close();
        $content = null;
        if ($request->httpStatusCode != 200) {
            self::_handlerHttpResponse($request);
        } else {
            $content = $request->getRawResponse();
        }

        return $content;
    }

    /**
     * put 请求
     *
     * @param       $url
     * @param array $data
     * @param array $headers
     *
     * @return null
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public static function put($url, $data = [], $headers = []) {
        $request = new Curl();
        if (!empty($headers)) {
            $request->setHeaders($headers);
        }
        $request->setHeader('Content-Type', 'application/json');
        $request->setTimeout(3);
        $request->put($url, $data);
        $request->close();
        $content = null;
        if ($request->httpStatusCode != 200) {
            self::_handlerHttpResponse($request);
        } else {
            $content = $request->getRawResponse();
        }

        return $content;
    }

    /**
     * del 请求
     *
     * @param       $url
     * @param array $data
     * @param array $headers
     *
     * @return null
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public static function del($url, $data = [], $headers = []) {
        $request = new Curl();
        if (!empty($headers)) {
            $request->setHeaders($headers);
        }
        $request->setHeader('Content-Type', 'application/json');
        $request->setTimeout(3);
        $request->delete($url, $data);
        $request->close();
        $content = null;
        if ($request->httpStatusCode != 200) {
            self::_handlerHttpResponse($request);
        } else {
            $content = $request->getRawResponse();
        }

        return $content;
    }

    /**
     * 详情体处理
     *
     * @param \Curl\Curl $request
     *
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * Author: DQ
     */
    private static function _handlerHttpResponse(Curl $request) {
        $msg = json_decode($request->getRawResponse(), true);
        $str = isset($msg['message'])?$msg['message']:'';
        switch ($request->httpStatusCode){
            case 400:
                throw new InvalidResponseException('Bad Request(请求参数不合法).'.$str, 400);break;
            case 401:
                throw new InvalidResponseException('Unauthorized(认证不通过或访问令牌失效).'.$str, 401);break;
            case 403:
                throw new InvalidResponseException('Forbidden(访问无权限).'.$str, 403);break;
            case 404:
                throw new InvalidResponseException('Not Found().'.$str, 404);break;
            case 409:
                throw new InvalidResponseException('Conflict(业务异常情况返回该状态码).'.$str, 409);break;
            case 500:
                throw new InvalidResponseException('Internal Server Error(服务异常).'.$str, 500);break;
            case 502:
                throw new InvalidResponseException('Bad Gateway(网关错误).'.$str, 502);break;
        }
    }

}