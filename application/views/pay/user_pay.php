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
                <h4>给用户充值</h4>
            </div>
            <form class="form-horizontal" action="<?=url('admin');?>pay/toPay" method="post" onsubmit="return checkForm()">
                <input type="checkbox" name="uid[]" value="<?=$user_info['uid'];?>" checked="checked"/>


                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">用户名 </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="name" value="<?=$user_info['uname'];?>" disabled="disabled">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">余额 </label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="amount" value="<?=fPrice($user_info['amount']);?>" disabled="disabled">
                                <span class="add-on">元</span>
                            </div>
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">充值金额 </label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="pay_amount" onkeyup="this.value=this.value.replace(/\D/g,'')">
                                <span class="add-on">元</span>
                            </div>
                            <p class="help-block"> 充值金额不能超过20000元。 </p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">充值</button>
                        <button class="btn">取消</button>
                    </div>
                </fieldset>
            </form>
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
