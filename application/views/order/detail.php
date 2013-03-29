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
                <h4>订单详情</h4>
            </div>

            <table class="table table-hover table-striped">
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
                    <td>订单号：</td>
                    <td><?=$data['order_sn']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>所属城市：</td>
                    <td> <?=$cityInfo[$data['city_id']]['city_name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>服务类别：</td>
                    <td> <?=$sf_info[$data['sid']]['name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>车辆级别：</td>
                    <td> <?=$carLevelInfo[$data['lid']]['name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>订单状态：</td>
                    <td><?=$order_status[$data['status']]?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>用户姓名：</td>
                    <td> <?=$data['uname']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>用户手机：</td>
                    <td> <?=$data['user_phone']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>用户性别：</td>
                    <td> <?=$user_sex[$data['user_sex']]?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>司机用户名：</td>
                    <td> <?=$data['chauffeur_login_name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>司机手机：</td>
                    <td> <?=$data['chauffeur_phone']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>上车地点：</td>
                    <td> <?=$data['train_address']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>下车地点：</td>
                    <td> <?=$data['getoff_address']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>订车时间：</td>
                    <td> <?=$data['create_time']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>上车时间：</td>
                    <td> <?=$data['train_time']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>下车时间：</td>
                    <td> <?=$data['getoff_time']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>总金额：</td>
                    <td> <?=$data['amount']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>车辆租金：</td>
                    <td> <?=$data['car_rent']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>里程费：</td>
                    <td> <?=$data['mileage_fee']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>司机佣金：</td>
                    <td> <?=$data['chauffeur_commission']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>调车费：</td>
                    <td> <?=$data['adjust_fares']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>减免调车费：</td>
                    <td> <?=$data['reduce_adjust_fares']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>起步费：</td>
                    <td> <?=$data['start_fee']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>空驶费：</td>
                    <td> <?=$data['kongshi_fee']?> 元</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()

    $(document).ready(function() {
        $('#reservation').daterangepicker();
    });
</script>
<?php require(APPPATH . 'views/footer.php');?>
