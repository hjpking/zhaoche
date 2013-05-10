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
                <h4>个人资料</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>system/saveProfile" method="post">
                <input type="hidden" name="staff_id" value="<?=$data['staff_id']?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">登陆名</label>
                        <div class="controls">
                            <input type="text" id="input01" class="input-xlarge disabled" disabled="" name="login_name" value="<?=$data['login_name']?>">
                            <p class="help-block"> 字母、数字组合，不超过32个字符。 </p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">真实姓名</label>
                        <div class="controls">
                            <input type="text" id="input02" class="input-xlarge" name="realname" value="<?=$data['realname']?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">性别</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox3" name="sex" <?=$data['sex'] == '1' ? 'checked="checked"' : ''?>> 男
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="inlineCheckbox4" name="sex" <?=$data['sex'] == '0' ? 'checked="checked"' : ''?>> 女
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">手机号码</label>
                        <div class="controls">
                            <input type="text" id="input03" class="input-xlarge" name="phone" value="<?=$data['phone']?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">身份证码号</label>
                        <div class="controls">
                            <input type="text" id="input04" class="input-xlarge" name="id_card" value="<?=$data['id_card']?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">邮箱</label>
                        <div class="controls">
                            <input type="text" id="input04" class="input-xlarge" name="email" value="<?=$data['email']?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属部门</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="depart_id">
                                <?php foreach ($department as $v){ ?>
                                <option value="<?=$v['depart_id']?>"
                                    <?=$data['depart_id'] == $v['depart_id'] ? 'selected="selected"' : ''?>><?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">描述</label>
                        <div class="controls">
                            <textarea rows="3" id="textarea" class="input-xlarge" name="descr"><?=$data['descr']?></textarea>
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
