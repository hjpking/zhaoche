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
                <h4>添加司机</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>chauffeur/save/<?=isset ($isDeleteStatus) ? $isDeleteStatus : ''?>" method="post">
                <input type="hidden" name="chauffeur_id" value="<?=isset ($data['chauffeur_id']) ? $data['chauffeur_id'] : '';?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">司机登陆名</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge" name="username" value="<?=isset ($data['cname']) ? $data['cname'] : '';?>">
                            <p class="help-block"> 字母、数字组合，不超过32个字符。 </p>
                        </div>
                    </div>

                    <?php if (!isset ($data['chauffeur_id'])){?>
                    <div class="control-group">
                        <label for="input01" class="control-label">司机密码</label>
                        <div class="controls">
                            <input type="password" id="input06" class="input-xlarge" name="password">
                            <p class="help-block"> 字母、数字组合，不超过32个字符。 </p>
                        </div>
                    </div>
                    <?php }?>

                    <div class="control-group">
                        <label for="input01" class="control-label">真实姓名</label>
                        <div class="controls">
                            <input type="text" id="input02" class="input-xlarge" name="realname" value="<?=isset ($data['realname']) ? $data['realname'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">性别</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox3" name="usersex" <?=isset($data['sex']) && $data['sex'] == 1 ? 'checked="checked"' : '';?>> 男
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="2" id="inlineCheckbox4" name="usersex" <?=isset($data['sex']) && $data['sex'] == 2 ? 'checked="checked"' : '';?>> 女
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="inlineCheckbox5" name="usersex" <?=isset($data['sex']) && $data['sex'] == 0 ? 'checked="checked"' : '';?>> 保密
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">手机号码</label>
                        <div class="controls">
                            <input type="text" id="input03" class="input-xlarge" name="phone" value="<?=isset ($data['phone']) ? $data['phone'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">身份证码号</label>
                        <div class="controls">
                            <input type="text" id="input04" class="input-xlarge" name="id_card" value="<?=isset ($data['id_card']) ? $data['id_card'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所在城市</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="city">
                                <option value="0">选择所在城市</option>
                                <?php foreach ($city as $v){?>
                                <option value="<?=$v['city_id']?>" <?=$v['is_city'] == 1 ? '' : 'disabled="disabled"';?>
                                    <?=isset ($data['city_id']) && $data['city_id']== $v['city_id'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?>
                                </option>
                                <?php }?>

                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="select01" class="control-label">车型</label>
                        <div class="controls">
                            <select id="select01" class="span3" name="car_type">
                                <option value="0" selected="selected">选择车型</option>
                                <?php foreach ($car as $v){?>
                                <option value="<?=$v['car_id']?>" <?=$v['is_car_model'] == 1 ? '' : 'disabled="disabled"';?>
                                    <?=isset ($data['car_id']) == $v['car_id'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">车牌号</label>
                        <div class="controls">
                            <input type="text" id="input05" class="input-xlarge" name="car_no" value="<?=isset ($data['car_no']) ? $data['car_no'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">服装状态</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox1" name="status" <?=isset ($data['status']) && $data['status'] == 1 ? 'checked="checked"' : '';?>> 服务中
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="inlineCheckbox2" name="status" <?=isset ($data['status']) && $data['status'] == 0 ? 'checked="checked"' : '';?>> 暂停服务
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">司机描述</label>
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
