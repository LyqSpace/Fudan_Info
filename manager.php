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
    <link rel="stylesheet" type="text/css" href="css/movePage.css"/>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/movePage.js"></script>
    <script type="text/javascript" src="js/manager.js"></script>

    <title>主办方 | FDUTOPIA</title>
</head>

<body ontouchstart>

<div class="tabbar">
    <div class="weui_tab">
        <div class="weui_tab_bd" id="fullpage">
            <div class="mp-section fp-auto-height">
                <div class="mp-slide" data-anchor="page_events">
                    <div class="page_header">
                        <h1 class="page_title">活动管理</h1>

                        <p class="page_desc"><span class="info_publish">已发布</span>表示已经或将在推送中公开发布</p>

                        <p class="page_desc">如果想使用旧的信息，编辑后请选择【另存为】</p>

                        <p class="page_desc" id="info_red">报名表将被放在推送的“阅读原文”中</p>

                    </div>
                    <div class="page_body">

                        <div class="weui_btn_area">
                            <a class="weui_btn weui_btn_plain_primary" href="edit_event.php">发布一则活动信息</a>
                            <a class="weui_btn weui_btn_plain_primary" href="registration_list.php">查看我的票务系统<?php
                                $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                                mysql_query("set names 'utf8'");
                                mysql_select_db("fudan_info");

                                $query = sprintf("SELECT count(*) AS cnt FROM event_registration_list AS l LEFT JOIN event_registration_date AS d
                                    ON l.registration_serial=d.registration_serial LEFT JOIN event_info AS i
                                    ON d.event_id=i.event_id WHERE l.new_increment=TRUE AND i.username='%s';", $username);
                                $res = mysql_query($query, $mysql);
                                $row = mysql_fetch_assoc($res);
                                if ($row['cnt'] > 0) {
                                    echo '(' . $row['cnt'] . ')';
                                }
                                ?></a>
                            <a class="weui_btn weui_btn_plain_primary" href="preview.php">预览本周日的活动推送</a>
                        </div>

                        <article class="weui_article">
                            <div class="section_box">
                                <div class="section_header">
                                    <!--                                    <span class="section_num">1</span>-->
                                    <span class="section_body">历史活动信息</span>
                                </div>
                            </div>

                            <section>
                                <?php
                                $query = sprintf("SELECT title, i.event_id AS event_id, confirm FROM event_info AS i LEFT JOIN event_registration_common AS c ON i.event_id=c.event_id
                                    WHERE username='%s' ORDER BY i.event_id DESC;", $username);
                                $res = mysql_query($query, $mysql);
                                $event_cnt = 0;
                                while ($row = mysql_fetch_assoc($res)) {
                                    $event_cnt++;
                                    ?>
                                    <h3 class="info_title"><?php echo $event_cnt . ". " . $row['title']; ?></h3>
                                    <div class="info_box">
                                        <p class="info_time">
                                            <?php
                                            if ($row['confirm'] == 1) {
                                                echo '<span class="info_publish">报名表已发布</span>';
                                            } else {
                                                echo '<span class="text_warn">报名表未发布</span>';
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
                        </article>
                    </div>
                </div>

                <div class="mp-slide" data-anchor="page_recruits">
                    <div class="page_header">
                        <h1 class="page_title">招新管理</h1>

                        <p class="page_desc">招新信息只在每学期前四周被推送</p>

                        <p class="page_desc" id="info_red">招新表将被放在推送的“阅读原文”中</p>

                    </div>
                    <div class="page_body">

                        <div class="weui_btn_area">
                            <a class="weui_btn weui_btn_plain_primary" href="edit_recruit.php">编辑我的招新信息</a>
                            <a class="weui_btn weui_btn_plain_primary" href="manage_recruit_list.php">查看我的招新系统<?php
                                $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                                mysql_query("set names 'utf8'");
                                mysql_select_db("fudan_info");

                                $query = sprintf("SELECT count(*) AS cnt FROM recruit_list WHERE new_increment=TRUE AND username='%s';", $username);
                                $res = mysql_query($query, $mysql);
                                $row = mysql_fetch_assoc($res);
                                if ($row['cnt'] > 0) {
                                    echo '(' . $row['cnt'] . ')';
                                }
                                ?></a>
                        </div>
                        <?php

                        $query = sprintf("SELECT * FROM recruit_info_common WHERE username='%s';", $username);
                        $res = mysql_query($query, $mysql);
                        if ($row = mysql_fetch_assoc($res)) {
                        ?>
                        <article class="weui_article">
                            <div class="section_box">
                                <div class="section_header">
                                    <span class="section_body">招新信息</span>
                                </div>
                            </div>
                            <p><?php echo $row['details']; ?></p>

                            <div class="section_box">
                                <div class="section_header">
                                    <span class="section_body">活动介绍</span>
                                </div>
                            </div>
                            <ol style="padding-left: 8px;">
                                <?php
                                $query = sprintf("SELECT * FROM recruit_info_activities WHERE username='%s';", $username);
                                $res = mysql_query($query, $mysql);
                                while ($row = mysql_fetch_assoc($res)) {
                                    ?>
                                    <li>
                                        <p><?php echo '【名称】' . $row['activity_name']; ?></p>

                                        <p><?php echo '【时间】' . $row['activity_date']; ?></p>

                                        <p><?php echo '【地点】' . $row['activity_location']; ?></p>

                                        <p><?php echo '【详细】' . $row['activity_details']; ?></p>
                                    </li>
                                    <?php
                                }
                                }
                                mysql_close($mysql);
                                ?>
                            </ol>
                            </section>
                        </article>
                    </div>
                </div>

                <div class="mp-slide" data-anchor="page_settings">
                    <div class="page_header">
                        <h1 class="page_title">我的信息</h1>

                        <p class="page_desc">社团/主办方的全称将显示在推送中</p>

                        <p class="page_desc">联系邮箱不可为空</p>
                    </div>
                    <div class="page_body">
                        <article class="weui_article">
                            <?php
                            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                            mysql_query("set names 'utf8'");
                            mysql_select_db("fudan_info");

                            $query = sprintf("SELECT * FROM users WHERE username='%s';", $username);
                            $res = mysql_query($query, $mysql);
                            if ($row = mysql_fetch_assoc($res)) {
                                ?>
                                <ul>
                                    <li><?php echo '【账号】' . $row['username']; ?></li>
                                    <li><?php echo '【全称】' . $row['fullname']; ?></li>
                                    <li><?php echo '【邮箱】' . $row['email']; ?></li>
                                    <li><?php echo '【大类】' . $row['user_category']; ?></li>
                                </ul>
                                <?php
                            }
                            ?>
                        </article>
                        <div class="weui_btn_area">
                            <a class="weui_btn weui_btn_plain_primary" href="edit_profile.php">修改我的基本信息</a>
                            <a class="weui_btn weui_btn_plain_default" onclick="logout()">退出</a>
                        </div>
                        <br>
                        <hr/>
                        <br>

                        <div class="page_desc">
                            <p>【致谢】</p>

                            <p>所有在这里精心维护和更新信息的主办方们</p>
                            <br>

                            <p>【特别致谢】</p>

                            <p>我的动力和信仰：悦儿</p>

                            <p>最完美的前端支持：戍爷</p>

                            <p>超热心的小伙伴：越越</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="weui_tabbar">
            <a href="#" class="weui_tabbar_item" id="btn_events">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_button.png">
                </div>
                <p class="weui_tabbar_label">活动管理</p>
            </a>
            <a href="#" class="weui_tabbar_item" id="btn_recruits">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_button.png">
                </div>
                <p class="weui_tabbar_label">招新管理</p>
            </a>
            <a href="#" class="weui_tabbar_item" id="btn_settings">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_cell.png">
                </div>
                <p class="weui_tabbar_label">设置</p>
            </a>
        </div>
    </div>
</div>
</body>

</html>
