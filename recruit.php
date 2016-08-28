<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/jquery.fullPage.css"/>
    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/fullpage.css"/>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.fullPage.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/register_recruit.js"></script>

    <title>招新报名 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">招新报名表</h1>

    <p class="page_desc">填上基本信息就可以报名啦</p>
</div>

<div class="page_body">

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" pattern="[0-9]*" name="register_recruit_id_tmp"
                       required="required" placeholder="请输入学号或工号" value="<?php echo $_COOKIE['guest_id']; ?>">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" required="required" name="register_recruit_name_tmp"
                       placeholder="请输入姓名" value="<?php echo $_COOKIE['guest_name']; ?>">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">专业</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" required="required" name="register_recruit_major_tmp"
                       placeholder="请输入专业" value="<?php echo $_COOKIE['guest_major']; ?>">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" pattern="[0-9]*" required="required"
                       name="register_recruit_phone_tmp"
                       placeholder="请输入手机号码" value="<?php echo $_COOKIE['guest_phone']; ?>">
            </div>
        </div>
    </div>
    <div class="weui_cells_title">写给社团的话（选填）</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <textarea class="weui_textarea" placeholder="这里可以写下简短自我介绍，对社团的期待等内容"
                          name="register_recruit_message_tmp" rows="2"
                          id="recruit_message"
                          onkeyup="count('recruit_message', recruit_message_cnt, 200);"></textarea>

                <div class="weui_textarea_counter"><span id="recruit_message_cnt">0</span>/200</div>
            </div>
        </div>
    </div>

    <?php
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = "select * from users WHERE username not in ('admin', 'fdubot', 'test1', 'test2') group by user_category";
    $res = mysql_query($query, $mysql);

    ?>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell weui_cell_select weui_select_after">
            <div class="weui_cell_hd">
                <label class="weui_label">大类</label>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <select class="weui_select select_no_padding" name="select_clubs_category"
                        id="select_clubs_category" onchange="change_clubs_category();">
                    <?php
                    $options = '';
                    $clubs_category_cnt = 0;
                    while ($row = mysql_fetch_assoc($res)) {
                        $options .= '<option value="' . $clubs_category_cnt . '">' . $row['user_category'] . '</option>';
                        $clubs_category_cnt++;
                    }
                    echo $options;
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div id="clubs_category_list">

        <?php
        $clubs_category_list = mysql_query($query, $mysql);
        $clubs_category_cnt = 0;

        while ($clubs_category = mysql_fetch_assoc($clubs_category_list)) {

            $query = sprintf("SELECT * FROM users natural join recruit_info_common WHERE user_category='%s' AND
                username not in ('admin', 'fdubot', 'test1', 'test2') ;",
                $clubs_category['user_category']);
            //echo $query;
            $clubs_list = mysql_query($query, $mysql);

            ?>
            <div id="<?php echo 'clubs_category_' . $clubs_category_cnt ?>" <?php
            if ($clubs_category_cnt > 0) {
                echo 'style="display: none"';
            }
            $clubs_category_cnt++;
            ?>>
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell weui_cell_select weui_select_after">
                        <div class="weui_cell_hd">
                            <label for="" class="weui_label">社团</label>
                        </div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <select class="weui_select select_no_padding" id="select_club_<?php echo $clubs_category_cnt;?>"
                                    name="select_club"
                                    onchange="change_club(this, <?php echo $clubs_category_cnt; ?>);">
                                <?php
                                $options = '';
                                while ($club_info = mysql_fetch_assoc($clubs_list)) {
                                    $options .= '<option value="' . $club_info['username'] . '">' . $club_info['fullname'] . '</option>';
                                }
                                echo $options;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <?php

                $clubs_list = mysql_query($query, $mysql);
                $club_cnt = 0;

                while ($club_info = mysql_fetch_assoc($clubs_list)) {
                    ?>
                    <div id="<?php echo 'club_' . $club_cnt; ?>" <?php
                    if ($date_cnt > 0) {
                        echo 'style="display: none"';
                    }
                    $club_cnt++;
                    ?>>
                        <div class="weui_cells_title">社团概况</div>
                        <article class="club_recruit_article">
                            <p><?php echo $club_info['details'];?></p>
                        </article>
                        <form name="edit_register_recruit" method="post" onsubmit="return check_regsiter_recruit();"
                              action="register_events_save.php">

                            <input name="username" style="display: none;" />
                            <input name="register_recruit_id" style="display: none;"/>
                            <input name="register_recruit_name" style="display: none;"/>
                            <input name="register_recruit_major" style="display: none;"/>
                            <input name="register_recruit_phone" style="display: none;"/>
                            <textarea name="register_recruit_message" style="display:none;"></textarea>

                            <div class="weui_cells_title">勾选想参加的活动</div>
                            <div class="weui_cells weui_cells_checkbox">
                                <?php
                                    $query = sprintf("SELECT * FROM recruit_info_activities WHERE username='%s';", $club_info['username']);
                                    //echo $query;
                                    $activity_list = mysql_query($query, $mysql);
                                    $activity_cnt = 0;
                                    while ($activity_info = mysql_fetch_assoc($activity_list)) {
                                        $activity_cnt++;
                                        ?>
                                        <label class="weui_cell weui_check_label">
                                            <div class="weui_cell_hd">
                                                <input type="checkbox" class="weui_check" name="activity_<?php echo $activity_cnt; ?>">
                                                <i class="weui_icon_checked"></i>
                                            </div>
                                            <div class="weui_cell_bd weui_cell_primary">
                                                <p>【名称】<?php echo $activity_info['activity_name']; ?></p>
                                                <p>【时间】<?php echo $activity_info['activity_date']; ?></p>
                                                <p>【地点】<?php echo $activity_info['activity_location']; ?></p>
                                                <p>【简介】<?php echo $activity_info['activity_details']; ?></p>
                                            </div>
                                        </label>
                                <?php
                                    }
                                ?>
                            </div>

                            <div class="weui_cells weui_cells_form">
                                <div class="weui_cell weui_cell_switch">
                                    <div class="weui_cell_hd weui_cell_primary">在此设备上记住我</div>
                                    <div class="weui_cell_ft">
                                        <input class="weui_switch" name="remember" id="remember_register_recruit_<?php echo $club_info['username'];?>"
                                               type="checkbox" checked="checked"/>
                                    </div>
                                </div>
                            </div>

                            <div class="weui_btn_area">
                                <input name="save" type="submit" value="报名" class="weui_btn weui_btn_plain_primary"/>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="register_recruit_history.php">查找社团报名记录</a>
    </div>
    <div id="error_message"></div>

    <br>
    <hr>
    <?php
    if (isset($_COOKIE['guest_id']) && isset($_COOKIE['guest_name']) && isset($_COOKIE['guest_phone'])) {
        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM recruit_list NATURAL JOIN event_registration_date NATURAL JOIN event_info NATURAL JOIN users
                                              WHERE registration_id='%s' OR registration_name='%s' OR registration_phone='%s' ORDER BY register_time DESC;",
            $_COOKIE['guest_id'], $_COOKIE['guest_name'], $_COOKIE['guest_phone']);
        //echo $query;
        $res = mysql_query($query, $mysql);

        if (mysql_num_rows($res)) {
            ?>
            <article class="weui_article">
                <div class="section_box">
                    <div class="section_header">
                        <span class="section_body">活动报名历史</span>
                    </div>
                </div>
                <p class="page_desc">【学号】 <?php echo $_COOKIE['guest_id']; ?></p>

                <p class="page_desc">【姓名】 <?php echo $_COOKIE['guest_name']; ?></p>

                <p class="page_desc">【手机】 <?php echo $_COOKIE['guest_phone']; ?></p>

                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell">
                        <table class="dataintable">
                            <tbody>
                            <tr>
                                <th>入场码</th>
                                <th>活动信息</th>
                            </tr>
                            <?php
                            while ($row = mysql_fetch_assoc($res)) {
                                $event_date = date('n月j日 H:i', strtotime($row['event_date']));
                                ?>
                                <tr>
                                    <td><?php echo $row['registration_user_serial']; ?></td>
                                    <td>
                                        <ul>
                                            <?php
                                            echo "<li>【名称】" . $row['title'] . "</li>";
                                            echo "<li>【地点】" . $row['location'] . "</li>";
                                            echo "<li>【场次】" . $event_date . "</li>";
                                            echo "<li>【票数】" . $row['ticket_num'] . "</li>";
                                            if ($row['hostname'] != '') {
                                                echo "<li>【主办方】" . $row['hostname'] . "</li>";
                                            } else if ($row['username'] != 'fdubot') {
                                                echo "<li>【主办方】" . $row['fullname'] . "</li>";
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
            <?php
        } else {
            echo '123';
        }
        mysql_close($mysql);
    }
    ?>
</div>
</body>
</html>