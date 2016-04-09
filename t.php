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
    <title>跳转到软文 | FDUTOPIA</title>
</head>

<body ontouchstart>

<?php
if (isset($_GET['t']) && $_GET['t'] != "") {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");
    
    $query = sprintf('select * from event_info where short_url="%s";', $_GET['t']);
    $res = mysql_query($query);
    if (mysql_num_rows($res)) {

        $row = mysql_fetch_assoc($res);
        mysql_close($mysql);

        $url = "Location: ";
        if (strtolower(substr($row['review_url'], 0, 8)) == 'https://' or
            strtolower(substr($row['review_url'], 0, 7)) == 'http://') {
            $url .= $row['review_url'];
        } else {
            $url .= 'http://' . $row['review_url'];
        }

        header($url);

    } else {
        mysql_close($mysql);
        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">访问错误</strong>
            </div>
            <div class="weui_dialog_bd">
                跳转页面的打开方式不对～
            </div>
            <div class="weui_dialog_ft">
                <a href="javascript:history.back();" class="weui_btn_dialog primary">返回</a>
            </div>
        </div>
        <?php
    }

} else {
    ?>
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd">
            <strong class="weui_dialog_title">访问错误</strong>
        </div>
        <div class="weui_dialog_bd">
            跳转页面的打开方式不对～
        </div>
        <div class="weui_dialog_ft">
            <a href="javascript:history.back();" class="weui_btn_dialog primary">返回</a>
        </div>
    </div>
    <?php
}
?>
</body>
</html>
