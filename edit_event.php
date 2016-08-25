<?php

$username = '';
$fullname = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");
    $query = sprintf("SELECT username,fullname FROM login_serial NATURAL JOIN users WHERE serial='%s';",
        mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    mysql_close($mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        $username = $row['username'];
        $fullname = $row['fullname'];
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

    <title>编辑一则活动 | FDUTOPIA</title>
</head>

<body ontouchstart>

<!--    <div id="error_message"></div>-->
<!--    <script type="text/javascript" src="check_html5.js"></script>-->

<div class="page_header">
    <h1 class="page_title">编辑一则活动</h1>

    <p class="page_desc">一个英文占一个字符，一个中文占两个字符</p>

    <p class="page_desc"><span class="text_warn">标题</span>、<span class="text_warn">地点</span>、<span
            class="text_warn">时间</span>和<span class="text_warn">类别</span>必填</p>

    <p class="page_desc">内容可多次编辑，但若推送已发布，则修改无效</p>

    <p class="page_desc">推送将会收录<span class="text_warn">下周一到下下周一</span>的已发布的活动</p>

    <p class="page_desc">如果活动的报名时间在区间内则也会被收录</p>
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
//	$len = mb_strwidth($str, 'utf-8'); // not used for chinese quote
        preg_match_all('[\r\n]', $str, $matches);
        $len -= count($matches[0]);
        return $len;
    }

    if (isset($_GET['event_id']) && $_GET['event_id'] != '') {

        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");

        $query = sprintf("SELECT * FROM event_info WHERE event_id='%s';",
            mysql_real_escape_string($_GET['event_id']));
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        if ($row['username'] == $username) {
            ?>
            <form name="edit_event" method="post" onsubmit="return reedit_event(this.submited);" action="">

                <input style="display: none" name="event_id" value="<?php echo $_GET['event_id']; ?>"/>

                <div class="weui_cells weui_cells_form">
                    <!--标题-->
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label">标题</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" required="required" maxlength="100" name="title"
                                   placeholder="请输入活动标题" value="<?php echo $row['title']; ?>">
                        </div>
                    </div>
                    <!--嘉宾-->
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label">嘉宾</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" maxlength="100" name="speaker"
                                   placeholder="选填，主讲人的简单介绍" value="<?php echo $row['speaker']; ?>">
                        </div>
                    </div>
                    <!--地点-->
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label">地点</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" required="required" maxlength="40" name="location"
                                   placeholder="请输入活动地点" value="<?php echo $row['location']; ?>">
                        </div>
                    </div>
                    <!--主办方-->
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label">主办方</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" maxlength="40" name="hostname"
                                   placeholder="选填，缺省值为我的全称" value="<?php
                            if ($row['hostname'] == '') {
                                if ($username != 'fdubot') {
                                    echo $fullname;
                                }
                            } else {
                                echo $row['hostname'];
                            }
                            ?>">
                        </div>
                    </div>
                    <!--类别-->
                    <div class="weui_cell weui_cell_select weui_select_after">
                        <div class="weui_cell_hd">
                            <label class="weui_label">类别</label>
                        </div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <select class="weui_select select_no_padding" name="category">
                                <?php
                                $options =
                                    '<option value="culture">人文</option>' .
                                    '<option value="science">科学</option>' .
                                    '<option value="art">艺术</option>' .
                                    '<option value="finance">社科与金融</option>' .
                                    '<option value="activity">比赛与活动</option>' .
                                    '<option value="others">其它</option>';

                                $pos = strpos($options, $row['category']);
                                $part1 = substr($options, 0, $pos - 7);
                                $part2 = substr($options, $pos - 7, strlen($options) - $pos + 7);
                                $options = $part1 . 'selected ' . $part2;
                                echo $options;
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--时间-->
                    <div class="weui_cell">
                        <div class="weui_cell_hd ">
                            <select class="weui_select select_no_padding" name="date_type"
                                    onchange="show_register_form()">
                                <?php
                                $options =
                                    '<option value="date_st">开始时间</option>' .
                                    '<option value="date_ed">截止时间</option>';
                                $date_type = strlen($row['date_type']) > 0 ? $row['date_type'] : 'date_st';
                                $pos = strpos($options, $date_type);
                                $part1 = substr($options, 0, $pos - 7);
                                $part2 = substr($options, $pos - 7, strlen($options) - $pos + 7);
                                $options = $part1 . 'selected ' . $part2;
                                echo $options;
                                ?>
                            </select>
                        </div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" name="date" type="datetime-local" value="<?php
                            $pos = strpos($row['date'], " ");
                            $date = substr($row['date'], 0, $pos) . "T" . substr($row['date'], $pos + 1, strlen($row['date']) - $pos - 4);
                            echo $date;
                            ?>"/>
                        </div>
                    </div>
                    <div class="weui_cells_tips">"开始时间" 提供给一般的讲座和活动</div>
                    <div class="weui_cells_tips">"截止时间" 提供给长时间比赛和展览</div>
                    <!--提前报名-->
                    <div id="register_form">
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
                        <!--报名时间-->
                        <div id="register_date_form" style="display:none">
                            <div class="weui_cell">
                                <div class="weui_cell_hd ">
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
                            <div class="weui_cells_tips">【报名时间】和【详细信息】将一起回复给用户</div>
                        </div>
                    </div>
                    <!--是否有详细描述-->
                    <div class="weui_cell weui_cell_switch">
                        <div class="weui_cell_hd weui_cell_primary">是否有详细描述</div>
                        <div class="weui_cell_ft">
                            <input class="weui_switch" name="notification" type="checkbox"
                                   onclick="show_details(details_form)" <?php
                            if ($row['notification'] == 1) {
                                echo 'checked="checked"';
                            }
                            ?>>
                        </div>
                    </div>
                    <div id="details_form" style="display:<?php
                    if ($row['notification'] == 1) {
                        echo 'display';
                    } else {
                        echo 'none';
                    }
                    ?>">
                        <!--详细描述-->
                        <div class="weui_cells_title">详细描述</div>
                        <div class="weui_cell">
                            <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="details_text"
                                  placeholder="请输入主讲人介绍或活动介绍等信息，如果勾选“有详细描述”，则此栏或软文网址不可为空"
                                  name="details" rows="7"
                                  onkeyup="count('details_text', details_cnt, 300);"><?php echo $row['details']; ?></textarea>

                                <div class="weui_textarea_counter"><span
                                        id="details_cnt"><?php echo count_str($row['details']); ?></span>/300
                                </div>
                            </div>
                        </div>
                        <!--软文网址-->
                        <div class="weui_cells_title">软文网址</div>
                        <div class="weui_cell">
                            <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="propa_url" placeholder="选填，如果自己的公众号有宣传此活动的软文，可以把软文网址复制在此处"
                                  name="propa_url" rows="7"
                                  onkeyup="count('propa_url', propa_url_cnt, 600);"><?php echo $row['propa_url']; ?></textarea>

                                <div class="weui_textarea_counter"><span
                                        id="propa_url_cnt"><?php echo count_str($row['propa_url']); ?></span>/600
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="weui_btn_area">
                    <input class="weui_btn weui_btn_plain_primary" name="save" type="submit"
                           onclick="this.form.submited=this.name" value="保存"/>
                </div>
                <div class="weui_btn_area">
                    <input class="weui_btn weui_btn_plain_primary" name="save_as" type="submit"
                           onclick="this.form.submited=this.name" value="另存为一则新的活动"/>
                </div>
                <div class="weui_btn_area">
                    <input id="weui_btn_plain_warn" class="weui_btn weui_btn_plain_primary" name="delete" type="submit"
                           onclick="this.form.submited=this.name" value="删除"/>
                </div>
            </form>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_plain_default" href="manager.php">返回活动管理</a>
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
                    只能编辑自己的活动！
                </div>
                <div class="weui_dialog_ft">
                    <a href="index.php" weui_btn_dialog primary">确定</a>
                </div>
            </div>
            <?php
        }

    } else {
        ?>
        <form name="edit_event" method="post" onsubmit="return reedit_event(this.submited);" action="save_event.php">
            <div class="weui_cells weui_cells_form">
                <!--标题-->
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">标题</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" required="required" maxlength="100" name="title"
                               placeholder="请输入活动标题">
                    </div>
                </div>
                <!--嘉宾-->
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">嘉宾</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" maxlength="100" name="speaker" placeholder="选填，主讲人的简单介绍">
                    </div>
                </div>
                <!--地点-->
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">地点</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" required="required" maxlength="40" name="location"
                               placeholder="请输入活动地点">
                    </div>
                </div>
                <!--主办方-->
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">主办方</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" maxlength="40" name="hostname" placeholder="选填，缺省值为我的全称"
                               value="<?php
                               if ($username != 'fdubot') {
                                   echo $fullname;
                               }
                               ?>">
                    </div>
                </div>
                <!--类别-->
                <div class="weui_cell weui_cell_select weui_select_after">
                    <div class="weui_cell_hd">
                        <label class="weui_label">类别</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select select_no_padding" name="category">
                            <option value="culture">人文</option>
                            <option value="science">科学</option>
                            <option value="art">艺术</option>
                            <option value="finance">社科与金融</option>
                            <option value="activity">比赛与活动</option>
                            <option selected value="others">其它</option>
                        </select>
                    </div>
                </div>
                <!--时间-->
                <div class="weui_cell">
                    <div class="weui_cell_hd ">
                        <select class="weui_select select_no_padding" name="date_type" onchange="show_register_form()">
                            <option selected value="date_st">开始时间</option>
                            <option value="date_ed">截止时间</option>
                        </select>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="date" required type="datetime-local"/>
                    </div>
                </div>
                <div class="weui_cells_tips">"开始时间" 提供给一般的讲座和活动</div>
                <div class="weui_cells_tips">"截止时间" 提供给长时间比赛和展览</div>
                <!--提前报名-->
                <div id="register_form">
                    <div class="weui_cell weui_cell_switch">
                        <div class="weui_cell_hd weui_cell_primary">是否需要提前取票/报名</div>
                        <div class="weui_cell_ft">
                            <input class="weui_switch" name="register" type="checkbox" onclick="show_register_date()"/>
                        </div>
                    </div>
                    <!--报名时间-->
                    <div id="register_date_form" style="display:none">
                        <div class="weui_cell">
                            <div class="weui_cell_hd ">
                                <select class="weui_select select_no_padding" name="register_date_type">
                                    <option selected value="date_st">报名开始时间</option>
                                    <option value="date_ed">报名截止时间</option>
                                </select>
                            </div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" name="register_date" type="datetime-local"/>
                            </div>
                        </div>
                        <div class="weui_cells_tips">"报名开始时间" 不填表示即可起，先到先得</div>
                        <div class="weui_cells_tips">"报名截止时间" 表示报名持续到该时间为止</div>
                        <div class="weui_cells_tips">【报名时间】和【详细信息】将一起回复给用户</div>
                    </div>
                </div>
                <!--是否有详细描述-->
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">是否有详细描述</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" name="notification" type="checkbox"
                               onclick="show_details(details_form)">
                    </div>
                </div>
                <!--详细描述-->
                <div id="details_form" style="display:none;">
                    <div class="weui_cells_title">详细描述</div>
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="details_text"
                                  placeholder="请输入主讲人介绍或活动介绍等信息，如果勾选“有详细描述”，则此栏或软文网址不可为空"
                                  name="details" rows="7" onkeyup="count('details_text', details_cnt, 300);"></textarea>

                            <div class="weui_textarea_counter"><span id="details_cnt">0</span>/300</div>
                        </div>
                    </div>
                    <!--软文网址-->
                    <div class="weui_cells_title">软文网址</div>
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="propa_url" placeholder="选填，如果自己的公众号有宣传此活动的软文，可以把软文网址复制在此处"
                                  name="propa_url" rows="7"
                                  onkeyup="count('propa_url', propa_url_cnt, 600);"></textarea>

                            <div class="weui_textarea_counter"><span id="propa_url_cnt">0</span>/600</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_btn_area">
                <input class="weui_btn weui_btn_plain_primary" name="save" type="submit"
                       onclick="this.form.submited=this.name" value="保存"/>
            </div>
        </form>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="manager.php#m/0">返回活动管理</a>
        </div>
        <div id="error_message"></div>
        <?php
    }
    ?>
</div>
<br>
</body>
</html>
