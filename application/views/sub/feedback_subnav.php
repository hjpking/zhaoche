<?php
$_view_nav_id = 5;

$subNav = array(
    '50' => array(
        array('title' => '投诉建议列表', 'url' => 'feedback/index'),
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
        <!--li><a href="<?=url('admin')?>feedback/index">投诉建议列表</a></li-->
    </ul>
</div>