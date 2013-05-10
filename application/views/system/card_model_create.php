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
                <h4><?=isset ($data['staff_id']) ? '修改员工' : '添加员工';?></h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>system/card_model_save" method="post">
                <input type="hidden" name="model_id" value="<?=isset($data['model_id']) ? $data['model_id'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">模型名</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="name" value="<?=isset ($data['name']) ? $data['name'] : ''?>">
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">金额</label>

                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="amount"
                                       value="<?=isset($data['amount']) ? $data['amount'] : ''?>">
                                <span class="add-on">分</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">数量</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="num" value="<?=isset($data['num']) ? $data['num'] : ''?>">
                                <span class="add-on">张</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">过期时间</label>
                        <div class="controls">
                            <input type="text" id="input03" class="input-xlarge" name="end_time" value="<?=isset($data['end_time']) ? $data['end_time'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">卡描述</label>
                        <div class="controls">
                            <textarea rows="3" id="textarea" class="input-xlarge" name="descr"><?=isset($data['descr']) ? $data['descr'] : ''?></textarea>
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
