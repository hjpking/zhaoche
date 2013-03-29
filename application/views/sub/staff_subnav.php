<?php
$_view_nav_id = 7;

$subNav = array(
    '70' => array(
        array('title' => '添加员工', 'url' => 'staff/create'),
        array('title' => '员工列表', 'url' => 'staff/index/0'),
    ),
    '71' => array(
        array('title' => '员工回收站', 'url' => 'staff/index/1'),
    ),
    '72' => array(
        array('title' => '添加部门', 'url' => 'staff/department_create'),
        array('title' => '部门列表', 'url' => 'staff/department_index'),
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
        <!--li><a href="<?=url('admin')?>staff/create">添加员工</a></li>
        <li><a href="<?=url('admin')?>staff/index/0">员工列表</a></li>
        <li><a href="<?=url('admin')?>staff/department_create">添加部门</a></li>
        <li><a href="<?=url('admin')?>staff/department_index">部门列表</a></li-->
    </ul>
</div>