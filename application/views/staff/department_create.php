<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/staff_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/staff_subnav.php');?>

            <div class="page-header">
                <h4><?=isset ($isEdit) ? '编辑' : '添加'?>部门</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>staff/department_save" method="post">
                <input type="hidden" name="depart_id" value="<?=isset($data['depart_id']) ? $data['depart_id'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">部门名称</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="name" value="<?=isset($data['name']) ? $data['name'] : ''?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属部门</label>
                        <div class="controls">
                            <select id="select02" class="span2" name="parent_id">
                                <option value="0">选择所属部门</option>
                                <?php
                                foreach ($depart_data as $v){
                                    if (isset ($data['depart_id']) && $data['depart_id'] == $v['depart_id']){
                                        continue;
                                    }
                                    ?>
                                <option value="<?=$v['depart_id']?>"
                                    <?=isset ($data['parent_id']) && $data['parent_id'] == $v['depart_id'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                                </option>
                                <?php }?>

                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">部门描述</label>
                        <div class="controls">
                            <textarea rows="3" id="textarea" class="input-xlarge" name="descr"><?=isset ($data['descr']) ? $data['descr'] : ''?></textarea>
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
