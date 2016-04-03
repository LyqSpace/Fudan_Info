<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
<?php

$forbid = false;
$res = null;
$mysql = null;

if (!isset($_GET['datestamp']) || $_GET['datestamp'] == "") {
    $forbid = true;
} else {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $date = $_GET['datestamp'] . ' 00:00:00';

    $query = sprintf("update review_read set count=count+1 where published_date='%s';", $date);
    mysql_query($query, $mysql);

    $query = sprintf('select * from published_event natural join event_info natural join users where published_date="%s";', $date);
    $res = mysql_query($query, $mysql);
    if (!mysql_num_rows($res)) {
        $forbid = true;
        mysql_close($mysql);
    }
}
if (!$forbid) {
    $date_st = date('n月j日', strtotime($date));
    $date_ed = date('n月j日', strtotime("+6 days", strtotime($date)));
?>
    <title><?php echo $date_st . '-' . $date_ed;?>活动回顾 | FDUTOPIA</title>
</head>
<body ontouchstart onload="random_item_color()">
<div class="page_header">
    <div class="logo_img"></div>
    <?php
        $intro_names = array('朱迪', '尼克', '朱迪', '尼克', '朱迪', '尼克', '博戈局长', '本杰明警官', '大先生', '闪电');
        $intro_cities = array('冰川镇', '动物城撒哈拉广场', '动物城火车站', '布雷德利', '恩格林', '怀特曼', '罗拉多泉', '小石城');
        for ($i = 0; $i < 4; $i++) $intro_num .= rand(0, 9) . '';
    ?>
    <p class="page_desc">我是复旦乌托邦的<?php $rand_id = rand(0, count($intro_names)-1); echo $intro_names[$rand_id];?></p>
    <p class="page_desc">欢迎来到动物城的<?php $rand_id = rand(0, count($intro_cities)-1); echo $intro_cities[$rand_id];?></p>
    <p class="page_desc">下面的爪爪棒冰，戳标题即可享用</p>
    <p class="page_desc">生产日期<?php echo $date_st . '-' . $date_ed;?></p>
</div>

<div class="page_body">
    <?php
        $html = '';
        $cnt = 1;
        while ($row = mysql_fetch_assoc($res)) {

            if ($row['review_url'] == null || $row['review_url'] == '') continue;

            $url = '';
            if (strtolower(substr($row['review_url'], 0, 8)) == 'https://' or
                strtolower(substr($row['review_url'], 0, 7)) == 'http://') {
                $url = $row['review_url'];
            } else {
                $url = 'http://' . $row['review_url'];
            }
            $html .= sprintf('<div class="review_item" id="review_item%s"><ol style="list-style-type: decimal; padding-left: 35px;" start=%d><li>', $cnt-1, $cnt);
            $html .= sprintf('<a href="%s" style="font-size: 16px; color: black;"><strong>%s</strong></a>', $url, $row[title]);
            //if ($row['username'] != 'fdubot') {
                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
            //}
            $html .= '</li></ol></div>';
            $cnt++;
        }

        if ($cnt == 1) {
            echo '<p class="page_desc">爪爪冰棒还没做出来，过两天再来看看吧～</p>';
        } else {
            echo $html;
        }

    ?>
    <br>
    <p class="page_desc">素心无用，自由分享</p>
    <p class="page_desc">分享复旦内有生命力的讲座活动</p>
    <p class="page_desc">守望复旦人澎湃不息的赤子之心</p>
    <p class="page_desc">如果你喜欢我们，戳<a style="color: darkorange" href="http://weixin.qq.com/r/JTgaApzEpMHbrdhh9203">这里</a>关注FDUTOPIA</p>
    <br>
    <p style="font-size: 14px;color: #888;">阅读 <?php

        $query = sprintf("select * from review_read where published_date='%s';", $date);
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        echo $row['count'];
        mysql_close($mysql);
        ?></p>
</div>
<br>
</body>
</html>
<?php
} else {
    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">抱歉!口令有误!</strong>
        </div>
        <div class="weui_dialog_bd">
            如果你是自己人，请从公众号FDUTOPIA进入!
        </div>
        <div class="weui_dialog_ft">
            <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}
?>