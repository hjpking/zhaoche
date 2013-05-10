<?php
$_view_nav_id = 2;

$subNav = array(
    '20' => array(
        array('title' => '添加用户', 'url' => 'user/create'),
        array('title' => '用户列表', 'url' => 'user/index/0'),
    ),
    '21' => array(
        array('title' => '用户回收站', 'url' => 'user/index/1'),
    ),
    '22' => array(
        array('title' => '用户发票', 'url' => 'user/invoice_index'),
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
        <!--li><a href="<?=url('admin')?>user/create">添加用户</a></li>
        <li><a href="<?=url('admin')?>user/index/0">用户列表</a></li-->
    </ul>
</div>