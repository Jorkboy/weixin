<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017-04-12
 * Time: 17:04
 */

namespace App\Tool;


class Robot
{
    public function __construct($message)
    {
        //图灵机器人接口
        $this->url = config('weixin.robotURL');
        //发送的数据
        $data = config('weixin.robot');
        //要发送内容
        $data['info'] = $message->Content;
        //发送者的openid
        $data['userid'] = md5($message->FromUserName);

        $this->data = Tool::encodeJson($data);
    }

    //发送数据给机器人接口,返回结果数组
    public function send()
    {
        $result = Tool::decodeJson(Tool::curl($this->url, 'POST', $this->data));
        //获取结果后交给handle方法处理
        return $this->handle($result);
    }

    private function handle(array $result)
    {
        switch ($result['code']){
            case '100000' :      //文本类
                $news = $this->text($result);
                break;
            case '200000' :      //连接类
                $news = $this->link($result);
                break;
            case '302000' :     //新闻类
                $news = $this->news($result);
                break;
            case '308000' :     //菜谱类
                $news = $this->menu($result);
                break;
            default :
                $news = '';
                break;
        }

        return $news;
    }

    //文本类的处理return array
    private function text(array $result)
    {
        $content['content'] = $result['text'];
        return $content;
    }

    //链接类
    private function link(array $result)
    {
        //返回图文消息格式的二维数组
        return [
            [
                'title'       => $result['text'],
                'url'         => $result['url'],
                'image'       => config('weixin.link'),
            ]
        ];
    }

    //新闻类
    private function news(array $result)
    {
        $newsList = array();

        foreach ($result['list'] as $news){
            $newsList[] = [
                'title'       => $news['article'],
                'description' => $news['source'],
                'url'         => $news['detailurl'],
                'image'       => $news['icon'],
            ];
        }

        //返回图文消息格式的二维数组
        if(count($newsList) <= 5){
            return $newsList;
        }else{
            return array_slice($newsList, 0, 5);
        }
    }

    //菜谱类
    private function menu(array $result)
    {
        $newsList = array();

        foreach ($result['list'] as $news){
            $newsList[] = [
                'title'       => $news['name'],
                'description' => $news['info'],
                'url'         => $news['detailurl'],
                'image'       => config('weixin.foodie'),
            ];
        }

        //返回图文消息格式的二维数组
        if(count($newsList) <= 5){
            return $newsList;
        }else{
            return array_slice($newsList, 0, 5);
        }
    }
}