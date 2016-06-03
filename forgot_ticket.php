<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="js/forgot_ticket.js"></script>
    <title>找回入场码 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <!--    <div class="logo_img"></div>-->
    <h1 class="page_title">找回入场码</h1>
    <p class="page_desc">填上以下任意基本信息查询</p>
</div>

<div class="page_body">

    <form name="forgot_ticket" method="post" onsubmit="return check_forgot_ticket();" action="forgot_ticket.php">

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" pattern="[0-9]*" name="registration_id" placeholder="请输入学号或工号">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" name="registration_name" placeholder="请输入姓名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" pattern="[0-9]*" name="registration_phone" placeholder="请输入手机号码">
                </div>
            </div>
        </div>
        <div class="weui_btn_area">
            <input name="search" type="submit" value="查询" class="weui_btn weui_btn_plain_primary" />
        </div>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="register.php">返回报名表</a>
        </div>

    </form>

    <br>
    <?php
        if (isset($_POST['registration_id']) || isset($_POST['registration_name']) || isset($_POST['registration_phone'])) {

            $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
            mysql_query("set names 'utf8'");
            mysql_select_db("fudan_info");

            $query = sprintf("select * from event_register_list natural join event_date natural join event_info natural join users
                where register_id='%s' or register_name='%s' or register_phone='%s';",
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
            if (isset($_POST['registration_id'])) {
                echo '<p class="page_desc>【卡号】' . $_POST['registration_id'] . '</p>';
            }
            if (isset($_POST['registration_name'])) {
                echo '<p class="page_desc>【姓名】' . $_POST['registration_name'] . '</p>';
            }
            if (isset($_POST['registration_phone'])) {
                echo '<p class="page_desc>【手机】' . $_POST['registration_phone'] . '</p>';
            }
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
                    <td><?php echo $row['register_serial'];?></td>
                    <td>
                        <ul>
                            <?php
                            echo "<li>【活动】". $row['title'] ."</li>";
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
    <hr>
    <br>
    <p class="page_desc">素心无用，自由分享</p>
    <p class="page_desc">分享复旦内有生命力的讲座活动</p>
    <p class="page_desc">守望复旦人澎湃不息的赤子之心</p>
    <br>
    <p class="page_desc">如果你喜欢我们，欢迎关注公众号FDUTOPIA</p>
    <div class="qrcode_img"></div>
    <br>

</div>
</body>
</html>