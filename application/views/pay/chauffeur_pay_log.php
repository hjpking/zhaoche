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
                <h4>司机给用户充值记录</h4>
            </div>

            <?php

            $chauffeur_code = '';
            foreach ($chauffeur_data as $v) { $chauffeur_code .= '"'.$v['cname'].'",'; }
            $chauffeur_code = substr($chauffeur_code, 0, -1);

            ?>

            <form class=" well form-inline" action="<?=url('admin')?>pay/chauffeur_pay_log" method="post">
                <input type="text" class="input-medium" placeholder="司机用户名" data-provide="typeahead" name="chauffeur_name" value="<?=isset($chauffeur_name) ? $chauffeur_name : ''?>"
                       data-items="4" data-source='[<?=$chauffeur_code?>]'>

                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" name="create_time" id="reservation" value="<?=isset($time) ? $time : ''?>" placeholder="选择充值开始和结束时间">
                </div>


                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <form action="<?=url('admin')?>pay/userPay" method="post">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>司机ID</th>
                        <th>司机名称</th>
                        <th>司机手机号</th>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>用户手机号</th>
                        <th>金额</th>
                        <th>描述</th>
                    <tr>
                    </thead>
                    <tbody>
                    <?php foreach ($log_info as $v){?>
                        <tr>
                            <td><?=$v['id']?></td>
                            <td><?=$v['chauffeur_id']?></td>
                            <td><?=$v['chauffeur_name']?></td>
                            <td><?=$v['chauffeur_phone']?></td>
                            <td><?=$v['uid']?></td>
                            <td><?=$v['uname']?></td>
                            <td><?=$v['user_phone']?></td>
                            <td><?=fPrice($v['amount'])?>元</td>
                            <td><?=$v['descr']?></td>
                            <td><?=$v['create_time']?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
                <div class="pagination pagination-right  well form-inline">
                    <strong>充值日志数量:<?=(empty($totalNum) ? '0' : $totalNum)?></strong>
                </div>
            </form>
            <div class="pagination pagination-right"><ul><?php if(isset($pageHtml)) echo $pageHtml;?></ul></div>
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
        if (t) {
            $('#status')[0].value = 1;
            $('#status_text').text('白名单');
            return;
        }
        $('#status')[0].value = 0;
        $('#status_text').text('黑名单');
    }
    function checkAll()
    {
        var currStatus = $("#ckbSelectAll").attr("checked");
        if (currStatus == 'checked') {
            jQuery(".uid").attr("checked", true);
        } else {
            jQuery(".uid").attr("checked", false);
        }
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
