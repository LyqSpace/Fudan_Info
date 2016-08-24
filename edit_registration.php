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
    <script type="text/javascript" src="js/registration.js"></script>

    <title>编辑报名表 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">报名表</h1>

    <p class="page_desc">报名表收集的信息有<span id="info_green">学号、姓名、专业、手机</span></p>
    <p class="page_desc">报名表将显示在每期推送的阅读原文中</p>
</div>
<div class="page_body">
    <?php

    if (isset($_GET['event_id']) && $_GET['event_id'] != '') {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM event_info AS i LEFT JOIN event_registration_common AS c ON i.event_id=c.event_id
            WHERE i.event_id='%s';",
            mysql_real_escape_string($_GET['event_id']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['username'] == $username) {

            $query = sprintf("SELECT * FROM event_registration_common NATURAL JOIN event_info WHERE event_id='%s';",
                mysql_real_escape_string($_GET['event_id']));
            $res = mysql_query($query, $mysql);
            $row = mysql_fetch_assoc($res);
            $confirm = $row['confirm'];

            if ($confirm) {
                ?>
                <form name="edit_registration" method="post" onsubmit="return reedit_registration(this.submited);"
                      action="">

                    <input style="display: none" name="event_id" value="<?php echo $_GET['event_id']; ?>"/>

                    <article class="weui_article">
                        <div class="section_box">
                            <div class="section_header">
                                <span class="section_body">基本信息</span>
                            </div>
                        </div>
                        <ul>
                            <?php
                            $register_date = '【报名开始时间】';
                            if ($row['register_date_type'] == 'date_ed') {
                                $register_date = '【报名结束时间】';
                            }
                            if ($row['register_date'] == '' || $row['register_date'] == null || $row['register_date'] == '0000-00-00 00:00:00') {
                                $register_date .= '即可起，先到先得';
                            } else {
                                $register_date .= $row['register_date'];
                            }

                            ?>

                            <li><?php echo $register_date; ?></li>
                            <li><?php echo '【票/人】' . $row['ticket_per_person']; ?></li>
                        </ul>
                        <div class="section_box">
                            <div class="section_header">
                                <span class="section_body">场次信息</span>
                            </div>
                        </div>
                        <ol style="padding-left: 8px;">
                            <?php
                            $query = sprintf("SELECT * FROM event_registration_date WHERE event_id='%s';",
                                mysql_real_escape_string($_GET['event_id']));
                            $res = mysql_query($query, $mysql);
                            while ($row = mysql_fetch_assoc($res)) {
                                ?>
                                <li>
                                    <p>【时间】<?php echo $row['event_date']; ?></p>

                                    <p>【票数】<?php echo $row['ticket_count']; ?></p>

                                    <p>【已报名】<?php echo $row['register_count']; ?></>
                                </li>
                                <?php
                            }
                            ?>
                        </ol>
                    </article>
                    <div class="weui_btn_area">
                        <input class="weui_btn weui_btn_plain_primary" id="btn_info_red" name="delete" type="submit"
                               onclick="this.form.submited=this.name" value="删除报名表及报名记录"/>
                    </div>
                </form>
                <?php
            } else {

                $query = sprintf("SELECT * FROM event_info AS i LEFT JOIN event_registration_common AS c ON i.event_id=c.event_id
                    WHERE i.event_id='%s';", mysql_real_escape_string($_GET['event_id']));
                $res = mysql_query($query, $mysql);
                $row = mysql_fetch_assoc($res);

                ?>
                <form name="edit_registration" method="post" onsubmit="return reedit_registration(this.submited);"
                      action="">

                    <input style="display: none" name="event_id" value="<?php echo $_GET['event_id']; ?>"/>

                    <div class="weui_cells weui_cells_form">
                        <!--是否需要报名-->
                        <div class="weui_cell weui_cell_switch">
                            <div class="weui_cell_hd weui_cell_primary">是否需要提前取票/报名</div>
                            <div class="weui_cell_ft">
                                <input class="weui_switch" name="register" type="checkbox"
                                       onclick="show_register_date()" <?php
                                if ($row['register'] == 1) {
                                    echo 'checked="checked"';
                                }
                                ?>/>
                            </div>
                        </div>

                        <div id="register_date_form" style="display:none">
                            <!--报名时间-->
                            <div class="weui_cell">
                                <div class="weui_cell_hd cell_hd_date_type">
                                    <select class="weui_select select_no_padding" name="register_date_type">
                                        <?php
                                        $options =
                                            '<option value="date_st">报名开始时间</option>' .
                                            '<option value="date_ed">报名截止时间</option>';
                                        $register_date_type = strlen($row['register_date_type']) > 0 ? $row['register_date_type'] : 'date_st';
                                        $pos = strpos($options, $register_date_type);
                                        $part1 = substr($options, 0, $pos - 7);
                                        $part2 = substr($options, $pos - 7, strlen($options) - $pos + 7);
                                        $options = $part1 . 'selected ' . $part2;
                                        echo $options;
                                        ?>
                                    </select>
                                </div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" name="register_date" type="datetime-local" value="<?php
                                    $pos = strpos($row['register_date'], " ");
                                    $date = substr($row['register_date'], 0, $pos) . "T" . substr($row['register_date'], $pos + 1, strlen($row['register_date']) - $pos - 4);
                                    echo $date;
                                    ?>"/>
                                </div>
                            </div>
                            <div class="weui_cells_tips">"报名开始时间" 不填表示即可起，先到先得</div>
                            <div class="weui_cells_tips">"报名截止时间" 表示报名持续到该时间为止</div>


                            <?php
                            $query = sprintf("SELECT * FROM event_registration_common WHERE event_id='%s';",
                                mysql_real_escape_string($_GET['event_id']));
                            $res = mysql_query($query, $mysql);
                            $row = mysql_fetch_assoc($res);
                            ?>
                            <!--报名表-->

                            <div class="weui_cell">
                                <div class="weui_cell_hd"><label class="weui_label">票/人</label></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" type="number" required="required" name="ticket_per_person"
                                           pattern="[0-9]*" placeholder="每位用户最多能取几张票" value="<?php
                                    echo $row['ticket_per_person'];
                                    ?>">
                                </div>
                            </div>
                            <div id="date_list">
                                <?php
                                $query = sprintf("SELECT * FROM event_registration_date WHERE event_id='%s';",
                                    mysql_real_escape_string($_GET['event_id']));
                                $res = mysql_query($query, $mysql);
                                $row = mysql_fetch_assoc($res);
                                $cnt = 0;
                                while (true) {
                                    $cnt++;
                                    ?>
                                    <div id="<?php echo "date_" . $cnt; ?>">
                                        <div class="weui_cell">
                                            <div class="weui_cell_hd">
                                                <label class="weui_label">场次<?php echo $cnt; ?></label>
                                            </div>
                                            <div class="weui_cell_bd weui_cell_primary">
                                                <input class="weui_input" name="<?php echo "date_" . $cnt; ?>"
                                                       type="datetime-local" required="required" value="<?php
                                                $pos = strpos($row['event_date'], " ");
                                                $date = substr($row['event_date'], 0, $pos) . "T" . substr($row['event_date'], $pos + 1, strlen($row['event_date']) - $pos - 4);
                                                echo $date;
                                                ?>"/>
                                            </div>
                                        </div>
                                        <div class="weui_cell">
                                            <div class="weui_cell_hd"><label class="weui_label">票数</label></div>
                                            <div class="weui_cell_bd weui_cell_primary">
                                                <input class="weui_input" type="number" required="required"
                                                       name="<?php echo "ticket_count_" . $cnt; ?>" pattern="[0-9]*"
                                                       placeholder="可以通过此方式报名的总人数" value="<?php
                                                echo $row['ticket_count'];
                                                ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $row = mysql_fetch_assoc($res);
                                    if ($row == null) break;
                                }
                                ?>
                            </div>
                            <div class="info_box" style="margin-top: 10px">
                                <a class="weui_btn weui_btn_mini info_left" id="btn_info_red"
                                   onclick="del_date();">减少场次</a>
                                <a class="weui_btn weui_btn_mini info_midright" id="btn_info_green"
                                   onclick="add_date();">增加场次</a>
                            </div>
                            <!--是否发布-->
                            <div class="weui_cell weui_cell_switch">
                                <div class="weui_cell_hd weui_cell_primary">是否发布，发布后不可修改</div>
                                <div class="weui_cell_ft">
                                    <input class="weui_switch" name="confirm" type="checkbox" <?php
                                    if ($confirm == 1) {
                                        echo 'checked="checked"';
                                    }
                                    ?>/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="weui_btn_area">
                        <input class="weui_btn weui_btn_plain_primary" name="save" type="submit"
                               onclick="this.form.submited=this.name" value="保存"/>
                        <input class="weui_btn weui_btn_plain_primary" id="btn_info_red" name="delete" type="submit"
                               onclick="this.form.submited=this.name" value="删除报名表及报名记录"/>
                    </div>
                </form>
                <?php
            }
            ?>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_plain_default" href="javascript:history.back();">返回</a>
            </div>
            <div id="error_message"></div>
            <div id="confirm_message"></div>

            <?php
        } else {
            ?>
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd">
                    <strong class="weui_dialog_title">访问违规</strong>
                </div>
                <div class="weui_dialog_bd">
                    只能编辑自己活动的报名表！
                </div>
                <div class="weui_dialog_ft">
                    <a href="index.php" weui_btn_dialog primary">确定</a>
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
                <a href="index.php" weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<br>
</body>
</html>