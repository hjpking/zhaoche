<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/chauffeur_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/chauffeur_subnav.php');?>

            <div class="page-header">
                <h4>添加机场</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin')?>/city/airport_save" method="post">
                <input type="hidden" name="id" value="<?=isset ($data['id']) ? $data['id'] : '';?>"/>
                <fieldset>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属省份</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="city_id">
                                <option value="0">选择所属省份</option>
                                <?php foreach($city as $pk=>$pv){?>
                                    <option value="<?=$pv['city_id']?>"
                                        <?=isset ($data['city_id']) && $data['city_id'] == $pv['city_id'] ? 'selected="selected"' : '';?>
                                        <?=$pv['is_city'] == '0' ? 'disabled="disabled"' : '';?>>
                                        <?=str_repeat("&nbsp;", $pv['floor'] * 8), $pv['city_name'];?>
                                    </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">地址名称</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="name"
                                   value="<?=isset ($data['airport_name']) ? $data['airport_name'] : '';?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">经度</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="longitude"
                                   value="<?=isset ($data['longitude']) ? $data['longitude'] : '';?>">
                            <!p class="help-block"> 机场经度，如：116.59265 </p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">纬度</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="latitude"
                                   value="<?=isset ($data['latitude']) ? $data['latitude'] : '';?>">
                            <!p class="help-block"> 机场纬度，如：40.079122 </p>
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
