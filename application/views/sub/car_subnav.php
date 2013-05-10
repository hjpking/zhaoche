<?php
$_view_nav_id = 4;

$subNav = array(
    '40' => array(
        array('title' => '添加服务类别', 'url' => 'car/service_type_create'),
        array('title' => '服务类别列表', 'url' => 'car/service_type_index'),
    ),
    '41' => array(
        array('title' => '添加车辆级别', 'url' => 'car/car_level_create'),
        array('title' => '车辆级别列表', 'url' => 'car/car_level_index'),
    ),
    '42' => array(
        array('title' => '添加车辆', 'url' => 'car/create'),
        array('title' => '车辆列表', 'url' => 'car/index'),
    ),
    '43' => array(
        array('title' => '添加计费规则', 'url' => 'rule/create'),
        array('title' => '计费规则列表', 'url' => 'rule/index'),
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
        <!--li><a href="<?=url('admin')?>car/service_type_create">添加服务类别</a></li>
        <li><a href="<?=url('admin')?>car/service_type_index">服务类别列表</a></li>

        <li><a href="<?=url('admin')?>car/car_level_create">添加车辆级别</a></li>
        <li><a href="<?=url('admin')?>car/car_level_index">车辆级别列表</a></li>

        <li><a href="<?=url('admin')?>car/create">添加车辆</a></li>
        <li><a href="<?=url('admin')?>car/index">车辆列表</a></li>

        <li><a href="<?=url('admin')?>rule/create">添加计费规则</a></li>
        <li><a href="<?=url('admin')?>rule/index">计费规则列表</a></li-->
    </ul>
</div>