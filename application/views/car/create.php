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
                <h4><?=isset($isEdit) ? '编辑' : '添加'?>车辆</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>car/save" method="post">
                <input type="hidden" name="car_id" value="<?=isset($data['car_id']) ? $data['car_id'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">车辆名称</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="name" value="<?=isset($data['name']) ? $data['name'] : ''?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属品牌</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="parent_id">
                                <option value="0">选择所属品牌</option>
                                <?php foreach ($car_data as $v) {?>
                                <option value="<?=$v['car_id']?>"
                                    <?=isset ($data['parent_id']) && $data['parent_id'] == $v['car_id'] ? 'selected="selected"' : '';?>>
                                    <?php $v['floor'] = isset ($v['floor']) ? $v['floor'] : 0; echo str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="select01">是否为车型</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="is_car_model" id="inlineCheckbox3" value="1"
                                        <?=isset($data['is_car_model']) && $data['is_car_model'] == '1' ? 'checked="checked"' : ''?>> 是
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="is_car_model" id="inlineCheckbox4" value="0"
                                    <?=isset($data['is_car_model']) && $data['is_car_model'] == '0' ? 'checked="checked"' : ''?>> 否
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属级别</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="lid">
                                <?php foreach ($car_level_data as $v) {?>
                                <option value="<?=$v['lid']?>"
                                    <?=isset ($data['lid']) && $data['lid'] == $v['lid'] ? 'selected="selected"' : ''?>><?=$v['name']?></option>
                                <?php }?>
                                <!--option value="类别">舒适型</option-->
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">车辆描述</label>
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
