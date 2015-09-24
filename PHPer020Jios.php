<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "Jios");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();  
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Conte nt>
                            <FuncFlag>0</FuncFlag>
                            </xml>";             
                  
                $ev = $postObj->Event;
                if ($ev == "subscribe") {
                    
                    $textTpl = "<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[news]]></MsgType>
                          <ArticleCount>4</ArticleCount>
                          <Articles>

                          <item>
                          <Title><![CDATA[感谢关注!回复1、2、3了解更多]]></Title>
                          <Description><![CDATA[descriptionl]]></Description>
                          <PicUrl><![CDATA[http://jioszhang.imwork.net/1.png]]></PicUrl>
                          <Url><![CDATA[http://www.baidu.com]]></Url>
                          </item>

                          <item>
                          <Title><![CDATA[这里是微信开发者导航]]></Title>
                          <Description><![CDATA[descriptionl]]></Description>
                          <PicUrl><![CDATA[http://jioszhang.imwork.net/2.jpg]]></PicUrl>
                          <Url><![CDATA[http://yiqixueweixin.sinaapp.com/]]></Url>
                          </item>

                          <item>
                          <Title><![CDATA[微信内网页开发工具包(微信JS-SDK)详解【含源码】]]></Title>
                          <Description><![CDATA[descriptionl]]></Description>
                          <PicUrl><![CDATA[http://www.9miao.com/template/dean_hotspot_141011/deancss/logo.png]]></PicUrl>
                          <Url><![CDATA[http://www.9miao.com/thread-67512-1-1.html]]></Url>
                          </item>

                          <item>
                          <Title><![CDATA[微信公众平台开发]]></Title>
                          <Description><![CDATA[descriptionl]]></Description>
                          <PicUrl><![CDATA[http://mp.weixin.qq.com/wiki/static/assets/ac9be2eafdeb95d50b28fa7cd75bb499.png]]></PicUrl>
                          <Url><![CDATA[http://mp.weixin.qq.com/wiki/home/index.html]]></Url>
                          </item>


                         

                          </Articles>
                          <FuncFlag>1</FuncFlag>

                          </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;

                 //     $msgType="text";
                 //     $contentStr="感谢您的关注！！ Welcome to 陈建凯工作室！回复1：我的姓名、2：我的专业、3：了解更多，谢谢！";
            
                    // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    // echo $resultStr;
               
                }






                if(!empty( $keyword ))
                {
                    $msgType = "text";
                    switch ($keyword) {
                        case '1':
                        $contentStr = "个人简介：我叫张志富，是广州大学华软学院2012级软件开发专业4班的学生。Welcome to  Jios 软件开发工作室。哈哈！！";
                            break;
                        case '2':
                        $contentStr = "天气预报：直接输入城市名或者经纬度即可查询天气情况";
                            break;
                        case '3':
                        $contentStr = "音乐欣赏：";
                                $musicTpl = "<xml> 
                                            <ToUserName><![CDATA[%s]]></ToUserName> 
                                            <FromUserName><![CDATA[%s]]></FromUserName> 
                                            <CreateTime>%s</CreateTime> 
                                            <MsgType><![CDATA[%s]]></MsgType> 
                                            <Music> 
                                                    <Title><![CDATA[%s]]></Title> 
                                                    <Description><![CDATA[%s]]></Description> 
                                                    <MusicUrl><![CDATA[%s]]></MusicUrl> 
                                                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl> 
                                            </Music> 
                                            </xml>"; 
                                $msgType = "music"; //表音乐消息类型  
                                $Title = " Fade.mp3"; //音乐标题  
                                $Description = "Alan Walker";//音乐描述  
                                $MusicUrl = $HQMusicUrl = "http://jioszhang.imwork.net/Fade.mp3";  

                                $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType,  
                                $Title, $Description, $MusicUrl, $HQMusicUrl);//经过sprintf处理  
                                echo $resultStr; 
                        
                            
                    }
                    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }


               
 

 /*                          $musicTpl= "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[music]]></MsgType>
                            <Music>
                            <Title><![CDATA[Alan Walker - Fade - 纯音乐版.mp3]]</Title>
                            <Description><![CDATA[Alan Walker]]></Description>
                            <MusicUrl><![CDATA[http://jioszhang.imwork.net/Fade.mp3]]></MusicUrl>
                            <HQMusicUrl><![CDATA[http://jioszhang.imwork.net/Fade.mp3]]></HQMusicUrl>
                            </Music>        
                        
                            </xml>"; 
*/      
                
                    // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
        //          echo $resultStr;
             




        }else {
            echo "";
            exit;
        }


    }
        
    private function checkSignature()   //校验 署名
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');  //抛出一个异常
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce); //把获取到的参数  放进一个临时数组
        // use SORT_STRING rule  
        sort($tmpArr, SORT_STRING); //排序算法，原有的sort($tmpArr)修改为sort($tmpArr, SORT_STRING)，php中sort（）函数默认为SORT_REGULAR，即原来的数据类型。
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>