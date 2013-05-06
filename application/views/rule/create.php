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
                <h4>添加计费规则</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>rule/save" method="post">
                <input type="hidden" name="rule_id" value="<?=isset($data['rule_id']) ? $data['rule_id'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="select01" class="control-label">所属城市</label>
                        <div class="controls">
                            <select id="select01" name="city_id" class="span4">
                                <option value="0" selected="selected">选择所在城市</option>
                                <?php foreach ($cityInfo as $v){?>
                                <option value="<?=$v['city_id']?>" <?=$v['is_city'] == 1 ? '' : 'disabled="disabled"';?>
                                    <?=isset($data['city_id']) && $data['city_id'] == $v['city_id'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属服务</label>
                        <div class="controls">
                            <select id="select02" name="sid" class="span4">
                                <option value="0" selected="selected">选择所属服务</option>
                                <?php foreach ($sf_info as $v){?>
                                <option value="<?=$v['sid']?>"
                                    <?=isset($data['sid']) && $data['sid'] == $v['sid'] ? 'selected="selected"' : '';?>>
                                    <?=$v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属级别</label>
                        <div class="controls">
                            <select id="select02" name="lid" class="span4">
                                <option value="0" selected="selected">选择车辆级别</option>
                                <?php foreach ($carLevelInfo as $v){?>
                                <option value="<?=$v['lid']?>"
                                    <?=isset($data['lid']) && $data['lid'] == $v['lid'] ? 'selected="selected"' : '';?>>
                                    <?=$v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">基础价格</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="base_price" value="<?=isset($data['base_price']) ? fPrice($data['base_price']) : ''?>">
                                <span class="add-on">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">公里单价</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="km_price" value="<?=isset($data['km_price']) ? fPrice($data['km_price']) : ''?>">
                                <span class="add-on">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">服务公里数</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="service_km" value="<?=isset($data['service_km']) ? $data['service_km'] : ''?>">
                                <span class="add-on">公里</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">时间单价</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="time_price" value="<?=isset($data['time_price']) ? fPrice($data['time_price']) : ''?>">
                                <span class="add-on">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">时长</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span4" type="text" name="time_int" value="<?=isset($data['time']) ? $data['time'] : ''?>">
                                <span class="add-on">分钟</span>
                            </div>
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">服务时长</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span4" type="text" name="service_time" value="<?=isset($data['service_time']) ? $data['service_time'] : ''?>">
                                <span class="add-on">分钟</span>
                            </div>
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">夜间服务费</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="night_service_charge" value="<?=isset($data['night_service_charge']) ? fPrice($data['night_service_charge']) : ''?>">
                                <span class="add-on">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">空驶费</label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span12" type="text" name="kongshi_fee" value="<?=isset($data['kongshi_fee']) ? fPrice($data['kongshi_fee']) : ''?>">
                                <span class="add-on">元</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="textarea">描述</label>
                        <div class="controls">
                            <textarea name="descr" class="input-xlarge" id="textarea" rows="3"><?=isset($data['descr']) ? $data['descr'] : ''?></textarea>
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
