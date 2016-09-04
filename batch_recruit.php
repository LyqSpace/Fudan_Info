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
        if ($row['username'] != 'admin') {
            header('Location: login.html');
        }
    }
} else {
    header('Location: login.html');
}
?>

<?php

function print_header()
{
    $html = '<section><p style="text-align: center;"><span style="font-size: 14px;">这是复旦乌托邦</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">让美好遇见欣赏，让有趣告别雪藏</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">每周日晚上见～</span></p>';
    $html .= '<br><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p></section>';
    echo $html;
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


function print_article($mysql, $category_name)
{

    global $user_category_point;

    $query = sprintf("SELECT * FROM recruit_info_common NATURAL JOIN users WHERE user_category='%s' ORDER BY edit_time;", $category_name);
    $res = mysql_query($query, $mysql);
    if (!mysql_num_rows($res)) return;

    $user_category_point++;
    print_title($user_category_point, $category_name);
    $html = '<ol style="list-style-type: decimal;" class=" list-paddingleft-2">';
    while ($row = mysql_fetch_assoc($res)) {

        $html .= '<li>';
        $html .= sprintf('<p style="font-size: 16px;"><strong>%s</strong></p>', $row['fullname']);
        $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">%s</p>', $row['details']);
        $html .= '</li><br>';

    }

    $html .= '</ol>';
    echo $html;
}


function print_footer()
{
    $html = '<br><section><p style="text-align: center;"><span style="font-size: 15px;">戳<span style="color: rgb(92, 137, 183);">阅读原文</span>可报名社团招新</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">FDUTOPIA致力于打造高效的复旦信息分享平台</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">如果喜欢我们，欢迎分享给小伙伴～</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">©2016 FDUTOPIA. All rights reserved.</span></p>';
    $html .= '<br></section>';
    echo $html;
}

$mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
mysql_query("set names 'utf8'");
mysql_select_db("fudan_info");

print_header();

$user_category = array('书院团学联', '人文历史类', '科学技术类', '社科经管类', '体育运动类', '国际交流类', '能力拓展类',
    '艺术类', '公益类', '棋牌类', '兴趣类', '枫林社团', '江湾社团', '张江社团', '其它');
$user_category_cnt = 15;
$user_category_point = 0;
for ($i = 0; $i < $user_category_cnt; $i++) {
    print_article($mysql, $user_category[$i]);
}

mysql_close($mysql);

print_footer();

?>
