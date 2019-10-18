<?php

define("TOKEN", "connectWXToken");//自己定义的token 就是个通信的私钥

$wechatObj = new wechatCallbackapiTest();

//$wechatObj->valid();

$wechatObj->responseMsg();

class wechatCallbackapiTest

{

    public function valid()

    {

        $echoStr = $_GET["echostr"];

        if($this->checkSignature()){

            echo $echoStr;

            exit;

        }

    }

    public function responseMsg()

    {

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){

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

            <Content><![CDATA[%s]]></Content>

            <FuncFlag>0<FuncFlag>

            </xml>";

            if($postObj->MsgType == 'event'){ //如果XML信息里消息类型为event
                if($postObj->Event == 'subscribe'){ //如果是订阅事件
                    $msgtype = "text";
                    $contentStr = "欢迎使用智能保修系统 \n  <a href='http://219.218.160.81/repair/public/userHomeView'>点击报修</a>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgtype, $contentStr);
                    echo $resultStr;
                    exit();
                }
            }
            if(!empty( $keyword ))
            {
                $msgType = "text";

                $contentStr = " <a href='http://219.218.160.81/repair/public/userHomeView'>点击报修</a> ";

                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

                echo $resultStr;

            }else{

                echo '说说话吧';

            }

        }else {

            echo '说说话吧';

            exit;

        }

    }

    private function checkSignature()

    {

        $signature = $_GET["signature"];

        $timestamp = $_GET["timestamp"];

        $nonce = $_GET["nonce"];

        $token =TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);

        sort($tmpArr);

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