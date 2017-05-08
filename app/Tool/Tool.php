<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017-04-12
 * Time: 17:04
 */

namespace App\Tool;


class Tool
{
    /*
     * curl方法
     * @param   string  $url    要请求的地址
     * @param   string  $data    要发送的数据
     * @param   string  $type    发送请求的方法
     * @param   bool  $output    是否直接输出请求到的信息
     */
    public static function curl($url, $type = 'get', $data = array())
    {
        //初始化
        $ch = curl_init();
        //设置选项
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_HEADER, false);

        if(strtoupper($type) == 'POST'){
            if(is_string($data)){
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            }
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        }

        //不直接输出结果
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /*
     * 解码json格式字串
     * return   array
     */
    public static function decodeJson($string)
    {
        return json_decode($string, true);
    }

    /*
     * 编码成json格式字串
     * return   array
     */
    public static function encodeJson($string)
    {
        return json_encode($string, JSON_UNESCAPED_UNICODE);
    }

    /*
     * 返回json数据
     * @param   int     $status     状态码
     * @param   mix     $mess   信息
     */
    public static function returnJson($status = 200, $mess = 'ok')
    {
        response()->json(['status' => $status, 'mess' => $mess]);
    }
}