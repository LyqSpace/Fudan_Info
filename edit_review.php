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

    <title>编辑一则回顾 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">编辑一则回顾</h1>

    <p class="page_desc">一个英文占一个字符，一个中文占两个字符</p>

    <p class="page_desc">阅读原文中将收录最近二十次活动的回顾</p>
</div>
<div class="page_body">
    <?php

    if (isset($_GET['event_id']) && $_GET['event_id'] != '') {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM event_info WHERE event_id='%s';",
            mysql_real_escape_string($_GET['event_id']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['username'] == $username) {
            ?>
            <form name="edit_review" method="post" onsubmit="return check_review();" action="save_review.php">

                <input style="display: none" name="event_id" value="<?php echo $_GET['event_id']; ?>"/>

                <div class="weui_cells_title">该活动的回顾图文的网址</div>
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                            <textarea class="weui_textarea" id="review_url"
                                      placeholder="各自公众号发布该活动的回顾图文后，在此处粘贴回顾图文的网址，请不要在此处填写其它内容；若没有，则清空此栏"
                                      name="review_url" rows="7"
                                      onkeyup="count('review_url', review_url_cnt, 300);"><?php echo $row['review_url']; ?></textarea>

                            <div class="weui_textarea_counter"><span
                                    id="review_url_cnt"><?php echo strlen($row['review_url']); ?></span>/300
                            </div>
                        </div>
                    </div>
                </div>
                <div class="weui_btn_area">
                    <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" value="保存"/>
                </div>
            </form>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_plain_default" href="manager.php#m/page_events">返回</a>
            </div>
            <div id="error_message"></div>

            <?php
        } else {
            ?>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">访问违规</strong>
                </div>
                <div class="weui_dialog_bd">
                    只能编辑自己活动的回顾！
                </div>
                <div class="weui_dialog_ft">
                    <a href="manager.php#m/page_events" weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php
        }

    } else {
        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">访问违规</strong>
            </div>
            <div class="weui_dialog_bd">
                必须选择一则活动！
            </div>
            <div class="weui_dialog_ft">
                <a href="manager.php#m/page_events" weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<br>
</body>
</html>
