<?php
$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("SELECT username FROM login_serial WHERE serial='%s';",
        mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    mysql_close($mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        $username = $row['username'];
    }
} else {
    header('Location: login.html');
}
?>

<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <script type="text/javascript" src="js/event.js"></script>

    <title>预览我的排版 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">预览我的排版</h1>
    <?php
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM users WHERE username='%s';", $username);
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    mysql_close($mysql);
    ?>
    <p class="page_desc">每个大类下按“时间”先后排序</p>

    <p class="page_desc">“详细信息”和“软文网址”不受推送影响</p>

    <p class="page_desc">如对排版有疑问请速联系管理员</p>
</div>
<div class="page_body">
    <?php

    if (date('N', time()) != 7) {
        $week_st = date('y-m-d 00:00:00', strtotime('next week', time()));
        $week_ed = date('y-m-d 00:00:00', strtotime('next week + 8 day', time()));
    } else {
        $week_st = date('y-m-d 00:00:00', strtotime('this week', time()));
        $week_ed = date('y-m-d 00:00:00', strtotime('this week + 8 day', time()));
    }
    $category_name_cn = array('人文', '科学', '艺术', '社科与金融', '比赛与活动', '其它');
    $category_name_en = array('culture', 'science', 'art', 'finance', 'activity', 'others');
    $category_cnt = 7;
    $order_id = 1;
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $category_id_bias = 0;
    $event_arr = array();
    for ($i = 0; $i < $category_cnt; $i++) {
        print_article($order_id, $i);
    }

    function add_week(&$date, $row_date)
    {
        $week_arr = array('', '周一', '周二', '周三', '周四', '周五', '周六', '周日');
        $week_str = '（' . $week_arr[date('N', strtotime($row_date))] . '）';
        $pos = strpos($date, ' ');
        if ($pos == false) return;
        $date = substr($date, 0, $pos) . $week_str . substr($date, $pos + 1, strlen($date) - $pos);
    }

    function print_title($index, $category_name_cn)
    {
        $html = '<section><section style="border: 0px; margin-top: 0.8em; margin-bottom: 0.5em; box-sizing: border-box;">' .
            '<section style="display: inline-block; padding-right: 2px; padding-bottom: 2px; padding-left: 2px; box-sizing: border-box; border-bottom-width: 2px; border-bottom-style: solid; border-color: #FF6666; line-height: 1; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255);">' .
            '<section style="display: inline-block; padding: 0.3em 0.4em; min-width: 1.8em; min-height: 1.6em; border-radius: 80% 100% 90% 20%; line-height: 1; font-size: 1em; font-family: inherit; box-sizing: border-box; word-wrap: break-word !important; background-color: #FF6666;">';
        $html .= sprintf('<section style="box-sizing: border-box;">​%d</section>', $index);
        $html .= '</section><span style="display: inline-block; margin-left: 0.4em; max-width: 100%; color: #FF6666; line-height: 1.4; font-size: 1em; word-wrap: break-word !important; box-sizing: border-box;"><span style="max-width: 100%; font-size: 1em; font-family: inherit; font-weight: bolder; text-decoration: inherit; color: #FF6666; word-wrap: break-word !important; box-sizing: border-box;">';
        $html .= sprintf('<section style="box-sizing: border-box;">%s</section>', $category_name_cn);
        $html .= '</span></span></section><section style="width: 0px; height: 0px; clear: both;"></section></section>';
        echo $html;
    }

    function print_events(&$html, &$res, &$order_id, $update_next_week)
    {

        global $week_ed, $week_st, $event_arr;

        while ($row = mysql_fetch_assoc($res)) {

            $date = date('n月j日 H:i', strtotime($row['date']));
            add_week($date, $row['date']);
            if ($row['date_type'] == "date_ed") {
                $date .= ' 截止';
            }
            if ($row['register'] == 1) {
                $date .= ' 需报名';
            }

            $html .= '<li>';
            $html .= sprintf('<p style="font-size: 16px;"><strong>%s', $row['title']);
            if ($row['confirm']) {
                $html .= '<span style="color: #0099CC">（文末报名）</span>';
            }
            if ($row['notification'] == 1) {
                $html .= '&nbsp;<span style="text-align: center; padding: 0px;line-height: 16px; margin: 0px;width: 16px; display: inline-block; border-top-left-radius: 50%; border-top-right-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;height: 16px;background-color: #0099CC; color: rgb(255, 255, 255);">i</span>';
            }
            $html .= '</strong></p>';
            if (strlen($row['speaker']) > 0) {
                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【嘉宾】%s</p>', $row['speaker']);
            }

            $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【时间】%s</p>', $date);
            $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【地点】%s</p>', $row['location']);
            if ($row['hostname'] != '') {
                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['hostname']);
            } else {
                if ($row['username'] != 'fdubot') {
                    $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
                }
            }
            $html .= '</p></li><br>';

            $event_arr[] = $row['event_id'];

            $order_id++;
        }
    }

    function print_article(&$order_id, $category_id)
    {

        global $category_id_bias, $category_name_cn, $category_name_en, $week_st, $week_ed, $mysql, $update_next_week, $username;
        $query = sprintf("SELECT * FROM event_info AS i NATURAL JOIN users LEFT JOIN event_registration_common AS c ON i.event_id=c.event_id
                WHERE category='%s' AND username='%s' AND
                ((date_type='date_st' AND date>='%s' AND date<'%s') OR
                 (date_type='date_ed' AND date>='%s') OR
                 (register=TRUE AND register_date_type='date_st' AND date>='%s') OR
                 (register=TRUE AND register_date_type='date_ed' AND register_date>='%s')) ORDER BY date;",
            $category_name_en[$category_id], $username, $week_st, $week_ed, $week_st, $week_st, $week_st);

        $res = mysql_query($query, $mysql);
        if (!mysql_num_rows($res)) {
            $category_id_bias++;
            return;
        } else {
            $index = $category_id - $category_id_bias + 1;
            print_title($index, $category_name_cn[$category_id]);
        }
        $html = sprintf('<ol start="%d">', $order_id);

        print_events($html, $res, $order_id, $update_next_week);

        $html .= '</ol></section>';
        echo $html;
    }

    ?>
    <br>

    <p class="page_desc">用户回复编号后显示的详细信息</p>
    <br>
    <?php

    $html = '<section><ol style="list-style-type: decimal; padding-left: 35px;">';

    for ($i = 0; $i < count($event_arr); $i++) {

        $query = sprintf('SELECT * FROM event_info WHERE event_id="%s";', $event_arr[$i]);
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);

        $content = '【' . $row['title'] . '】<br>';
        $details = '';
        if ($row['register'] == 1) {
            if ($row['register_date_type'] == 'date_st') {
                $details = '【报名时间】即刻起，先到先得<br>';
            } else if ($row['register_date_type'] == 'date_ed') {
                $details = '【报名截止时间】';
                $details .= date('n月j日 H:i<br>', strtotime($row['register_date']));
            }
        }
        if ($row['details'] != null && $row['details'] != '') {
            $details .= nl2br($row['details']);
        } else {
            $details .= '喵～信息都已经交代完整啦，祝您参加活动愉快哦～';
        }
        $content .= $details;

        if ($row['propa_url'] != null && $row['propa_url'] != '') {
            $url = 'fdutopia.lyq.me/t.php?t=' . $row['short_url'];
            $content .= '<br>【软文网址】' . $url;
        }

        $html .= '<li><p style="font-size: 13.5px;">' . $content . '</p></li>';
    }

    $html .= '</ol></section>';
    echo $html;

    mysql_close($mysql);

    ?>
    <br>

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="manager.php#m/page_events">返回活动管理</a>
    </div>
</div>
<br>
</body>
</html>
