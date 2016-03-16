<?php

function checkSignature($signature, $timestamp, $nonce, $echostr) {
    $token = TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    if( $tmpStr == $signature ) {
        echo $echostr;
    }else{
        echo "";
    }
}

define("TOKEN", "QingLoveYue");
$signature = $_GET["signature"];
$timestamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];
$echostr = $_GET["echostr"];

if ($signature != null && $timestamp != null && $nonce != null && $echostr != null) {

    checkSignature($signature, $timestamp, $nonce, $echostr);
    exit;
}

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

if (!empty($postStr)) {

    libxml_disable_entity_loader(true);
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

    if ($postObj->MsgType == "shortvideo" || $postObj->MsgType == "video") {
        response_shortvideo($postObj);
        exit;
    } else {
        response_defaultmsg($postObj);
        exit;
    }
} else {
    echo "";
    exit;
}

function send_get($url, $get_data) {

    $get_data = http_build_query($get_data);
    $options = array(
        "http" => array(
            "method" => "GET",
            "content" => $get_data,
            'timeout' => 15
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function get_access_token() {

    $url = "https://api.weixin.qq.com/cgi-bin/token";
    $get_data = array(
        "grant_type" => "client_credential",
        "appid" => "wxeacc90de62d5cfb2",
        "secret" => "cd27e5e09167f0fea8b6f26a71cf436e"
    );
    $access_token_xml = send_get($url, $get_data);
    $access_token_obj = simplexml_load_string($access_token_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    return $access_token_obj->access_token;
}



function save_video($video_url, $file_name) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $video_url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
    $video=curl_exec($curl);
    curl_close($curl);
    $video_file = @fopen("staticGIF//materials//" . $file_name, "w");
    fwrite($video_file, $video);
    fclose($video_file);
    unset($video, $video_url);

}

function response_defaultMsg($postObj) {

    $FromUserName = $postObj->FromUserName;
    $ToUserName = $postObj->ToUserName;
    $time = time();
    $textTemplate = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
					</xml>";
    $MsgType = "text";
    $contentStr = "欢迎使用Everlasting～\n";
    $contentStr .= "您的消息主页君看到后会及时回复滴。\n";
    $contentStr .= "如果不知道怎么使用请看【第一次玩】，如果想欣赏往期的优秀作品，请看【别人在玩]";
    $resultStr = sprintf($textTemplate, $FromUserName, $ToUserName, $time, $MsgType, $contentStr);
    echo $resultStr;
}

function response_shortvideo($postObj) {

    print_r($postObj);

    $FromUserName = $postObj->FromUserName;
    $ToUserName = $postObj->ToUserName;
    $time = time();
    $MediaId = $postObj->MediaId;
    $textTemplate = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
					</xml>";
    $MsgType = "text";

    $dirStr = date("Ymdh") . "-";
    $randomStr = "";
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for ($i = 0; $i < 10; $i++) {
        $idx = rand(0, strlen($chars)-1);
        $randomStr .= $chars[$idx];
    }
    $dirStr .= $randomStr;

    $contentStr = "http://42.96.206.142/Everlasting/" . $randomStr;
    $resultStr = sprintf($textTemplate, $FromUserName, $ToUserName, $time, $MsgType, $contentStr);

    $access_token = get_access_token();

    $video_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$MediaId;
    $video_name = $randomStr . "mp4";
    get_video($video_url, $video_name);

    mkdir($dirStr);
    exec("./statifGIF/staticGIF " . "materials//". $video_name);
    exec("mv staticGIF/gifs/* " . $dirStr . "/gifs/");

    echo $resultStr;
}

?>