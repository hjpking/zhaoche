<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <ul class="nav nav-tabs nav-stacked">
                <?php $_view_nav_id = 2;?>
                <li class="nav-header font-1em"><h3>快捷通道</h3></li>
                <?php
                $this->load->model('model_competence_correspond', 'cc');
                $userCcData = $this->cc->getUserCompetence($this->amInfo['staff_id'], '*', null, 'competence_id');

                $arr = array(
                    30 => array('name' => '订单管理', 'url' => 'order/index'),
                    31 => array('name' => '充值管理', 'url' => 'pay/index'),
                    20 => array('name' => '用户管理', 'url' => 'user/index/0'),
                    10 => array('name' => '司机管理', 'url' => 'chauffeur/index/0'),
                    61 => array('name' => '消息管理', 'url' => 'message/index'),
                    50 => array('name' => '投诉建议管理', 'url' => 'feedback/index'),
                    80 => array('name' => '个人信息管理', 'url' => 'system/profile'),
                );
                foreach ($arr as $k=>$v) {
                    if (!array_key_exists($k, $userCcData)) {
                        //unset ($functionModule[$k]);
                        continue;
                    }
                ?>
                    <li><a href="<?=url('admin').$v['url'];?>"><?=$v['name'];?></a></li>
                <?php }?>
                <!--li><a href="<?=url('admin');?>order/index">订单管理</a></li>
                <li><a href="<?=url('admin');?>pay/index">充值管理</a></li>
                <li><a href="<?=url('admin');?>user/index/0">用户管理</a></li>
                <li><a href="<?=url('admin');?>chauffeur/index/0">司机管理</a></li>
                <li><a href="<?=url('admin');?>message/index">消息管理</a></li>
                <li><a href="<?=url('admin');?>feedback/index">投诉建议管理</a></li>
                <li><a href="<?=url('admin');?>system/profile">个人信息管理</a></li-->
            </ul>
        </div>
        <div class="span10">


            <div class="page-header">
                <h4>系统动态提醒便签</h4>
            </div>
            <!--div class="alert alert-error">
                <a data-dismiss="alert" class="close">×</a>
                <strong>最新资讯！</strong> 紧密团结在以希特勒元首为核心的纳粹党中央周围，高举国家意志和民族利益的大旗，直达人间地狱。
            </div-->

            <?php if (array_key_exists(30, $userCcData)) { ?>
            <div class="span5">
                <div style="background-color: #ffffff;" class="well">
                    <strong><a href="<?=url('admin');?>order/index">最近5笔订单信息</a></strong><hr/>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>城市</th>
                            <th>用户名</th>
                            <th>状态</th>
                            <th>金额</th>
                            <th>订车时间</th>
                            <th>操作</th>
                        <tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order as $v){?>
                        <tr>
                            <td><?=$v['order_sn']?></td>
                            <td><?=$cityInfo[$v['city_id']]['city_name']?></td>
                            <td><?=$v['uname']?></td>
                            <td><?=$order_status[$v['status']]?></td>
                            <td><?=fPrice($v['amount'])?>元</td>
                            <td><?=date('m-d H:i', strtotime($v['create_time']))?></td>
                            <td>
                                <a href="<?=url('admin')?>order/detail/<?=$v['order_sn']?>" title="查看订单"><i class="icon-eye-open"></i></a>
                            </td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php }?>

            <?php if (array_key_exists(31, $userCcData)) { ?>
            <div class="span5">
                <div style="background-color: #ffffff;" class="well">
                    <strong><a href="<?=url('admin');?>pay/index">最近5笔充值信息</a></strong><hr/>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>订单ID</th>
                            <th>用户名</th>
                            <th>金额</th>
                            <th>状态</th>
                            <th>寄送发票</th>
                            <th>充值时间</th>
                        <tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pay as $v){?>
                        <tr>
                            <td><?=$v['pay_id']?></td>
                            <td><?=$v['uname']?></td>
                            <td><?=fPrice($v['pay_amount'])?></td>
                            <td><?=$pay_status[$v['pay_status']]?></td>
                            <td><?=$is_post[$v['is_post']]?></td>
                            <td><?=date('m-d H:i', strtotime($v['create_time']))?></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php }?>

            <?php if (array_key_exists(50, $userCcData)) { ?>
            <div class="span5">
                <div style="background-color: #ffffff;" class="well">
                    <strong><a href="<?=url('admin');?>feedback/index">最新5条投诉建议</a></strong><hr/>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>订单号</th>
                            <th>用户名</th>
                            <th>描述</th>
                            <th>状态</th>
                            <th>提交时间</th>
                        <tr>
                        </thead>
                        <tbody>
                        <?php foreach ($feedback as $v){?>
                        <tr>
                            <td><?=$v['id']?></td>
                            <td><?=$v['order_sn']?></td>
                            <td><?=$v['uname']?></td>
                            <td><?=$v['descr']?></td>
                            <td><?=$process_status[$v['process_status']]?></td>
                            <td><?=date('m-d H:i', strtotime($v['create_time']))?></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php }?>


            <?php if (array_key_exists(20, $userCcData)) { ?>
            <div class="span5">
                <div style="background-color: #ffffff;" class="well">
                    <strong><a href="<?=url('admin');?>feedback/index">新增5个新用户</a></strong><hr/>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>用户ID</th>
                            <th style="width: 50px;table-layout:fixed;overflow:auto;">用户名</th>
                            <th>手机号</th>
                            <th>状态</th>
                            <th>注册时间</th>
                        <tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user as $v){?>
                        <tr>
                            <td><?=$v['uid']?></td>
                            <td title="<?=$v['uname']?>"><?=cutStr($v['uname'], 0, 8);?></td>
                            <td><?=$v['phone']?></td>
                            <td><?=isset ($user_status[$v['status']]) ? $user_status[$v['status']] : '未知'?></td>
                            <td><?=date('m-d H:i', strtotime($v['create_time']))?></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php }?>

        </div>
    </div>
</div>
    <script type="text/javascript">
        $('.typeahead').typeahead()
    </script>
<?php require(APPPATH . 'views/footer.php');?>
