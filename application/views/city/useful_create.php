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
                <h4>添加常用地址</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin')?>/city/useful_save" method="post">
                <input type="hidden" name="ua_id" value="<?=isset ($data['ua_id']) ? $data['ua_id'] : '';?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">地址名称</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="name"
                                   value="<?=isset ($data['name']) ? $data['name'] : '';?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属省份</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="city_id">
                                <option value="0">选择所属省份</option>
                                <?php foreach($city as $pk=>$pv){?>
                                <option value="<?=$pv['city_id']?>" <?=isset ($data['city_id']) && $data['city_id'] == $pv['city_id'] ? 'selected="selected"' : '';?>
                                    <?php //$pv['is_city'] == 1 ? '' : 'disabled="disabled"';?>>
                                    <?=str_repeat("&nbsp;", $pv['floor'] * 8), $pv['city_name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">经纬度</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="longitude"
                                   value="<?=(isset ($data['longitude']) && isset ($data['latitude']) ) ? $data['longitude'].','.$data['latitude'] : '';?>">
                            <p class="help-block"> 例如：114.522091,38.059067。 </p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">城市描述</label>
                        <div class="controls">
                            <textarea rows="3" id="textarea" class="input-xlarge" name="descr"><?=isset ($data['descr']) ? $data['descr'] : '';?></textarea>
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
