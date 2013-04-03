<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/car_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/car_subnav.php');?>

            <div class="page-header">
                <h4><?=isset($isEdit) ? '修改' : '添加'?>车辆级别</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>car/car_level_save" method="post">
                <input type="hidden" name="lid" value="<?=isset($data['lid']) ? $data['lid'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">车辆级别名称</label>
                        <div class="controls">
                            <input type="text" id="1" class="input-xlarge" name="name" value="<?=isset($data['name']) ? $data['name'] : ''?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">车辆级别价格</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" id="1" class="input-xlarge" name="price" value="<?=isset($data['price']) ? $data['price'] : ''?>">
                                <span class="add-on">元</span>
                            </div>

                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">车辆级别描述</label>
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
