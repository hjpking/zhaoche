<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/order_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/order_subnav.php');?>

            <div class="page-header">
                <h4>充值记录列表</h4>
            </div>
            <?php
            $username_code = '';
            foreach ($userData as $v) {
                $username_code .= '"'.$v['uname'].'",';
            }
            $username_code = substr($username_code, 0, -1);

            $pay_code = '';
            foreach ($pay_data as $v) {
                $pay_code .= '"'.$v['pay_id'].'",';
            }
            $pay_code = substr($pay_code, 0, -1);
            ?>
            <form class=" well form-inline" action="<?=url('admin')?>pay/index" method="post">
                <input type="hidden" name="pay_status" id="pay_status" value="<?=isset($status) ? $status : ''?>"/>
                <input type="text" class="input-small" placeholder="订单号" data-provide="typeahead" name="order_sn" value="<?=isset($orderSn) ? $orderSn : ''?>"
                       data-items="4" data-source='[<?=$pay_code?>]'>
                <input type="text" class="input-small" placeholder="用户账号" data-provide="typeahead" name="uname" value="<?=isset($uname) ? $uname : ''?>"
                       data-items="4" data-source='[<?=$username_code?>]'>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" name="time" id="reservation" placeholder="选择充值开始与结束时间" value="<?=isset($time) ? $time : ''?>">
                </div>
                <div class="btn-group">
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn ">
                        <strong id="status_text">
                            <?php
                            $statusText = '充值方式';
                            if ($status == '1') {
                                $statusText = '支付宝';
                            } elseif ($status == '2') {
                                $statusText = '银行卡';
                            }
                            echo $statusText;
                            ?>
                            </strong> </a>
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="changeStatus(2)"> 银行卡</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(1)"> 支付宝</a></li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <a href="<?=$url?>&is_export=1" class="btn"><i class="icon-download"></i> 导出</a>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>订单号</th>
                    <th>用户名</th>
                    <th>充值金额</th>
                    <th>充值来源</th>
                    <th>充值方式</th>
                    <th>充值状态</th>
                    <th>充值时间</th>
                    <th>操作人</th>
                    <th>寄送发票</th>
                    <th>寄送方式</th>
                    <th>发票抬头</th>
                    <th>邮寄地址</th>
                    <th>寄送状态</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($pay as $v){ ?>
                <tr>
                    <td><?=$v['pay_id']?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=fPrice($v['pay_amount'])?> 元</td>
                    <td><?=$v['source'] ? '其他':'客户端'?></td>
                    <td><?=$pay_type[$v['pay_type']]?></td>
                    <td><?=$pay_status[$v['pay_status']]?></td>
                    <td><?=$v['create_time']?></td>
                    <td><?=$v['opera_people']?></td>
                    <td><?=$is_post[$v['is_post']]?></td>
                    <td><?=isset ($post_mode[$v['post_mode']]) ? $post_mode[$v['post_mode']] : '默认'?></td>
                    <td><?=$v['invoice']?></td>
                    <td><?=$v['post_address']?></td>
                    <td><?=$post_status[$v['post_status']]?></td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="pagination pagination-right">
                <ul><?php if (isset($pageHtml)) echo $pageHtml;?></ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()

    $(document).ready(function() {
        $('#reservation').daterangepicker();
    });

    $('#backdroptrue').on('click',function(evt){
        $('#modalbackdroptrue').modal({
            backdrop:true,
            keyboard:true,
            show:true
        });
    });
    function changeStatus(t)
    {
        switch (t) {
            case 1:
                $('#pay_status')[0].value = 1;
                $('#status_text').text('支付宝');
                break;
            case 2:
                $('#pay_status')[0].value = 2;
                $('#status_text').text('银行卡');
                break;
            default :
                $('#pay_status')[0].value = 1;
                $('#status_text').text('支付宝');
                break;
        }
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
