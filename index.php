<?php
header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');

function count_str($str)
{
    $len = 0;
    preg_match_all("/./us", $str, $matchs);
    foreach ($matchs[0] as $p) {
        $len += preg_match('#^[' . chr(0x1) . '-' . chr(0xff) . ']$#', $p) ? 1 : 2;
    }
//	$len = mb_strwidth($str, 'utf-8'); // not used for chinese quote
    preg_match_all('[\r\n]', $str, $matches);
    $len -= count($matches[0]);
    return $len;
}

?>
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
    <script type="text/javascript" src="js/register_events.js"></script>

    <title>FDUTOPIA</title>
</head>

<body ontouchstart>

<div class="tabbar">
    <div class="weui_tab">
        <div class="weui_tab_bd" id="fullpage">
            <div class="section fp-auto-height">
                <div class="slide" data-anchor="page_guest_events">
                    <div class="page_header">
                        <h1 class="page_title">报名/取票表</h1>

                        <p class="page_desc">填上基本信息就可以报名啦</p>
                    </div>

                    <div class="page_body">

                        <div class="weui_cells weui_cells_form">
                            <div class="weui_cell">
                                <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" type="number" pattern="[0-9]*" name="registration_id_tmp"
                                           required="required" placeholder="请输入学号或工号"
                                           value="<?php echo $_COOKIE['guest_id']; ?>">
                                </div>
                            </div>
                            <div class="weui_cell">
                                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" type="text" required="required"
                                           name="registration_name_tmp"
                                           placeholder="请输入姓名" value="<?php echo $_COOKIE['guest_name']; ?>">
                                </div>
                            </div>
                            <div class="weui_cell">
                                <div class="weui_cell_hd"><label class="weui_label">专业</label></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" type="text" required="required"
                                           name="registration_major_tmp"
                                           placeholder="请输入专业" value="<?php echo $_COOKIE['guest_major']; ?>">
                                </div>
                            </div>
                            <div class="weui_cell">
                                <div class="weui_cell_hd"><label class="weui_label">手机</label></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <input class="weui_input" type="number" pattern="[0-9]*" required="required"
                                           name="registration_phone_tmp"
                                           placeholder="请输入手机号码" value="<?php echo $_COOKIE['guest_phone']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="weui_cells_title">留言板（选填）</div>
                        <div class="weui_cells weui_cells_form">
                            <div class="weui_cell">
                                <div class="weui_cell_bd weui_cell_primary">
                                    <textarea class="weui_textarea" placeholder="写给给主办方的话"
                                              name="registration_message_tmp" rows="2"
                                              id="registration_message"
                                              onkeyup="count('registration_message', registration_message_cnt, 200);"></textarea>

                                    <div class="weui_textarea_counter"><span id="registration_message_cnt">0</span>/200
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $now_time = date('Y-m-d H:i:s', time());

                        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                        mysql_query("set names 'utf8'");
                        mysql_select_db("fudan_info");

                        $query = sprintf("SELECT * FROM event_info NATURAL JOIN event_registration_date NATURAL JOIN event_registration_common WHERE
                                          confirm=TRUE AND ((register_date_type='date_st' AND '%s' < event_date) OR (register_date_type='date_ed' AND '%s' < register_date))
                                          GROUP BY event_id ORDER BY event_date;",
                            $now_time, $now_time);
                        //echo $query;
                        $res = mysql_query($query, $mysql);

                        ?>
                        <div class="weui_cells weui_cells_form">
                            <div class="weui_cell weui_cell_select weui_select_after">
                                <div class="weui_cell_hd">
                                    <label class="weui_label">活动</label>
                                </div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <select class="weui_select select_no_padding" name="event_id" id="select_event"
                                            onchange="change_event();">
                                        <?php
                                        $options = '';
                                        while ($row = mysql_fetch_assoc($res)) {
                                            $options .= '<option value="' . $row['event_id'] . '">' . $row['title'] . '</option>';
                                        }
                                        echo $options;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="event_list">

                            <?php
                            $event_list = mysql_query($query, $mysql);
                            $event_cnt = 0;

                            while ($event_info = mysql_fetch_assoc($event_list)) {
                                //echo $event_info['event_id'];
                                $query = sprintf("SELECT * FROM users NATURAL JOIN event_info NATURAL JOIN event_registration_common AS r RIGHT JOIN event_registration_date AS d ON r.event_id=d.event_id WHERE r.event_id='%s';",
                                    $event_info['event_id']);
                                //echo $query;
                                $registration_list = mysql_query($query, $mysql);

                                ?>
                                <div id="<?php echo 'event_id_' . $event_info['event_id'] ?>" <?php
                                if ($event_cnt > 0) {
                                    echo 'style="display: none"';
                                }
                                $event_cnt++;
                                ?>>
                                    <div class="weui_cells weui_cells_form">
                                        <div class="weui_cell weui_cell_select weui_select_after">
                                            <div class="weui_cell_hd">
                                                <label for="" class="weui_label">场次</label>
                                            </div>
                                            <div class="weui_cell_bd weui_cell_primary">
                                                <select class="weui_select select_no_padding" id="select_registration_<?php echo $event_info['event_id'];?>"
                                                        name="select_registration"
                                                        onchange="change_date(this, <?php echo $event_info['event_id']; ?>);">
                                                    <?php
                                                    $options = '';
                                                    while ($registration_info = mysql_fetch_assoc($registration_list)) {
                                                        $event_date = date('n月j日 H:i', strtotime($registration_info['event_date']));
                                                        $options .= '<option value="' . $registration_info['registration_serial'] . '">' . $event_date . '</option>';
                                                    }
                                                    echo $options;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($event_info['speaker'] != '') {
                                        ?>
                                        <div class="weui_cells_tips">
                                            【嘉宾】<?php echo $event_info['speaker']; ?></div>
                                        <?php
                                    }
                                    if ($event_info['location'] != '') {
                                        ?>
                                        <div class="weui_cells_tips">
                                            【地点】<?php echo $event_info['location']; ?></div>
                                        <?php
                                    }
                                    if ($event_info['hostname'] != '') {
                                        ?>
                                        <div class="weui_cells_tips">
                                            【主办方】<?php echo $event_info['hostname']; ?></div>
                                        <?php
                                    } else {
                                        if ($event_info['username'] != 'fdubot') {
                                            ?>
                                            <div class="weui_cells_tips">
                                                【主办方】<?php echo $event_info['fullname']; ?></div>
                                            <?php
                                        }
                                    }

                                    $query = sprintf("SELECT * FROM event_registration_date NATURAL JOIN event_registration_common WHERE event_id='%s';",
                                        $event_info['event_id']);
                                    //echo $query;
                                    $registration_list = mysql_query($query, $mysql);
                                    $date_cnt = 0;

                                    while ($registration_info = mysql_fetch_assoc($registration_list)) {
                                        ?>
                                        <div name="registration_date_<?php echo $event_info['event_id']; ?>"
                                             id="<?php echo 'registration_serial_' . $registration_info['registration_serial'] ?>" <?php
                                        if ($date_cnt > 0) {
                                            echo 'style="display: none"';
                                        }
                                        $date_cnt++;
                                        $ticket_remain = $registration_info['ticket_count'] - $registration_info['register_count'];
                                        ?>>
                                            <form name="edit_register" method="post" onsubmit="return check_register();"
                                                  action="register_events_save.php">

                                                <input name="registration_serial" style="display: none;" />
                                                <input name="registration_id" style="display: none;"/>
                                                <input name="registration_name" style="display: none;"/>
                                                <input name="registration_major" style="display: none;"/>
                                                <input name="registration_phone" style="display: none;"/>
                                                <textarea name="registration_message" style="display:none;"></textarea>

                                                <div class="weui_cells_title">
                                                    剩余票数<span
                                                        id="info_green"><?php echo $ticket_remain; ?></span>张，每人最多可取<span
                                                        id="info_green"><?php echo $registration_info['ticket_per_person']; ?></span>张
                                                </div>
                                                <div class="weui_cells weui_cells_form">
                                                    <div class="weui_cell">
                                                        <div class="weui_cell_hd"><label class="weui_label">票数</label></div>
                                                        <div class="weui_cell_bd weui_cell_primary">
                                                            <input class="weui_input" type="number" pattern="[0-9]*"
                                                                   max="<?php echo $event_info['ticket_per_person']; ?>" min="1"
                                                                   value="1" required="required" name="ticket_num"
                                                                   placeholder="请输入要预约的票数">
                                                        </div>
                                                    </div>
                                                    <div class="weui_cell weui_cell_switch">
                                                        <div class="weui_cell_hd weui_cell_primary">在此设备上记住我</div>
                                                        <div class="weui_cell_ft">
                                                            <input class="weui_switch" name="remember" id="remember_register_event_<?php echo $registration_info['registration_serial'];?>"
                                                                   type="checkbox" checked="checked"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="weui_btn_area">
                                                    <input name="save" type="submit" value="报名" <?php if ($ticket_remain==0) echo ' disabled=\"disabled\"' ?>
                                                           class="weui_btn weui_btn_plain_primary<?php if ($ticket_remain==0) echo ' disabled'; ?>"/>
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
                            <a class="weui_btn weui_btn_plain_default" href="register_events_forgotten.php">找回入场码</a>
                        </div>
                        <div id="error_message"></div>

                        <?php
                        if (isset($_COOKIE['guest_id']) && isset($_COOKIE['guest_name']) && isset($_COOKIE['guest_phone'])) {
                            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                            mysql_query("set names 'utf8'");
                            mysql_select_db("fudan_info");

                            $query = sprintf("SELECT * FROM event_registration_list NATURAL JOIN event_registration_date NATURAL JOIN event_info NATURAL JOIN users
                                              WHERE registration_id='%s' and registration_name='%s' and registration_phone='%s' ORDER BY register_time DESC;",
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
                                    <div class="weui_cells_title">
                                        <p>【学号】 <?php echo $_COOKIE['guest_id']; ?></p>
                                        <p>【姓名】 <?php echo $_COOKIE['guest_name']; ?></p>
                                        <p>【手机】 <?php echo $_COOKIE['guest_phone']; ?></p>
                                    </div>

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
                                    <div class="weui_btn_area">
                                        <a class="weui_btn weui_btn_plain_default" href="#/page_guest_events" id="btn_clear_cookie_events">清除本地记录</a>
                                    </div>
                                </article>
                                <?php
                            }
                            mysql_close($mysql);
                        }
                        ?>
                    </div>
                </div>
                <div class="slide" data-anchor="page_guest_recruits">
                </div>
                <div class="slide" data-anchor="page_guest_review">
                </div>
            </div>
        </div>

        <div class="weui_tabbar">
            <a href="#" class="weui_tabbar_item weui_bar_item_on" id="btn_events">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_button.png">
                </div>
                <p class="weui_tabbar_label">活动报名</p>
            </a>
            <a href="#" class="weui_tabbar_item" id="btn_recruits">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_button.png">
                </div>
                <p class="weui_tabbar_label">招新报名</p>
            </a>
            <a href="#" class="weui_tabbar_item" id="btn_settings">
                <div class="weui_tabbar_icon">
                    <img src="./images/icon_nav_cell.png">
                </div>
                <p class="weui_tabbar_label">精彩回顾</p>
            </a>
        </div>
    </div>
</div>
</body>

</html>