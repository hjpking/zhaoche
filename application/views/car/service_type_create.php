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
                <h4><?=isset($isEdit) ? '修改服务类别' : '添加服务类别'?></h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>car/service_type_save" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="sid" value="<?=isset($data['sid']) ? $data['sid'] : '';?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">服务类别名称</label>
                        <div class="controls">
                            <input type="text" id="title" class="input-xlarge" name="name" value="<?=isset($data['name']) ? $data['name'] : '';?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">服务类别描述</label>
                        <div class="controls">
                            <textarea rows="3" id="content" class="input-xlarge" name="descr"><?=isset($data['descr']) ? $data['descr'] : '';?></textarea>
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
        var content = $('#content').val();
        var title = $('#title').val();

        if (title == '' || title == undefined) {
            alert('服务类别名称为空！');
            return false;
        }
        if (content == '' || content.length > 100) {
            alert('服务类别描述为空！');
            return false;
        }
        return true;
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
