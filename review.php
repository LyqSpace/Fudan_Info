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
    <title>活动回顾 | FDUTOPIA</title>
</head>
<body ontouchstart onload="random_item_color()">
<div class="page_header">
    <div class="logo_img"></div>
    <p class="page_desc">恭喜发现彩蛋～</p>
    <p class="page_desc">这里是复旦乌托邦的雨林区</p>
    <p class="page_desc">下面的爪爪冰棍儿，点击标题即刻享用</p>
</div>

<div class="page_body"><?php

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = "update review_read set count=count+1;";
    mysql_query($query, $mysql);

    $cur_date = date('Y-m-d H:i:s', time());
    $query = sprintf('select * from event_info natural join users where review_url is not null and date<"%s" order by date desc limit 30;', $cur_date);
    $res = mysql_query($query, $mysql);

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
        $html .= sprintf('<a href="%s" style="font-size: 16px; color: black;"><strong>%s</strong></a>', $url, $row['title']);
        if ($row['username'] != 'fdubot') {
            $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
        }
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
    <br>
    <p class="page_desc">如果你喜欢我们，欢迎关注公众号FDUTOPIA</p>
    <br>
    <p style="font-size: 14px;color: #888;">阅读 <?php

        $query = "select * from review_read;";
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        echo $row['count'];
        mysql_close($mysql);
        ?></p>
</div>
<br>
<canvas id="canvas_effect"></canvas>
<script type="text/javascript" src="effects.js"></script>
</body>
</html>