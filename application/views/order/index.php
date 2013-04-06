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
                <h4>订单列表</h4>
            </div>

            <?php
            $str = '';
            foreach ($order as $v) {
                $str .= '"'.$v['order_sn'].'",';
            }
            $str = substr($str, 0, -1);
            ?>
            <form class=" well form-inline" action="<?=url('admin')?>/order/index" method="post">
                <input type="hidden" name="status" id="status"
                       value="<?php
                        $statusText = '';
                       if ($status === '0') {
                           $statusText = '0';
                       } elseif ($status === '1') {
                           $statusText = '1';
                       } elseif ($status === '2') {
                           $statusText = '2';
                       }
                       echo $statusText;
                       ?>"/>
                <input type="text" class="input-medium" placeholder="订单号" data-provide="typeahead" name="order_sn"
                       value="<?=isset($order_sn) ? $order_sn : ''?>"
                       data-items="4" data-source='[<?=$str?>]'>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" name="time" id="reservation" placeholder="选择订单开始与结束时间" value="<?=isset ($time) ? $time : ''?>">
                </div>
                <div class="btn-group">
                    <a href="#" class="btn " data-toggle="dropdown"> <strong id="status_text">
                        <?php
                        $statusText = '全部';
                        if ($status === '0') {
                            $statusText = '初始';
                        } elseif ($status === '1') {
                            $statusText = '已完成';
                        } elseif ($status === '2') {
                            $statusText = '已取消';
                        }
                        echo $statusText;
                        ?>
                        </strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="changeStatus()"><i class="icon-off"></i> 全部</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(0)"><i class="icon-off"></i> 初始</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(1)"><i class="icon-ok"></i> 已完成</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(2)"><i class="icon-remove"></i> 已取消</a></li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <a href="<?=$url?>&is_export=1" class="btn"><i class="icon-download"></i> 导出</a>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>订单号</th>
                    <th>所属城市</th>
                    <th>服务类别</th>
                    <th>车辆级别</th>
                    <th>用户名</th>
                    <th>手机号</th>
                    <th>金额</th>
                    <th>订单状态</th>
                    <th>司机</th>
                    <th>司机手机</th>
                    <th>上车时间</th>
                    <th>下车时间</th>
                    <th>订车时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($order as $v){?>
                <tr>
                    <td><?=$v['order_sn']?></td>
                    <td><?=$cityInfo[$v['city_id']]['city_name']?></td>
                    <td><?=$sf_info[$v['sid']]['name']?></td>
                    <td><?=$carLevelInfo[$v['lid']]['name']?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=$v['user_phone']?></td>
                    <td><?=fPrice($v['amount'])?>元</td>
                    <td><?=$order_status[$v['status']]?></td>
                    <td><?=$v['chauffeur_login_name']?></td>
                    <td><?=$v['chauffeur_phone']?></td>
                    <td><?=date('H:i', strtotime($v['train_time']))?></td>
                    <td><?=date('H:i', strtotime($v['getoff_time']))?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>order/detail/<?=$v['order_sn']?>" title="查看订单"><i class="icon-eye-open"></i></a>
                        <!--a href="<?=url('admin')?>order/delete/<?=$v['order_sn']?>" title="取消订单"><i class="icon-remove"></i></a-->
                    </td>
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

    function changeStatus(t)
    {
        switch (t) {
            case 0:
                $('#status')[0].value = 0;
                $('#status_text').text('初始');
                break;
            case 1:
                $('#status')[0].value = 1;
                $('#status_text').text('已完成');
                break;
            case 2:
                $('#status')[0].value = 2;
                $('#status_text').text('已取消');
                break;
            default :
                $('#status')[0].value = '';
                $('#status_text').text('全部');
                break;
        }
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
