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

<?php

function print_header() {
    $html = '<section><p style="text-align: center;"><span style="font-size: 14px;">这是关于复旦收集与分享的平台</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">社团的招新将在每周日晚上准时推送</span></p>';
    $html .= '<br><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br></section>';
    echo $html;
}

function print_article($mysql) {

    $query = 'select * from recruit_info natural join users where publish=1 order by edit_time desc;';
    $res = mysql_query($query, $mysql);
    $html = '<section><ol style="list-style-type: decimal;" class=" list-paddingleft-2">';
    while ($row = mysql_fetch_assoc($res)) {

        $date = date('Y年n月j日 H:i:s',strtotime($row['edit_time']));

        $html .= '<li>';
        $html .= sprintf('<p style="font-size: 14px;"><strong>%s</strong></p>', $row['fullname']);
        $html .= sprintf('<p style="font-size: 14px;">%s</p>', $row['details']);
        $html .= sprintf('<p style="font-size: 14px;">更新于%s</p>', $date);
        $html .= '</li>';

    }

    $html .= '</ol><br><br></section>';
    echo $html;
}

function print_footer() {
    $html = '<section><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">FDUTOPIA致力于打造高效的复旦信息分享平台</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">如果你是社长或主办方，快快联系fdutopia@lyq.me发布招新</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">为了信息的可靠性，请说明身份并附上责任人一卡通照片</span></p>';
    $html .= '<br></section>';
    echo $html;

}

$mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
mysql_query("set names 'utf8'");
mysql_select_db("fudan_info");

print_header();
print_article($mysql);
mysql_close($mysql);

print_footer();

?>