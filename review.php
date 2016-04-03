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
    <title>回顾上周活动 | FDUTOPIA</title>
</head>

<body ontouchstart>
<?php
$forbid = false;
if (isset($_GET['datestamp']) && $_GET['datestamp'] != "") {
    $forbid = true;
} else {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $date = $_GET['datestamp'] . ' 00:00:00';
    $query = sprintf('select * from published_event natural join event_info natural join users where published_date="%s";', $date);
    $res = mysql_query($query, $mysql);
    if (!mysql_num_rows($res)) {
        $forbid = true;
        mysql_close($mysql);
    }
}
if (!$forbid) {
?>
<div class="page_header">
    <h1 class="page_title">FDUTOPIA</h1>
    <?php
        $intro_level = array('六', '七', '八');
        $intro_num = '';
        $intro_area = array('匡塞特角', '麦肯塔', '沃斯堡', '布雷德利', '恩格林', '怀特曼', '罗拉多泉', '小石城');
        $intro_symbol = array('烙铁', '眼镜蛇', '霸王龙', '发电机', '黄金峡谷', '滚雷', '和平', '盾牌', '火炬', '花园');
        for ($i = 0; $i < 4; $i++) $intro_num .= rand(0, 9) . '';
    ?>
    <p class="page_desc">我是复旦军情处<?php $rand_id = rand(0, count($intro_level)-1); echo $intro_level[$rand_id];?>级特工，编号<?php echo $intro_num;?></p>
    <p class="page_desc">欢迎来到<?php
        $rand_id = rand(0, count($intro_area)-1);
        echo $intro_area[$rand_id];
        ?>的秘密基地，代号<?php
        $rand_id = rand(0, count($intro_symbol)-1);
        echo $intro_symbol[$rand_id];
        ?></p>
    <p class="page_desc">下面是一周情报速递，为荣誉而战吧！</p>
</div>

<div class="page_body">
    <?php
        $html = '<section><ol style="list-style-type: decimal; padding-left: 35px;">';
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['review_url'] != null && $row['review_url'] != '') {
                $url = '';
                if (strtolower(substr($row['review_url'], 0, 8)) == 'https://' or
                    strtolower(substr($row['review_url'], 0, 7) == 'http://')
                ) {
                    $url = $row['review_url'];
                } else {
                    $url = 'http://' . $row['review_url'];
                }
                $html .= sprintf('<li><a href="%s" style="font-size: 16px; color: black;"><strong>%s</strong></a>', $url, $row[title]);
                if ($row['username'] != 'fdubot') {
                    $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
                }
                $html .= '</li>';
            }
        }

        $html .= '</ol></section>';
        echo $html;
        mysql_close($mysql);
    ?>
    <br>

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="client_history.php">返回我的历史发布</a>
    </div>
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
            <strong class="weui_dialog_title">违规访问</strong>
        </div>
        <div class="weui_dialog_bd">
            抱歉！你的口令有误！<br>
            如果你是自己人，请从公众号FDUTOPIA进入！
        </div>
        <div class="weui_dialog_ft">
            <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    <?php
}
?>