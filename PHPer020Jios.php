<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "Jios");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];                     //从微信用户获取一个随机变量$echoStr

        //valid signature , option
        if($this->checkSignature()){                     //访问checkSignature签名验证方法，如果签名一致，输出变量$echoStr
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];         //将信息保存到变量$当中，同时解析用户数据

      	//extract post data
		if (!empty($postStr)){  //如果用户端数据不为空
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself    libxml_disable_entity_loader是防止XML外部实体注入，
                   最好的办法就是自己检查XML的有效性*/
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;     //将用户端的用户名赋予变量$fromUsername
                $toUsername = $postObj->ToUserName;         //将公众号ID赋予变量$toUsername
                $keyword = trim($postObj->Content);         //将发来的文本内容去空格后赋予变量$keyword
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag> 
							</xml>";                      //  微信目标方  来远方 系统时间 回复微信的信息类型 内容
                                                          //<FuncFlag>0</FuncFlag>    是否为星标微信         
				if(!empty( $keyword ))                    // 如果用户端发来的消息不是空
                {
              		$msgType = "text";                    // 回复文本消息为text文本类型
                	$contentStr = "Welcome to wechat world!";//进行文本回复的内容，如果要改，只要在这里更改就可以 
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	                                      //将XML格式中的变量分别赋值
                    echo $resultStr;                      //   输出回复消息
                }else{
                	echo "Input something...";            //输入内容，此消息不会发送到微信端，只是测试时候使用
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()                    // 建立私有方法验证签名
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];               //从用户端获取签名赋予变量$signature
        $timestamp = $_GET["timestamp"];               //从用户段获取时间赋予变量$timestamp
        $nonce = $_GET["nonce"];                       //从用户段获取随机数赋予变量$snonce
        		
		$token = TOKEN;                                //将常量TOKEN值赋予变量$token
		$tmpArr = array($token, $timestamp, $nonce);   //建立数组变量$tmpArr
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);                   //新键名 排序
		$tmpStr = implode( $tmpArr );                 //字典排序
		$tmpStr = sha1( $tmpStr );                    //加密 
		
		if( $tmpStr == $signature ){                  //判断$tmpStr与$signature变量是否同值
			return true;
		}else{
			return false;
		}
	}
}

?>