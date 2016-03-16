<?php

$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("select username from login_serial where serial='%s';",
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
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>编辑一则招新 | FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">编辑一则招新</h1>
    <p class="page_desc">一个英文占一个字符，一个中文占两个字符</p>
    <p class="page_desc">招新内容必填，主办方名字不必填写</p>
    <p class="page_desc">内容可多次保存/编辑，但若推送已生成，则修改无效</p>
    <p class="page_desc">请务必填写准确，若信息有误将视情况可能被禁言一学期</p>
    <p class="page_desc">招新信息将在<strong class="text_warn">每周五晚八点</strong>整理成一则推送</p>
</div>
<div class="page_body">
    <?php

    if (isset($_GET['recruit_id']) && $_GET['recruit_id'] != '') {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("select * from recruit_info where recruit_id='%s';",
            mysql_real_escape_string($_GET['recruit_id']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['username'] == $username) {
            ?>
            <form name="edit_recruit" method="post" onsubmit="return reedit_recruit(this.submited);" action="">

                <input style="display: none" name="recruit_id" value="<?php echo $_GET['recruit_id'];?>" />

                <div class="weui_cells_title">招新内容</div>
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="details_text" placeholder="请输入详细的社团介绍和招新信息，如部门需求、联系方式等。社团名无需填写，讲会自动补上。此栏不可为空"
                              name="details" rows="7" onkeyup="count('details_text', details_cnt, 300);"><?php echo $row['details'];?></textarea>
                            <div class="weui_textarea_counter"><span id="details_cnt"><?php echo strlen($row['details']);?></span>/300</div>
                        </div>
                    </div>
                </div>
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell weui_cell_switch">
                        <div class="weui_cell_hd weui_cell_primary">是否在周五的推送中公开发布</div>
                        <div class="weui_cell_ft">
                            <input class="weui_switch" name="publish" type="checkbox" <?php
                            if ($row['publish'] == 1) {
                                echo 'checked="checked"';
                            }
                            ?>">
                        </div>
                    </div>
                </div>
                <div class="weui_btn_area">
                    <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" onclick="this.form.submited=this.name" value="保存" />
                </div>
                <div class="weui_btn_area">
                    <input id="weui_btn_plain_warn" class="weui_btn weui_btn_plain_primary" name="delete" type="submit" onclick="this.form.submited=this.name" value="删除" />
                </div>
            </form>
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
                    只能编辑自己的文章!
                </div>
                <div class="weui_dialog_ft">
                    <a href="index.php" weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php
        }

    } else {
        ?>
        <form name="edit_recruit" method="post" onsubmit="return check_recruit();" action="save_recruit.php">
            <div class="weui_cells_title">招新内容</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="details_text" placeholder="请输入详细的社团介绍和招新信息，如部门需求、联系方式等。社团名无需填写，讲会自动补上。此栏不可为空"
                              name="details" rows="7" onkeyup="count('details_text', details_cnt, 300);"></textarea>
                        <div class="weui_textarea_counter"><span id="details_cnt">0</span>/300</div>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">是否在周五的推送中公开发布</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" name="publish" type="checkbox" />
                    </div>
                </div>
            </div>
            <div class="weui_btn_area">
                <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" value="保存" />
            </div>
        </form>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="javascript:history.back();">返回</a>
        </div>
        <div id="error_message"></div>
        <?php
    }
    ?>
</div>
<br>
</body>
</html>
