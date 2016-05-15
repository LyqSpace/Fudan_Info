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
        if ($row['username'] != 'admin') {
            header('Location: login.html');
        }
    }
} else {
    header('Location: login.html');
}

$update_next_week = check_update();
if (date('N', time()) != 7) {
    $week_st = date('Y-m-d 00:00:00', strtotime('next week', time()));
    $week_ed = date('Y-m-d 00:00:00', strtotime('next week + 8 day', time()));
} else {
    $week_st = date('Y-m-d 00:00:00', strtotime('this week', time()));
    $week_ed = date('Y-m-d 00:00:00', strtotime('this week + 8 day', time()));
}
$category_name_cn = array('人文', '科学', '艺术', '社科与金融', '比赛与活动', '其它');
$category_name_en = array('culture', 'science', 'art', 'finance', 'activity', 'others');
$category_cnt = 7;
$order_id = 1;
$mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
mysql_query("set names 'utf8'");
mysql_select_db("fudan_info");

if (date('N', time()) != 7) {
    $last_week_st = date('Y-m-d', strtotime('this week', time()));
} else {
    $last_week_st = date('Y-m-d', strtotime('last week', time()));
}

if ($update_next_week) {
    $query = "delete from published_event;";
    mysql_query($query, $mysql);
}

print_header();
$category_id_bias = 0;
for ($i = 0; $i < $category_cnt; $i++) {
    print_article($order_id, $i);
}

mysql_close($mysql);

print_footer();

echo 'http://fdutopia.lyq.me/review.php';

function check_update() {
    $cur_time_week = date('N', time());
    $cur_time_hour = date('H', time());
    if ($cur_time_week == 7 && (24 - $cur_time_hour) <= 6) {
        return true;
    }
    return false;
}

function add_week(&$date, $row_date) {
    $week_arr = array('', '周一', '周二', '周三', '周四', '周五', '周六', '周日');
    $week_str = '（' . $week_arr[date('N', strtotime($row_date))] . '）';
    $pos = strpos($date, ' ');
    if ($pos == false) return;
    $date = substr($date, 0, $pos) . $week_str .substr($date, $pos+1, strlen($date) - $pos);
}

function format_date(&$date_st, &$date_ed, $row_date_st, $row_date_ed) {

    $date_st = date('n月j日 H:i', strtotime($row_date_st));
    $date_ed = date('n月j日 H:i', strtotime($row_date_ed));
    $date_st_pos = strpos($date_st, ' ');
    $date_ed_pos = strpos($date_ed, ' ');
    if (substr($date_st, 0, $date_st_pos) == substr($date_ed, 0, $date_ed_pos)) {
        $date_ed = substr($date_ed, $date_ed_pos+1, strlen($date_ed)-$date_ed_pos-1);
    } else {
        $date_st_pos = strpos($date_st, '月');
        $date_ed_pos = strpos($date_ed, '月');
        if (substr($date_st, 0, $date_st_pos) == substr($date_ed, 0, $date_ed_pos)) {
            $date_ed = substr($date_ed, $date_ed_pos+3, strlen($date_ed)-$date_ed_pos-1);
        }
    }
    add_week($date_st, $row_date_st);
    add_week($date_ed, $row_date_ed);

}

function print_header() {
    $html = '<section><p style="text-align: center;"><span style="font-size: 14px;">复旦乌托邦，素心无用，自由分享</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">每周日晚上见～</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">带有<strong style="text-align: center; white-space: normal; font-size: 14px; line-height: 22.4px;"><span style="font-size: 14px; line-height: 16px; width: 16px; display: inline-block; border-radius: 50%; height: 16px; color: rgb(255, 255, 255); background-color: #0099CC;">i</span></strong>标签的活动</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">在公众号内发送编号可查看报名等详细信息</span></p>';
    $html .= '<br><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p></section>';
    echo $html;
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

    global $week_st;

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
        if ($row['hostname'] != '') {
            $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['hostname']);
        } else {
            if ($row['username'] != 'fdubot') {
                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
            }
        }

        $html .= '</li><br>';

        if ($update_next_week) {
            $query = sprintf('insert into published_event value (%s, %d);', $order_id, $row['event_id']);
            mysql_query($query);
        }

        $order_id++;
    }
}

function print_article(&$order_id, $category_id) {

    global $category_id_bias, $category_name_cn, $category_name_en, $week_st, $week_ed, $mysql, $update_next_week;
    $query = sprintf("select * from event_info natural join users where publish=1 and category='%s' and
        ((date_type='date_st' and date>='%s' and date<'%s') or
         (date_type='date_ed' and date>='%s') or
         (register_date_type='date_st' and register_date>='%s' and register_date<'%s') or
         (register_date_type='date_ed' and register_date>='%s')) order by date;",
        $category_name_en[$category_id], $week_st, $week_ed, $week_st, $week_st, $week_ed, $week_st);

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

function print_footer() {
    $html = '<br><section><p style="text-align: center;"><span style="font-size: 15px;">彩蛋：戳<span style="color: rgb(92, 137, 183);">阅读原文</span>可看往期讲座活动的回顾呦～</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">FDUTOPIA致力于打造高效的复旦信息分享平台</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">如果喜欢我们，欢迎分享给小伙伴～</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">©2016 FDUTOPIA. All rights reserved.</span></p>';
    $html .= '<br></section>';
    echo $html;
}

?>
