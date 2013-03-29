<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/system_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/system_subnav.php');?>

            <div class="page-header">
                <h4>个人资料</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>system/changePassword" method="post">
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">当前密码</label>
                        <div class="controls">
                            <input type="password" id="input01" class="input-xlarge" name="current_password">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">新密码</label>
                        <div class="controls">
                            <input type="password" id="input06" class="input-xlarge" name="new_password">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">重复密码</label>
                        <div class="controls">
                            <input type="password" id="input02" class="input-xlarge" name="repat_password">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">保存更改</button>
                        <button class="btn">取消</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()
</script>
<?php require(APPPATH . 'views/footer.php');?>
