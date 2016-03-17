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
        echo '';
    }
}

define('TOKEN', 'QingLoveYue');
$signature = $_GET['signature'];
$timestamp = $_GET['timestamp'];
$nonce = $_GET['nonce'];
$echo_str = $_GET['echostr'];

if ($signature != null && $timestamp != null && $nonce != null && $echo_str != null) {

    checkSignature($signature, $timestamp, $nonce, $echo_str);
    exit;
}

$post_str = $GLOBALS['HTTP_RAW_POST_DATA'];
$default_msg = array("喵～您的消息我们已收到，将会尽快回复您 (•∀•́)و ",
    "您投喂的饲料已被放入猫盆中，请等待(´,,•ω•,,‘)",
    "收到啦!～给我小鱼干我就回复你哦ლ(´ڡ`ლ)");
$welcome_msg = "Welcome to FDUTOPIA! To make FDU a better place (●'◡'●)ノ♥";
$default_msg_id = rand(0, count($default_msg)-1);

if ($post_str != null) {

    global $default_msg, $welcome_msg, $default_msg_id;

    libxml_disable_entity_loader(true);
    $post_obj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);

    if ($post_obj->MsgType == 'text') {
        response_query($post_obj);
        exit;
    } else if ($post_obj->MsgType == 'event') {

        if ($post_obj->Event == 'subscribe') {
            response_text($post_obj, $welcome_msg);
        } else {
            echo '';
            exit;
        }
    } else {
        echo response_text($post_obj, $default_msg[$default_msg_id]);
        exit;
    }
} else {
    echo '';
    exit;
}

function get_access_token() {

    $url = 'https://api.weixin.qq.com/cgi-bin/token';
    $get_data = array(
        'grant_type' => 'client_credential',
        'appid' => 'wxeacc90de62d5cfb2',
        'secret' => 'cd27e5e09167f0fea8b6f26a71cf436e'
    );
    $access_token_xml = send_get($url, $get_data);
    $access_token_obj = simplexml_load_string($access_token_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    return $access_token_obj->access_token;
}

function response_text($post_obj, $content) {
    $text_template = '<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                      </xml>';
    $echo_str = sprintf($text_template, $post_obj->FromUserName, $post_obj->ToUserName, time(), $content);
    echo $echo_str;
}

function response_query($post_obj) {

    global $default_msg, $default_msg_id;

    if (is_numeric(strval($post_obj->Content)) == 1) {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = "select count(*) as cnt from published_event;";
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        $query_num = intval($post_obj->Content);

        $content = "喵～您输入的编号不在本期活动内，本期共有" . $row['cnt'] . "个活动";
        if ($query_num <= $row['cnt'] && $query_num > 0) {

            $query = sprintf("select details from published_event where order_id=%d;", $query_num);
            $res = mysql_query($query, $mysql);
            $row = mysql_fetch_assoc($res);
            $content = $row['fullname'] . ': ' . $row['details'];
        }

        response_text($post_obj, $content);

    } else {
        if ($post_obj->Content == '发布') {

            $text_template = '<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
							    <FromUserName><![CDATA[%s]]></FromUserName>
							    <CreateTime>%s</CreateTime>
							    <MsgType><![CDATA[news]]></MsgType>
							    <ArticleCount>1</ArticleCount>
                                <Articles>
                                    <item>
                                        <Title><![CDATA[%s]]></Title>
                                        <Description><![CDATA[%s]]></Description>
                                        <PicUrl><![CDATA[%s]]></PicUrl>
                                        <Url><![CDATA[%s]]></Url>
                                    </item>
                                </Articles>
					        </xml>';

            $title = '戳我戳我！发布一则新公告';
            $desc = '点击“阅读原文”进入后台管理页面';
            $pic_url = 'https://mmbiz.qlogo.cn/mmbiz/h4vaiaNvovPrNwVKyj4mUhDcQku8F4EiaibGk18SpbQLzolicRciajlb78aKh3uXh1qBrwPIzicX9oQUt3fAvjzGTt8Q/0?wx_fmt=jpeg';
            $url = 'http://mp.weixin.qq.com/s?__biz=MzI3NjE5NTU3MQ==&mid=402047169&idx=1&sn=404d365a1fd5560e1141657f8d63bf42&scene=0&previewkey=afxeYHJgZGoJ2%2FS8F0m7k8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect';
            $echo_str = sprintf($text_template, $post_obj->FromUserName, $post_obj->ToUserName, time(), $title, $desc, $pic_url, $url);
            echo $echo_str;

        } else {
            response_text($post_obj, $default_msg[$default_msg_id]);
        }
    }
}
?>
