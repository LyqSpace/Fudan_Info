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
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <script type="text/javascript" src="functions.js"></script>
    <title>我的历史发布 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">我的历史发布</h1>
    <?php
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM users WHERE username='%s';", $username);
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    mysql_close($mysql);
    ?>
    <p class="page_desc">显示在推送中的全称 : <?php echo $row['fullname']; ?></p>

    <p class="page_desc">预留的联系邮箱 : <?php echo $row['email']; ?></p>

    <p class="page_desc"><span class="info_publish">已发布</span>表示已经或将在推送中公开发布</p>

    <p class="page_desc">如果想使用旧的信息，编辑后请选择【另存为】</p>

    <p class="page_desc" id="info_red">报名表将被放在推送的“阅读原文”中</p>

</div>
<div class="page_body">

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_primary" href="preview.php">预览本周日的活动推送</a>
    </div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_primary" href="edit_event.php">发布一则活动信息</a>
    </div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="index.php">返回主菜单</a>
    </div>
    <article class="weui_article">
        <div class="section_box">
            <div class="section_header">
                <span class="section_num">1</span>
                <span class="section_body">活动信息</span>
            </div>
        </div>

        <section>
            <?php
            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
            mysql_query("set names 'utf8'");
            mysql_select_db("fudan_info");

            $query = sprintf("SELECT * FROM event_info WHERE username='%s' ORDER BY event_id DESC;", $username);
            $res = mysql_query($query, $mysql);
            $event_cnt = 0;
            while ($row = mysql_fetch_assoc($res)) {
                $event_cnt++;
                ?>
                <h3 class="info_title"><?php echo $event_cnt . ". " . $row['title']; ?></h3>
                <div class="info_box">
                    <p class="info_time">
                        <?php
                        if ($row['publish'] == 1) {
                            echo ' <span class="info_publish">已发布</span>';
                        } else {
                            echo ' <span class="text_warn">未发布</span>';
                        }
                        ?></p>
                    <a class="weui_btn weui_btn_mini info_left"
                       href="edit_review.php?event_id=<?php echo $row['event_id']; ?>">回顾</a>
                    <a class="weui_btn weui_btn_mini info_midright"
                       href="edit_registration.php?event_id=<?php echo $row['event_id']; ?>">报名表</a>
                    <a class="weui_btn weui_btn_mini info_midright"
                       href="edit_event.php?event_id=<?php echo $row['event_id']; ?>">编辑</a>
                </div>
                <?php
            }
            mysql_close($mysql);
            ?>
        </section>

        <div class="section_box">
            <div class="section_header">
                <span class="section_num">2</span>
                <span class="section_body">招新信息</span>
            </div>
        </div>

        <section>
            <?php
            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
            mysql_query("set names 'utf8'");
            mysql_select_db("fudan_info");

            $query = sprintf("SELECT * FROM recruit_info WHERE username='%s' ORDER BY recruit_id DESC;", $username);
            $res = mysql_query($query, $mysql);
            $recruit_cnt = 0;
            while ($row = mysql_fetch_assoc($res)) {
                $recruit_cnt++;
                ?>
                <h3 class="info_title"><?php echo $recruit_cnt . ". " . $row['details']; ?></h3>
                <div class="info_box">
                    <p class="info_time">最后编辑于<?php
                        echo substr($row['edit_time'], 0, 10);
                        if ($row['publish'] == 1) {
                            echo ' <span class="info_publish">已发布</span>';
                        } else {
                            echo ' <span>未发布</span>';
                        }
                        ?></p>
                    <a class="weui_btn weui_btn_mini info_edit_single"
                       href="edit_recruit.php?recruit_id=<?php echo $row['recruit_id']; ?>">编辑</a>
                </div>
                <?php
            }
            mysql_close($mysql);
            ?>
        </section>
    </article>
</div>
<br>
</body>
</html>
