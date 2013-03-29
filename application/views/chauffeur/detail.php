<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/chauffeur_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/chauffeur_subnav.php');?>

            <div class="page-header">
                <h4>司机详细信息</h4>
            </div>

            <ul class="nav nav-tabs" id="tab">
                <li class="active"><a data-toggle="tab" href="#home">司机详情</a></li>
                <li class=""><a data-toggle="tab" href="#profile">接单记录</a></li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div id="home" class="tab-pane fade active in">
                    <p>
                    <form class="form-horizontal" action="" method="post">
                        <fieldset>
                            <div class="control-group">
                                <label for="input03" class="control-label">用户名</label>
                                <div class="controls"><?=$data['cname']?></div>
                            </div>

                            <div class="control-group">
                                <label for="input02" class="control-label">真实姓名</label>
                                <div class="controls"><?=$data['realname']?></div>
                            </div>

                            <div class="control-group">
                                <label for="select01" class="control-label">性别</label>

                                <div class="controls"><?php
                                    $text = '保密';
                                    switch ($data['sex']) {
                                        case 1:$text = '男';break;
                                        case 2:$text = '女';break;
                                    }
                                    echo $text;
                                    ?></div>
                            </div>

                            <div class="control-group">
                                <label for="input04" class="control-label">手机号码</label>
                                <div class="controls"><?=$data['phone']?></div>
                            </div>

                            <div class="control-group">
                                <label for="input01" class="control-label">身份证码号</label>
                                <div class="controls"><?=$data['id_card']?></div>
                            </div>

                            <div class="control-group">
                                <label for="select01" class="control-label">所在城市</label>
                                <div class="controls"><?=$city[$data['city_id']]['city_name']?></div>
                            </div>
                            <div class="control-group">
                                <label for="select01" class="control-label">车型</label>
                                <div class="controls"><?=$car[$data['car_id']]['name']?></div>
                            </div>

                            <div class="control-group">
                                <label for="input06" class="control-label">车牌号</label>
                                <div class="controls"><?=$data['car_no']?></div>
                            </div>

                            <div class="control-group">
                                <label for="select01" class="control-label">服装状态</label>

                                <div class="controls"><?=$data['status'] ? '正常服务' : '暂停服务';?></div>
                            </div>

                            <div class="control-group">
                                <label for="textarea" class="control-label">司机描述</label>
                                <div class="controls">
                                    <?=$data['descr'];?>
                                </div>
                            </div>

                            <hr/>
                        </fieldset>
                    </form>
                    </p>
                </div>
                <div id="profile" class="tab-pane fade">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>用户手机号</th>
                            <th>用户姓名</th>
                            <th>订车时间</th>
                            <th>上车时间</th>
                            <th>下车时间</th>
                            <th>订单状态</th>
                            <th>用车方式</th>
                            <th>租金</th>
                            <th>司机用户名</th>
                            <th>车型</th>
                            <th>操作</th>
                        <tr>
                        </thead>
                        <tbody>
                        <?php
                        if (empty ($order)) {
                            echo '<tr><td colspan="13" style="text-align: center;height: 50px;">没有相关数据</td></tr>';
                        } else {
                        foreach ($order as $v){?>
                        <tr>
                            <td><?=$v['order_sn']?></td>
                            <td><?=$v['user_phone']?></td>
                            <td><?=$v['uname']?></td>
                            <td><?=$v['order_time']?></td>
                            <td><?=$v['train_time']?></td>
                            <td><?=$v['getoff_time']?></td>
                            <td><?=$v['status'] ? '成功' : '取消'?></td>
                            <td><?=$v['use_mode']?></td>
                            <td><?=$v['amount']?></td>
                            <td><?=$v['chauffeur_login_name']?></td>
                            <td><?=$car[$v['car_id']]['name']?></td>
                            <td>
                                <a href="<?=url('admin')?>order/detail/"><i class="icon-eye-open"></i></a>
                                <a href="<?=url('admin')?>order/del/"><i class="icon-remove"></i></a>
                            </td>
                        </tr>
                        <?php }}?>
                        <!--tr>
                            <td>123</td>
                            <td>ak47</td>
                            <td>马如来</td>
                            <td>2013-02-23 11:12</td>
                            <td>2013-02-23 11:15</td>
                            <td>2013-02-23 15:25</td>
                            <td>已完成</td>
                            <td>租用</td>
                            <td>55</td>
                            <td>hjpking</td>
                            <td>花心油</td>
                            <td>大众 朗逸</td>
                            <td>
                                <a href="<?=url('admin')?>order/detail/"><i class="icon-eye-open"></i></a>
                                <a href="<?=url('admin')?>order/del/"><i class="icon-remove"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>123</td>
                            <td>ak47</td>
                            <td>马如来</td>
                            <td>2013-02-23 11:12</td>
                            <td>2013-02-23 11:15</td>
                            <td>2013-02-23 15:25</td>
                            <td>已完成</td>
                            <td>租用</td>
                            <td>55</td>
                            <td>hjpking</td>
                            <td>花心油</td>
                            <td>大众 朗逸</td>
                            <td>
                                <a href="<?=url('admin')?>order/detail/"><i class="icon-eye-open"></i></a>
                                <a href="<?=url('admin')?>order/del/"><i class="icon-remove"></i></a>
                            </td>
                        </tr-->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
    <script type="text/javascript">
        $('.typeahead').typeahead()
    </script>
<?php require(APPPATH . 'views/footer.php');?>
