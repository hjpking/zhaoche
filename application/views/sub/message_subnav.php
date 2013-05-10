<?php
$_view_nav_id = 6;

$subNav = array(
    '60' => array(
        array('title' => '添加消息分类', 'url' => 'message/category_create'),
        array('title' => '消息分类列表', 'url' => 'message/category_index'),
    ),
    '61' => array(
        array('title' => '添加消息', 'url' => 'message/create'),
        array('title' => '消息列表', 'url' => 'message/index/0'),
    ),
    '64' => array(
        array('title' => '消息回收站', 'url' => 'message/index/1'),
    ),
    '62' => array(
        array('title' => '消息推送', 'url' => 'message/push'),
    ),
    '63' => array(
        array('title' => '消息推送记录', 'url' => 'message/sendRecord'),
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
        <!--li><a href="<?=url('admin')?>message/category_create">添加消息分类</a></li>
        <li><a href="<?=url('admin')?>message/category_index">消息分类列表</a></li>
        <li><a href="<?=url('admin')?>message/create">添加消息</a></li>
        <li><a href="<?=url('admin')?>message/index">消息列表</a></li>
        <li><a href="<?=url('admin')?>message/push">消息推送</a></li-->
    </ul>
</div>