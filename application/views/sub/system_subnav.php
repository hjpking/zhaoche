<?php
$_view_nav_id = 8;

$subNav = array(
    '80' => array(
        array('title' => '个人资料', 'url' => 'system/profile'),
    ),
    '81' => array(
        array('title' => '修改密码', 'url' => 'system/reset_password'),
    ),
    '82' => array(
        array('title' => '卡列表', 'url' => 'system/card_index'),
        array('title' => '添加卡模型', 'url' => 'system/card_model_create'),
        array('title' => '卡模型列表', 'url' => 'system/card_model_index'),
    ),
);
?>
<div class="subnav">
    <ul class="nav nav-pills">
        <?php
        foreach ($_view_nav_conf[$_view_nav_id]['links'] as $k=>$v) {
            foreach ($subNav[$k] as $lv) {
                echo '<li><a href="'.url('admin').$lv['url'].'">'.$lv['title'].'</a></li>';
            }
        }
        ?>
        <!--li><a href="<?=url('admin')?>system/profile">个人资料</a></li>
        <li><a href="<?=url('admin')?>system/reset_password">修改密码</a></li-->
    </ul>
</div>