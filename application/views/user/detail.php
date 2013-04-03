<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/user_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/user_subnav.php');?>

            <div class="page-header">
                <h4>用户详细信息</h4>
            </div>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>字段</th>
                    <th>信息</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tr>
                    <td></td>
                    <td>用户名：</td>
                    <td><?=$data['uname']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>真实姓名：</td>
                    <td><?=$data['realname']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>账号余额：</td>
                    <td> <?=fPrice($data['amount'])?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>性别：</td>
                    <td> <?php
                        switch ($data['sex'])
                        {
                            case 1: $text = '男';break;
                            case 1: $text = '女';break;
                            default:$text = '保密';break;
                        }
                        echo $text;
                        ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>手机号码：</td>
                    <td> <?=$data['phone']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>账号绑定类型：</td>
                    <td> <?=$binding_type[$data['binding_type']];?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>用户状态：</td>
                    <td> <?=$user_status[$data['status']]?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>充值数量：</td>
                    <td> <a href="#" title="查看详细"> <?=$pay_number?> </a>笔</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>订单数量：</td>
                    <td> <a href="#" title="查看详细"><?=$order_number?></a> 笔</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>投诉/建议数量：</td>
                    <td> <a href="#" title="查看详细"><?=$feedback_number?></a> 次</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>用户描述：</td>
                    <td> <?=$data['descr']?></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <!--
                        <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <div class="control-group">
                        <label for="input03" class="control-label">用户名</label>
                        <div class="controls">hjpking</div>
                    </div>

                    <div class="control-group">
                        <label for="input02" class="control-label">真实姓名</label>
                        <div class="controls">花心油</div>
                    </div>
                    <div class="control-group">
                        <label for="input02" class="control-label">账号余额</label>
                        <div class="controls"> 12 元</div>
                    </div>
                    <div class="control-group">
                        <label for="select01" class="control-label">性别</label>

                        <div class="controls">男</div>
                    </div>

                    <div class="control-group">
                        <label for="input04" class="control-label">手机号码</label>
                        <div class="controls">15101559313</div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">账号绑定类别</label>

                        <div class="controls">支付宝</div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">用户状态</label>

                        <div class="controls">白名单</div>
                    </div>
                    <div class="control-group">
                        <label for="select01" class="control-label">充值数量</label>

                        <div class="controls"> 5 笔</div>
                    </div>
                    <div class="control-group">
                        <label for="select01" class="control-label">订单数量</label>

                        <div class="controls">6 笔</div>
                    </div>
                    <div class="control-group">
                        <label for="select01" class="control-label">投诉/建议数量</label>

                        <div class="controls"> 2 次</div>
                    </div>
                    <div class="control-group">
                        <label for="textarea" class="control-label">用户描述</label>
                        <div class="controls">
                            忠实用户。
                        </div>
                    </div>

                    <hr/>
                </fieldset>
            </form>
            -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()
</script>
<?php require(APPPATH . 'views/footer.php');?>
