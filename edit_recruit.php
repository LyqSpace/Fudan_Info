<?php

header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');


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

    <script type="text/javascript" src="js/recruit.js"></script>

    <title>编辑一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">编辑一则招新</h1>

    <p class="page_desc">一个英文占一个字符，一个中文占两个字符</p>

    <p class="page_desc">内容可多次编辑，但若推送已发布，则修改无效</p>

    <p class="page_desc">招新信息只在<span class="text_warn">每学期前四周</span>被推送</p>
</div>
<div class="page_body">
    <?php

    function count_str($str)
    {
        $len = 0;
        preg_match_all("/./us", $str, $matchs);
        foreach ($matchs[0] as $p) {
            $len += preg_match('#^[' . chr(0x1) . '-' . chr(0xff) . ']$#', $p) ? 1 : 2;
        }
        return $len;
    }

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM recruit_info_common WHERE username='%s';", $username);
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);

    ?>
    <form name="edit_recruit" method="post" onsubmit="return reedit_recruit(this.submited);" action="">

        <input style="display: none" name="username" value="<?php echo $username; ?>"/>

        <div class="weui_cells_title">社团概况</div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="details_text" placeholder="字数有限，请输入社团最与众不同，最吸引人的特色，社团名无需填写。此栏不可为空。"
                                  name="details" rows="4"
                                  onkeyup="count('details_text', details_cnt, 100);"><?php echo $row['details']; ?></textarea>

                    <div class="weui_textarea_counter"><span
                            id="details_cnt"><?php echo count_str($row['details']); ?></span>/100
                    </div>
                </div>
            </div>
        </div>
        <div id="activity_list">
            <?php
            $query = sprintf("SELECT * FROM recruit_info_activities WHERE username='%s';",
                mysql_real_escape_string($username));
            $res = mysql_query($query, $mysql);
            $cnt = 0;
            $row = mysql_fetch_assoc($res);
            while (true) {
                $cnt++;
                ?>
                <div id="<?php echo "activity_" . $cnt; ?>">
                    <?php
                    if ($cnt == 1) {
                        ?>
                        <div class="weui_cells_title">常规/大型活动介绍</div>
                        <?php
                    }
                    ?>
                    <div class="weui_cells weui_cells_form">
                        <div class="weui_cell">
                            <div class="weui_cell_hd">
                                <label class="weui_label">活动<?php echo $cnt; ?></label>
                            </div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" name="<?php echo "activity_name_" . $cnt; ?>" maxlength="30"
                                       type="text" required="required" value="<?php echo $row['activity_name']; ?>"/>
                            </div>
                        </div>
                        <div class="weui_cell">
                            <div class="weui_cell_hd">
                                <label class="weui_label">时间</label>
                            </div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" name="<?php echo "activity_date_" . $cnt; ?>" maxlength="30"
                                       type="text" required="required" value="<?php echo $row['activity_date']; ?>"/>
                            </div>
                        </div>
                        <div class="weui_cell">
                            <div class="weui_cell_hd">
                                <label class="weui_label">地点</label>
                            </div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" name="<?php echo "activity_location_" . $cnt; ?>" type="text"
                                       maxlength="30" required="required"
                                       value="<?php echo $row['activity_location']; ?>"/>
                            </div>
                        </div>
                        <div class="weui_cell">
                            <div class="weui_cell_bd weui_cell_primary">
                                    <textarea class="weui_textarea"
                                              id=<?php echo "activity_details_" . $cnt; ?> placeholder="请输入该常规/大型活动的介绍。此栏不可为空。"
                                              name=<?php echo "activity_details_" . $cnt; ?> rows="3"
                                              required="required"
                                              onkeyup="count('<?php echo 'activity_details_' . $cnt; ?>', <?php echo 'activity_details_cnt_' . $cnt; ?>, 200);"><?php echo $row['activity_details']; ?></textarea>

                                <div class="weui_textarea_counter">
                                    <span
                                        id=<?php echo "activity_details_cnt_" . $cnt; ?>><?php echo count_str($row['activity_details']); ?></span>/200
                                </div>
                            </div>
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
               onclick="del_activity();">减少活动</a>
            <a class="weui_btn weui_btn_mini info_midright" id="btn_info_green"
               onclick="add_activity();">增加活动</a>
        </div>
        <div class="weui_btn_area">
            <input class="weui_btn weui_btn_plain_primary" name="save" type="submit"
                   onclick="this.form.submited=this.name" value="保存"/>
        </div>
        <div class="weui_btn_area">
            <input id="weui_btn_plain_warn" class="weui_btn weui_btn_plain_primary" name="delete" type="submit"
                   onclick="this.form.submited=this.name" value="删除"/>
        </div>
    </form>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="manager.php#page_recruit">返回招新管理</a>
    </div>
    <div id="error_message"></div>
    <div id="confirm_message"></div>

</div>
<br>
</body>
</html>
