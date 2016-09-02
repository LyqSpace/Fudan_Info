<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/register_events.js"></script>
    <script type="text/javascript" src="js/guest_cookie.js"></script>

    <title>活动入场码 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">活动入场码</h1>

    <p class="page_desc">填写以下基本信息查询</p>
</div>

<div class="page_body">

    <form name="forgot_ticket" method="post" onsubmit="return check_register_forgotten();"
          action="register_events_forgotten.php">

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" required="required" pattern="[0-9]*" name="registration_id"
                           placeholder="请输入学号或工号">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" required="required" name="registration_name"
                           placeholder="请输入姓名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" required="required" pattern="[0-9]*"
                           name="registration_phone" placeholder="请输入手机号码">
                </div>
            </div>
            <div class="weui_cell weui_cell_switch">
                <div class="weui_cell_hd weui_cell_primary">在此设备上记住我</div>
                <div class="weui_cell_ft">
                    <input class="weui_switch" name="remember" id="remember" type="checkbox" checked="checked"/>
                </div>
            </div>
        </div>
        <div class="weui_btn_area">
            <input name="search" type="submit" value="查询" class="weui_btn weui_btn_plain_primary"/>
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="index.php#m/page_guest_events">返回活动报名系统</a>
        </div>

    </form>

    <br>
    <?php
    if ((isset($_POST['registration_id']) && $_POST['registration_id'] != '') ||
        (isset($_POST['registration_name']) && $_POST['registration_name'] != '') ||
        (isset($_POST['registration_phone']) && $_POST['registration_phone'] != '')
    ) {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM event_registration_list NATURAL JOIN event_registration_date NATURAL JOIN event_info NATURAL JOIN users
                WHERE registration_id='%s' AND registration_name='%s' AND registration_phone='%s' ORDER BY register_time DESC;",
            mysql_real_escape_string($_POST['registration_id']),
            mysql_real_escape_string($_POST['registration_name']),
            mysql_real_escape_string($_POST['registration_phone']));
        //echo $query;
        $res = mysql_query($query, $mysql);
        $exist = mysql_num_rows($res);
        ?>
        <hr/>
        <article class="weui_article">
            <div class="section_box">
                <div class="section_header">
                    <span class="section_body">活动报名历史</span>
                </div>
            </div>
            <div class="weui_cells_title">
                <p>【学号】 <?php echo $_COOKIE['registration_id']; ?></p>

                <p>【姓名】 <?php echo $_COOKIE['registration_name']; ?></p>

                <p>【手机】 <?php echo $_COOKIE['registration_phone']; ?></p>
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
                <?php
                if (!$exist) {
                    echo "<p class='page_desc'>查询不到您的报名记录，请换个信息重试或重新报名</p>";
                }
                ?>
            </div>
        </article>
        <?php
    }
    ?>
    <br>
    <br>

</div>
</body>
</html>