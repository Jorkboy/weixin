<?php

namespace App\Http\Controllers;

use App\Model\WX;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class WXController extends Controller
{
    protected $app;
    protected $server;

    public function __construct()
    {
        //在options中填入配置信息
        $options = config('weixin.config');
        //使用配置初始化一个项目实例
        $this->app = new Application($options);
        //从项目实例中得到一个服务端应用实例
        $this->server = $this->app->server;
    }

    public function index()
    {
        $WX = new WX();
        $movie = $WX->getMovie();
        dd($movie);
    }

    //消息方法
    public function message()
    {
        //实例化WX模型
        $WX = new WX();
        //传入闭包
        $this->server->setMessageHandler(function($message) use($WX){
            return $WX->messageHandle($message);
        });

        //响应输出,laravel直接return即可
        return $this->server->serve();
    }

    //自定义菜单方法
    public function menu($type = 'get')
    {
        $menu = $this->app->menu;
        switch (strtolower($type)){
            case 'get' :
                return $menu->all();
                break;
            case 'delete' :
                return $menu->destroy();
                break;
            case 'add' :
                return $menu->add(config('weixin.menu'));
            default :
                return false;
        }
    }

    //验证授权
    public function oauth(Request $request)
    {
        // 未登录
        if (!$request->session()->has('wechat_user')) {
            $oauth = $this->app->oauth;
            return $oauth->redirect();
        }else{
            //return redirect()->action('WXController@menu');
            header("Location: http://www.jorkboy.xyz");
        }
    }

    //通过授权后的回调地址
    public function callback(Request $request)
    {
        $oauth = $this->app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        //保存session
        $request->session()->put('wechat_user', $user);
        return redirect()->action('WXController@index');
    }

    //获取永久二维码
    public function getQRCode()
    {
        $qrcode = $this->app->qrcode;
        $result = $qrcode->forever(56);
        $ticket = $result->ticket;// 或者 $result['ticket']
        //$expireSeconds = $result->expire_seconds; // 有效秒数
        //$url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
        $url = $qrcode->url($ticket);  //二维码图片url
        header("location:$url");    //跳转展示二维码
    }

    //素材管理
    public function materialHandle()
    {
        //永久素材实例
        $material = $this->app->material;
        //$result = $material->uploadImage(public_path('upload/qrcode.jpg'));

        $lists = $material->lists('image');
        dd($lists);
    }

    //消息群发
    public function broadcast()
    {
        $broadcast = $this->app->broadcast;
        $broadcast->sendText("不行，森哥，我要做个草榴公众号，怎么看？");
    }
}
