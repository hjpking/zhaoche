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
                <h4>待充值用户列表</h4>
            </div>

            <?php
            $username_code = '';
            $phone_code = '';
            foreach ($user_data as $v) {
                $username_code .= '"'.$v['uname'].'",';
                $phone_code .= '"'.$v['phone'].'",';
            }
            $username_code = substr($username_code, 0, -1);
            $phone_code = substr($phone_code, 0, -1);
            ?>

            <form class=" well form-inline" action="<?=url('admin')?>pay/beUserPay" method="post">
                <input type="hidden" name="status" id="status"
                       value="<?=isset($status) && $status === '1' ? '1' : ($status === '0' ? '0' : '');?>"/>
                <input type="text" class="input-medium" placeholder="用户名" data-provide="typeahead" name="uname" value="<?=isset($uname) ? $uname : ''?>"
                       data-items="4" data-source='[<?=$username_code?>]'>

                <input type="text" class="input-medium" placeholder="手机号码" data-provide="typeahead" name="phone"
                       data-items="4" data-source='[<?=$phone_code?>]' value="<?=isset($phone) ? $phone : ''?>">

                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" name="create_time" id="reservation" value="<?=isset($time) ? $time : ''?>" placeholder="选择用户注册开始和结束时间">
                </div>

                <div class="btn-group">
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn ">
                        <strong id="status_text"><?=isset($status) && $status === '1' ? '白名单' : ($status === '0' ? '黑名单' : '用户状态')?></strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="changeStatus(1)"><i class="icon-ok"></i> 白名单</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(0)"><i class="icon-remove"></i> 黑名单</a></li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <form action="<?=url('admin')?>pay/batchUserPay" method="post" onsubmit="return checkBatchForm()">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><input id="ckbSelectAll" type="checkbox" class="chk" onclick="checkAll()"></th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>手机号</th>
                    <th>绑定类型</th>
                    <th>余额</th>
                    <th>用户状态</th>
                    <th>注册时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($user_info as $v){?>
                <tr>
                    <td><input type="checkbox" name="uid[]" class="uid" value="<?=$v['uid']?>"/></td>
                    <td><?=$v['uid']?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=$v['realname']?></td>
                    <td><?=$v['phone']?></td>
                    <td><?=$binding_status[$v['binding_type']]?></td>
                    <td><?=fPrice($v['amount'])?>元</td>
                    <td><?=$v['status'] ? '白名单' : '黑名单'?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>pay/userPay/<?=$v['uid']?>" class="btn"><i class="icon-plus"></i> 充值</a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="pagination">
                <button type="submit" class="btn btn-primary"><i class="icon-plus icon-white"></i> 批量充值</button>
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
        function checkBatchForm()
        {
            var checkStatus = false;

            $('.uid').each(function(k,v){
                //console.log(v.checked);
                if (v.checked) {
                    checkStatus = true;
                }
            })
            //var a = $('.chauffeur_id');

            if (checkStatus) return true;

            alert('请选择要充值用户！');
            return false;
        }
    </script>
<?php require(APPPATH . 'views/footer.php');?>
