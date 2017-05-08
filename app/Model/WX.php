<?php

namespace App\Model;

use EasyWeChat\Message\Image;
use Illuminate\Support\Facades\DB;
use App\Tool\Robot;
use App\Tool\Tool;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;

class WX
{
    //获取最后记录电影信息
    public function getMovieInfo()
    {
        $movie = DB::table('movie')
            ->orderBy('id', 'desc')
            ->first();

        return $movie;
    }

    //查找是否有电影条目
    public function findMovie($movieId)
    {
        return DB::table('movie')
            ->where('movie_id', $movieId)
            ->value('id');
    }

    //入库
    public function saveMovie($item)
    {
        $id = DB::table('movie')->insertGetId(
            [
                'movie_id' => $item['id'],
                'content' => serialize($item),
                'create_time' => time()
            ]
        );

        return $id;
    }

    /*
     * 消息的逻辑处理方法
     * @param   object  $message    用户输入信息
     * return   array
     */
    public function messageHandle($message)
    {
        //对用户发送的消息根据不同类型进行区分处理
        switch ($message->MsgType) {
            case 'event':
                return $this->event($message);
                break;
            //文本信息处理
            case 'text':
                //进行消息的处理,把内容发给用户
                return $this->textHandle($message);
                break;
            case 'image':
                return '收到图片，这是你的图片连接' . $message->PicUrl;
                break;
            case 'voice':
                return '你是在说：“' . $message->Recognition . '”吗？';
                break;
            case 'video':
                return '别发视频了啦，太耗流量';
                break;
            case 'shortvideo':
                return '小视频，大电影';
                break;
            case 'location':
                return '报告：经度' . $message->Location_Y . '，纬度' . $message->Location_X;
                break;
            case 'link':
                return '要是里面的内容不好看就不理你了';
                break;
            // ... 其它消息
            default:
                return '收到其它消息';
                break;
        }
    }

    /*
     * 文本类型的处理，一律交给图灵机器人
     * @param   object  $message    用户输入信息
     * return   array
     */
    public function textHandle($message)
    {
        //实例化机器人类
        $robot = new Robot($message);
        //执行send方法
        $news = $robot->send();
        //判断是什么消息类型
        if(isset($news['content'])){
            return $this->text($news);
        }else{
            return $this->news($news);
        }
    }

    /*
     * 回复文本消息类型
     * @param   array  $content    文本内容
     * return   object
     */
    public function text($content)
    {
        return new Text($content);
    }

    /*
     * 回复图文消息类型
     * @param   array  $mewsList    图文数组，一个元素代表一个图文消息
     * return   array
     */
    public function news(array $mewsList)
    {
        $newsObj = array();
        foreach($mewsList as $news){
            $newsObj[] = new News($news);
        }
        return $newsObj;
    }

    /*
     * 回复图片消息类型
     * return   array
     */
    public function image($mediaId)
    {
        return new Image(['media_id' => $mediaId]);
    }

    /*
     * 事件类型的处理
     * @param   object  $message    用户输入信息
     * return   array
     */
    public function event($message)
    {
        switch($message->Event){
            case 'subscribe' :  //关注事件,推送图文消息
                return $this->news(config('weixin.event.subscribe'));
                break;
            case 'CLICK' :  //点击菜单事件
                return $this->click($message);
                break;
            default :
                return $this->text('');
                break;
        }
    }

    /*
     * 处理各类菜单点击事件
     */
    public function click($message)
    {
        switch ($message->EventKey){
            case 'V1001_MUSIC' :
                //获取音乐
                $musicNews = $this->getMusic();
                return $this->news($musicNews);
                break;
            case 'V1001_MOVIE' :
                //获取电影
                $movieNews = $this->getMovie();
                return $this->news($movieNews);
                break;
            case 'V1001_QRCODE' :
                return $this->image(config('weixin.qrcode'));
                break;
            default :
                return false;
                break;
        }
    }

    //获取电影信息
    public function getMovie()
    {
        //看看数据库里有没有电影信息
        $movie = $this->getMovieInfo();

        //有数据且在24小时内，返回数据,否则从豆瓣api获取电影条目
        if ($movie && time() < $movie->create_time + 3600 *24){
            $item = unserialize($movie->content);
        }else{
            $tag = config('weixin.tag');
            $url = config('weixin.movieURL') .$tag[rand(0, count($tag) - 1)] ;
            $itemList = Tool::decodeJson(Tool::curl($url));

            if(isset($itemList['subjects'])){
                $rand = rand(0, count($itemList['subjects']) - 1);
                $item = $itemList['subjects'][$rand];
                $movieId = $item['id'];
                //查找数据库是否有此条电影信息
                $id = $this->findMovie($movieId);
                //没有才添加到数据库中
                if(!$id){
                    $id = $this->saveMovie($item);
                    if(!$id){
                        abort(400, '电影条目保存失败');
                    }
                }
            }else{
                //没电影数据就默认返回霸王别姬，哈哈
                return config('weixin.event.click.movie');
            }
        }

        //返回规范的数组格式
        $news = array([
            'title' => $item['title'],
            'url' => $item['alt'],
            'image' => $item['images']['large']
        ]);
        return $news;
    }

    //获取歌曲信息
    public function getMusic()
    {
        for ($i=0; $i < 15; $i++) {
            $url = config('weixin.musicURL') . '%5B' . mt_rand(100000, 35000000) . '%5D';
            $music = Tool::decodeJson(Tool::curl($url));
            if (isset($music['songs'][0]['name']) && $music['songs'][0]['name'] != 'null'){
                //返回规范化后的数组
                $news = [
                    [
                        'title'       => $music['songs'][0]['name'],
                        'description' => '',
                        'url'         => 'https://music.163.com/#/song?id=' . $music['songs'][0]['id'],
                        'image'       => $music['songs'][0]['album']['picUrl'],
                    ]
                ];
                return $news;
            }else{
                continue;
            }
        }

        //如果十五次都没有结果就返回默认音乐
        return config('weixin.event.click.music');
    }
}