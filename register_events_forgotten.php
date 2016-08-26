<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <script type="text/javascript" src="js/register_events.js"></script>

    <title>找回活动入场码 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">找回活动入场码</h1>
    <p class="page_desc">填写以下基本信息查询</p>
</div>

<div class="page_body">

    <form name="forgot_ticket" method="post" onsubmit="return check_register_forgotten();" action="register_events_forgotten.php">

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" required="required" pattern="[0-9]*" name="registration_id" placeholder="请输入学号或工号">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" required="required" name="registration_name" placeholder="请输入姓名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" required="required" pattern="[0-9]*" name="registration_phone" placeholder="请输入手机号码">
                </div>
            </div>
        </div>
        <div class="weui_btn_area">
            <input name="search" type="submit" value="查询" class="weui_btn weui_btn_plain_primary" />
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="index.php">返回报名系统</a>
        </div>

    </form>

    <br>
    <?php
        if ((isset($_POST['registration_id']) && $_POST['registration_id'] != '') ||
            (isset($_POST['registration_name']) && $_POST['registration_name'] != '') ||
            (isset($_POST['registration_phone']) && $_POST['registration_phone'] != '')) {

            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
            mysql_query("set names 'utf8'");
            mysql_select_db("fudan_info");

            $query = sprintf("select * from event_registration_list natural join event_registration_date natural join event_info natural join users
                where registration_id='%s' or registration_name='%s' or registration_phone='%s';",
                mysql_real_escape_string($_POST['registration_id']),
                mysql_real_escape_string($_POST['registration_name']),
                mysql_real_escape_string($_POST['registration_phone']));
            //echo $query;
            $res = mysql_query($query, $mysql);
            $exist = mysql_num_rows($res);
    ?>
    <hr/>
    <br>
    <p class="page_desc">查询结果</p>
    <?php
        echo '<p class="page_desc">【学号】' . $_POST['registration_id'] . '</p>';
        echo '<p class="page_desc">【姓名】' . $_POST['registration_name'] . '</p>';
        echo '<p class="page_desc">【手机】' . $_POST['registration_phone'] . '</p>';
    ?>
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
                    <td><?php echo $row['registration_user_serial'];?></td>
                    <td>
                        <ul>
                            <?php
                            echo "<li>【名称】". $row['title'] ."</li>";
                            echo "<li>【地点】". $row['location'] ."</li>";
                            echo "<li>【场次】". $event_date ."</li>";
                            echo "<li>【票数】". $row['ticket_num'] ."</li>";
                            if ($row['hostname'] != '') {
                                echo "<li>【主办方】". $row['hostname'] ."</li>";
                            } else if ($row['username'] != 'fdubot') {
                                echo "<li>【主办方】". $row['fullname'] ."</li>";
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
    <?php
        }
    ?>
    <br>
    <br>

</div>
</body>
</html>