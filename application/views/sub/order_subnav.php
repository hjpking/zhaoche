<?php
$_view_nav_id = 3;

$subNav = array(
    '30' => array(
        array('title' => '订单列表', 'url' => 'order/index'),
    ),
    '31' => array(
        array('title' => '充值列表', 'url' => 'pay/index'),
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
        <!--li><a href="<?=url('admin')?>order/index">订单列表</a></li>
        <li><a href="<?=url('admin')?>pay/index">充值列表</a></li-->
    </ul>
</div>