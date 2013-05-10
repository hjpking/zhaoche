<?php
$_view_nav_id = 1;

$subNav = array(
    '10' => array(
        array('title' => '添加司机', 'url' => 'chauffeur/create'),
        array('title' => '司机列表', 'url' => 'chauffeur/index/0'),
    ),
    '11' => array(
        array('title' => '司机回收站', 'url' => 'chauffeur/index/1'),
    ),
    '12' => array(
        array('title' => '添加城市', 'url' => 'city/create'),
        array('title' => '城市列表', 'url' => 'city/index'),
    ),
    '13' => array(
        array('title' => '添加常用地址', 'url' => 'city/useful_create'),
        array('title' => '常用地址列表', 'url' => 'city/useful_index'),
    ),
    '14' => array(
        array('title' => '添加机场', 'url' => 'city/airport_create'),
        array('title' => '机场列表', 'url' => 'city/airport_index'),
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
        <!--li><a href="<?=url('admin')?>chauffeur/create">添加司机</a></li>
        <li><a href="<?=url('admin')?>chauffeur/index/0">司机列表</a></li>
        <li><a href="<?=url('admin')?>chauffeur/index/1">司机回收站</a></li>
        <li><a href="<?=url('admin')?>city/create">添加城市</a></li>
        <li><a href="<?=url('admin')?>city/index">城市列表</a></li-->
    </ul>
</div>