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
    <script type="text/javascript" src="js/guest_cookie.js"></script>
    <script type="text/javascript" src="js/register_events.js"></script>
    <script type="text/javascript" src="js/register_recruit.js"></script>
    <script type="text/javascript" src="js/review.js"></script>

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
                                          confirm=TRUE AND register=TRUE AND ((register_date_type='date_st' AND '%s' < event_date) OR (register_date_type='date_ed' AND '%s' < register_date))
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
                                    if ($event_info['details'] != '') {
                                        ?>
                                        <div class="weui_cells_tips">
                                            【介绍】<?php echo $event_info['details']; ?></div>
                                        <?php
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

                                $event_cnt++;

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
                    <div class="page_header">
                        <h1 class="page_title">社团招新表</h1>

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

                        $query = "select * from users WHERE username not in ('admin', 'fdubot') group by user_category";
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
                username not in ('admin', 'fdubot') ;",
                                    $clubs_category['user_category']);
                                //echo $query;
                                $clubs_list = mysql_query($query, $mysql);

                                ?>
                                <div id="<?php echo 'clubs_category_' . $clubs_category_cnt ?>" <?php
                                if ($clubs_category_cnt > 0) {
                                    echo 'style="display: none"';
                                }
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
                                        <div id="<?php echo 'club_' . $club_info['username']; ?>" name="club_category_<?php echo $clubs_category_cnt;?>" <?php
                                        if ($club_cnt > 0) {
                                            echo 'style="display: none"';
                                        }
                                        ?>>
                                            <div class="weui_cells_title">社团概况</div>
                                            <article class="club_recruit_article">
                                                <p><?php echo $club_info['details'];?></p>
                                            </article>
                                            <form name="edit_register_recruit" method="post" onsubmit="return check_regsiter_recruit();"
                                                  action="register_recruit_save.php">

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
                                                        $activity_cnt++;
                                                    }
                                                    ?>
                                                </div>

                                                <div class="weui_cells weui_cells_form">
                                                    <div class="weui_cell weui_cell_switch">
                                                        <div class="weui_cell_hd weui_cell_primary">是否愿意加入管理层</div>
                                                        <div class="weui_cell_ft">
                                                            <input class="weui_switch" name="join_management" id="join_management" type="checkbox"/>
                                                        </div>
                                                    </div>
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
                                        $club_cnt++;
                                    }
                                    ?>
                                </div>
                                <?php
                                $clubs_category_cnt++;
                            }
                            ?>
                        </div>

                        <div class="weui_btn_area">
                            <a class="weui_btn weui_btn_plain_default" href="register_recruit_history.php">查询社团报名记录</a>
                        </div>
                        <div id="error_message"></div>

                        <br>
                        <hr>
                        <?php
                        if (isset($_COOKIE['guest_id']) && isset($_COOKIE['guest_name']) && isset($_COOKIE['guest_phone'])) {
                            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                            mysql_query("set names 'utf8'");
                            mysql_select_db("fudan_info");

                            $query = sprintf("select * from recruit_list natural join users
                where guest_id='%s' and guest_name='%s' and guest_phone='%s' order by recruit_register_time desc;",
                                $_COOKIE['guest_id'], $_COOKIE['guest_name'], $_COOKIE['guest_phone']);
                            //echo $query;
                            $res = mysql_query($query, $mysql);

                            if (mysql_num_rows($res)) {
                                ?>
                                <article class="weui_article">
                                    <div class="section_box">
                                        <div class="section_header">
                                            <span class="section_body">招新报名历史</span>
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
                                                    <th>社团名</th>
                                                    <th>报名信息</th>
                                                </tr>
                                                <?php

                                                while ($row = mysql_fetch_assoc($res)) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['fullname']; ?></td>
                                                        <td>
                                                            <ul>
                                                                <?php
                                                                if ($row['email'] != '') {
                                                                    echo "<li>【官方邮箱】" . $row['email'] . "</li>";
                                                                } else {
                                                                    echo "<li>【官方邮箱】暂无</li>";
                                                                }
                                                                echo "<li>【报名活动】" . $row['recruit_items'] . "</li>";
                                                                $register_date = date('n月j日 H:i', strtotime($row['recruit_register_time']));
                                                                echo "<li>【报名时间】" . $register_date . "</li>";
                                                                if ($row['join_management']) {
                                                                    echo "<li>愿意加入管理层</li>";
                                                                } else {
                                                                    echo "<li>暂不愿意加入管理层</li>";
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
                                        <a class="weui_btn weui_btn_plain_default" href="#/page_guest_recruits" id="btn_clear_cookie_recruits">清除本地记录</a>
                                    </div>
                                </article>
                                <?php
                            }
                            mysql_close($mysql);
                        }
                        ?>
                    </div>
                </div>

                <div class="slide" data-anchor="page_guest_review">
                    <div class="page_header">
                        <h1 class="page_title">往期活动精彩回顾</h1>

                        <p class="page_desc">下面的爪爪冰棍儿，点击标题即刻享用</p>
                    </div>
                    <div class="page_body"><?php

                        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
                        mysql_query("set names 'utf8'");
                        mysql_select_db("fudan_info");

                        $query = "update review_read set count=count+1;";
                        mysql_query($query, $mysql);

                        $cur_date = date('Y-m-d H:i:s', time());
                        $query = sprintf('select * from event_info natural join users where review_url is not null and date<"%s" order by date desc limit 30;', $cur_date);
                        $res = mysql_query($query, $mysql);

                        $html = '';
                        $cnt = 1;
                        while ($row = mysql_fetch_assoc($res)) {

                            if ($row['review_url'] == null || $row['review_url'] == '') continue;

                            $url = '';
                            if (strtolower(substr($row['review_url'], 0, 8)) == 'https://' or
                                strtolower(substr($row['review_url'], 0, 7)) == 'http://') {
                                $url = $row['review_url'];
                            } else {
                                $url = 'http://' . $row['review_url'];
                            }
                            $html .= sprintf('<div class="review_item" id="review_item%s"><ol style="list-style-type: decimal; padding-left: 35px;" start=%d><li>', $cnt-1, $cnt);
                            $html .= sprintf('<a href="%s" style="font-size: 16px; color: black;"><strong>%s</strong></a>', $url, $row['title']);
                            if ($row['hostname'] != '') {
                                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['hostname']);
                            } else if ($row['username'] != 'fdubot') {
                                $html .= sprintf('<p style="font-size: 13.5px; margin-left: -0.75em;">【主办方】%s</p>', $row['fullname']);
                            }
                            $html .= '</li></ol></div>';
                            $cnt++;
                        }

                        if ($cnt == 1) {
                            echo '<p class="page_desc">爪爪冰棒还没做出来，过两天再来看看吧～</p>';
                        } else {
                            echo $html;
                        }

                        ?>
                        <br>

                        <p style="font-size: 14px;color: #888;">阅读 <?php

                            $query = "select * from review_read;";
                            $res = mysql_query($query, $mysql);
                            $row = mysql_fetch_assoc($res);
                            echo $row['count'];
                            mysql_close($mysql);
                            ?></p>
                    </div>
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