<?php
$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("select username from login_serial where serial='%s';",
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
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>预览我的排版 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">预览我的排版</h1>
    <?php
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("select * from users where username='%s';", $username);
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    mysql_close($mysql);
    ?>
    <p class="page_desc">每个大类下按“时间”先后排序</p>
    <p class="page_desc">推送生成后，除了“详细信息”之外编辑无效</p>
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
        $category_name_cn = array('人文与社科', '科学', '艺术', '金融', '体育','娱乐', '其它');
        $category_name_en = array('culture', 'science', 'art', 'finance', 'sport', 'entertainment', 'others');
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

        function add_week(&$date, $row_date) {
            $week_arr = array('', '周一', '周二', '周三', '周四', '周五', '周六', '周日');
            $week_str = '（' . $week_arr[date('N', strtotime($row_date))] . '）';
            $pos = strpos($date, ' ');
            if ($pos == false) return;
            $date = substr($date, 0, $pos) . $week_str .substr($date, $pos+1, strlen($date) - $pos);
        }

        function print_title($index, $category_name_cn) {
            $html = '<section><section style="border: 0px; margin-top: 0.8em; margin-bottom: 0.5em; box-sizing: border-box;">' .
                '<section style="display: inline-block; padding-right: 2px; padding-bottom: 2px; padding-left: 2px; box-sizing: border-box; border-bottom-width: 2px; border-bottom-style: solid; border-color: #FF6666; line-height: 1; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255);">' .
                '<section style="display: inline-block; padding: 0.3em 0.4em; min-width: 1.8em; min-height: 1.6em; border-radius: 80% 100% 90% 20%; line-height: 1; font-size: 1em; font-family: inherit; box-sizing: border-box; word-wrap: break-word !important; background-color: #FF6666;">';
            $html .= sprintf('<section style="box-sizing: border-box;">​%d</section>', $index);
            $html .= '</section><span style="display: inline-block; margin-left: 0.4em; max-width: 100%; color: #FF6666; line-height: 1.4; font-size: 1em; word-wrap: break-word !important; box-sizing: border-box;"><span style="max-width: 100%; font-size: 1em; font-family: inherit; font-weight: bolder; text-decoration: inherit; color: #FF6666; word-wrap: break-word !important; box-sizing: border-box;">';
            $html .= sprintf('<section style="box-sizing: border-box;">%s</section>', $category_name_cn);
            $html .= '</span></span></section><section style="width: 0px; height: 0px; clear: both;"></section></section>';
            echo $html;
        }

        function print_events(&$html, &$res, &$order_id, $update_next_week) {

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
                if ($row['notification'] == 1) {
                    $html .= '&nbsp;<span style="text-align: center; padding: 0px;line-height: 16px; margin: 0px;width: 16px; display: inline-block; border-top-left-radius: 50%; border-top-right-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;height: 16px;background-color: #0099CC; color: rgb(255, 255, 255);">i</span>';
                }
                $html .= '</strong></p>';
                if (strlen($row['speaker']) > 0) {
                    $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【嘉宾】%s</p>', $row['speaker']);
                }

                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【时间】%s</p>', $date);
                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【地点】%s</p>', $row['location']);
                if ($row['username'] != 'fdubot') {
                    $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s', $row['fullname']);
                }
                $html .= '</p></li><br>';

                $event_arr[] = $row['event_id'];

                $order_id++;
            }
        }

        function print_article(&$order_id, $category_id) {

            global $category_id_bias, $category_name_cn, $category_name_en, $week_st, $week_ed, $mysql, $update_next_week, $username;
            $query = sprintf("select * from event_info natural join users where publish=1 and category='%s' and username='%s' and
                ((date_type='date_st' and date>='%s' and date<'%s') or
                 (date_type='date_ed' and date>='%s') or
                 (register_date_type='date_st' and register_date>='%s' and register_date<'%s') or
                 (register_date_type='date_ed' and register_date>='%s')) order by date;",
                $category_name_en[$category_id], $username, $week_st, $week_ed, $week_st, $week_st, $week_ed, $week_st);

            $res = mysql_query($query, $mysql);
            if (!mysql_num_rows($res)) {
                $category_id_bias++;
                return;
            } else {
                $index = $category_id-$category_id_bias+1;
                print_title($index, $category_name_cn[$category_id]);
            }
            $html = sprintf('<ol style="list-style-type: decimal; padding-left: 35px;" start="%d">', $order_id);

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

            $query = sprintf('select * from event_info where event_id=%s;', $event_arr[$i]);
            $res = mysql_query($query, $mysql);
            $row = mysql_fetch_assoc($res);

            $content = '【' . $row['title'] . '】';
            $details = '';
            if ($row['register'] == 1) {
                if ($row['register_date_type'] == 'date_st') {
                    $details = '报名开始时间是';
                    $details .= date('n月j日 H:i ', strtotime($row['register_date']));
                } else if ($row['register_date_type'] == 'date_ed') {
                    $details = '报名截止时间是';
                    $details .= date('n月j日 H:i ', strtotime($row['register_date']));
                }
            }
            if ($row['details'] != null && $row['details'] != '') {
                $details .= $row['details'];
            } else {
                $details .= '喵～信息都已经交代完整啦，祝您参加活动愉快哦～';
            }
            $content .= $details;

            $html .= '<li><p style="font-size: 13.5px;">' . $content . '</p></li>';
        }

        $html .= '</ol></section>';
        echo $html;

    ?>
    <br>
    <p class="page_desc">阅读原文中显示的上周回顾</p>
    <br>
    <?php

        if (date('N', time()) != 7) {
            $last_week_st = date('y-m-d 00:00:00', strtotime('this week', time()));
        } else {
            $last_week_st = date('y-m-d 00:00:00', strtotime('last week', time()));
        }

        $html = '<section><ol style="list-style-type: decimal; padding-left: 35px;">';

        $query = sprintf('select * from published_event natural join event_info where published_date="%s" and username="%s" and review_url is not null order by order_id;',
            $last_week_st, $username);
        $res = mysql_query($query, $mysql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($row['review_url'] != null && $row['review_url'] != '') {
                $url = '';
                if (strtolower(substr($row['review_url'], 0, 8)) == 'https://' or
                    strtolower(substr($row['review_url'], 0, 7) == 'http://')) {
                    $url = $row['review_url'];
                } else {
                    $url = 'http://' . $row['review_url'];
                }
                $html .= sprintf('<li><a href="%s" style="font-size: 16px; color: black;"><strong>%s</strong></a></li>', $url, $row[title]);
                if ($row['username'] != 'fdubot') {
                    $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s', $row['fullname']);
                }
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
