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
        if ($row['username'] != 'admin') {
            header('Location: login.html');
        }
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

    <title>维护用户数据 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">维护用户数据</h1>
</div>
<div class="page_body">
    <?php

    if (isset($_POST['username']) && $_POST['username'] != '') {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        if (isset($_POST['search'])) {
            $query = sprintf("SELECT * FROM users WHERE username='%s';",
                mysql_real_escape_string($_POST['username']));
            $res = mysql_query($query, $mysql);
            ?>
            <div id="dialog">
                <div class="weui_mask"></div>
                <div class="weui_dialog">
                    <div class="weui_dialog_hd">
                        <strong class="weui_dialog_title">查询结果</strong>
                    </div>
                    <div class="weui_dialog_bd">
                        <?php
                        if (!mysql_num_rows($res)) {
                            echo '该用户不存在!';
                        } else {
                            $row = mysql_fetch_assoc($res);
                            printf('用户名 : %s<br>全称 : %s<br>邮件 : %s<br>活动可发布次数 : %s<br>招新可发布次数 : %s',
                                $row['username'], $row['fullname'], $row['email'], $row['event_limit'], $row['recruit_limit']);
                        }
                        ?>
                    </div>
                    <div class="weui_dialog_ft">
                        <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
                    </div>
                </div>
            </div>
            <?php

        } else if (isset($_POST['insert'])) {

            $query = sprintf("INSERT INTO users VALUE ('%s', '%s', '%s', '%s', '%s', '%s', '%s');",
                mysql_real_escape_string($_POST['username']),
                mysql_real_escape_string($_POST['fullname']),
                mysql_real_escape_string($_POST['email']),
                mysql_real_escape_string(md5($_POST['password'])),
                mysql_real_escape_string($_POST['event_limit']),
                mysql_real_escape_string($_POST['recruit_limit']),
                mysql_real_escape_string($_POST['category']));
            $res = mysql_query($query, $mysql);

            ?>
            <div id="dialog">
                <div class="weui_mask"></div>
                <div class="weui_dialog">
                    <div class="weui_dialog_hd">
                        <strong class="weui_dialog_title">新建结果</strong>
                    </div>
                    <div class="weui_dialog_bd">
                        <?php
                        if ($res != false) {
                            echo '新建用户成功!';
                        } else {
                            echo '新建用户失败!';
                        }
                        ?>
                    </div>
                    <div class="weui_dialog_ft">
                        <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
                    </div>
                </div>
            </div>
            <?php

        } else if (isset($_POST['update'])) {

            $update_res = '';

            if (isset($_POST['fullname']) && $_POST['fullname'] != '') {
                $query = sprintf("UPDATE users SET fullname='%s' WHERE username='%s';",
                    mysql_real_escape_string($_POST['fullname']),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新全称成功<br>';
                } else {
                    $update_res .= '更新全称失败<br>';
                }
            }

            if (isset($_POST['email']) && $_POST['email'] != '') {
                $query = sprintf("UPDATE users SET email='%s' WHERE username='%s';",
                    mysql_real_escape_string($_POST['email']),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新邮箱成功<br>';
                } else {
                    $update_res .= '更新邮箱失败<br>';
                }
            }

            if (isset($_POST['password']) && $_POST['password'] != '') {
                $query = sprintf("UPDATE users SET password='%s' WHERE username='%s';",
                    mysql_real_escape_string(md5($_POST['password'])),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新密码成功<br>';
                } else {
                    $update_res .= '更新密码失败<br>';
                }
            }

            if (isset($_POST['event_limit']) && $_POST['event_limit'] != '') {
                $query = sprintf("UPDATE users SET event_limit='%s' WHERE username='%s';",
                    mysql_real_escape_string($_POST['event_limit']),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新活动可用次数成功<br>';
                } else {
                    $update_res .= '更新活动可用次数失败<br>';
                }
            }
            if (isset($_POST['recruit_limit']) && $_POST['recruit_limit'] != '') {
                $query = sprintf("UPDATE users SET recruit_limit='%s' WHERE username='%s';",
                    mysql_real_escape_string($_POST['recruit_limit']),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新招新可用次数成功<br>';
                } else {
                    $update_res .= '更新招新可用次数失败<br>';
                }
            }
            if (isset($_POST['category']) && $_POST['category'] != '') {
                $query = sprintf("UPDATE users SET user_category='%s' WHERE username='%s';",
                    mysql_real_escape_string($_POST['category']),
                    mysql_real_escape_string($_POST['username']));
                $res = mysql_query($query, $mysql);
                if ($res) {
                    $update_res .= '更新大类成功<br>';
                } else {
                    $update_res .= '更新大类失败<br>';
                }
            }

            ?>
            <div id="dialog">
                <div class="weui_mask"></div>
                <div class="weui_dialog">
                    <div class="weui_dialog_hd">
                        <strong class="weui_dialog_title">更新结果</strong>
                    </div>
                    <div class="weui_dialog_bd">
                        <?php
                        echo $update_res;
                        ?>
                    </div>
                    <div class="weui_dialog_ft">
                        <a href="javascript:history.back();" class="weui_btn_dialog primary">确定</a>
                    </div>
                </div>
            </div>
            <?php
        }

        mysql_close($mysql);
    }
    ?>

    <form name="update_users" method="post" action="admin_user.php">
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell weui_cell_warn">
                <div class="weui_cell_hd">
                    <label class="weui_label">用户名</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="username" type="text" placeholder="请输入用户名"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">全称</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="fullname" type="text" placeholder="请输入主办方中文全称"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">邮箱</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="email" type="text" placeholder="请输入联系人的邮箱"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cells_hd">
                    <label class="weui_label">密码</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="password" type="text" placeholder="请输入密码"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cells_hd">
                    <label class="weui_label">活动</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="event_limit" type="number" placeholder="请输入数字，一周发布活动的次数"/>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cells_hd">
                    <label class="weui_label">招新</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="recruit_limit" type="number" placeholder="请输入数字，一周发布招新的次数"/>
                </div>
            </div>
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    <label class="weui_label">大类</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select select_no_padding" name="category">
                        <option value="书院团学联">书院团学联</option>
                        <option value="人文历史类">人文历史类</option>
                        <option value="科学技术类">科学技术类</option>
                        <option value="社科经管类">社科经管类</option>
                        <option value="体育运动类">体育运动类</option>
                        <option value="国际交流类">国际交流类</option>
                        <option value="能力拓展类">能力拓展类</option>
                        <option value="艺术类">艺术类</option>
                        <option value="公益类">公益类</option>
                        <option value="棋牌类">棋牌类</option>
                        <option value="兴趣类">兴趣类</option>
                        <option value="枫林社团">枫林社团</option>
                        <option value="江湾社团">江湾社团</option>
                        <option value="张江社团">张江社团</option>
                        <option selected value="其它">其它</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="weui_btn_area">
            <input class="weui_btn weui_btn_plain_primary" name="search" type="submit" value="查询一个用户"/>
        </div>
        <div class="weui_btn_area">
            <input class="weui_btn weui_btn_plain_primary" name="insert" type="submit" value="新建一个新的用户"/>
        </div>
        <div class="weui_btn_area">
            <input class="weui_btn weui_btn_plain_primary" name="update" type="submit" value="更新一个旧的用户"/>
        </div>
    </form>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="admin.php" ">返回</a>
    </div>
</div>
</body>
</html>
