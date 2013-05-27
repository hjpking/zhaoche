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
                <h4><?=isset($isEdit) ? '编辑' : '添加'?>车辆级别</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>car/car_level_save" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="lid" value="<?=isset($data['lid']) ? $data['lid'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">车辆级别名称</label>
                        <div class="controls">
                            <input type="text" id="name" class="input-xlarge" name="name" value="<?=isset($data['name']) ? $data['name'] : ''?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">车辆级别价格</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" id="price" class="input-xlarge" name="price" value="<?=isset($data['price']) ? $data['price'] : ''?>">
                                <span class="add-on">元</span>
                            </div>

                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">车辆级别描述</label>
                        <div class="controls">
                            <textarea rows="3" id="descr" class="input-xlarge" name="descr"><?=isset($data['descr']) ? $data['descr'] : ''?></textarea>
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
    $('.typeahead').typeahead();

    function checkForm()
    {
        var name = $('#name').val();
        var price = $('#price').val();
        var descr = $('#descr').val();

        if (name == '' || name == undefined) {
            alert('车辆级别名称为空！');
            return false;
        }

        if (price == '' || price == undefined) {
            alert('车辆级别价格为空！');
            return false;
        }

        if (descr == '' || descr.length > 100) {
            alert('车辆级别描述为空！');
            return false;
        }
        return true;
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
