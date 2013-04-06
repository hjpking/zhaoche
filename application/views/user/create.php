<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/user_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/user_subnav.php');?>

            <div class="page-header">
                <h4><?=isset($isDeleteStatus) ? '修改用户' : '添加用户'?></h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin');?>user/save/<?=isset($isDeleteStatus) ? $isDeleteStatus : 0?>" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="uid" value="<?=isset ($data['uid']) ? $data['uid'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">用户名</label>
                        <div class="controls">
                            <input type="text" id="name" class="input-xlarge" name="username" value="<?=isset ($data['uname']) ? $data['uname'] : ''?>" onkeypress="return limitString(this)">
                            <p class="help-block"> 字母、数字组合，不超过32个字。 </p>
                        </div>
                    </div>

                    <?php if (!isset ($data['uid'])){?>
                    <div class="control-group">
                        <label for="input01" class="control-label">用户密码</label>
                        <div class="controls">
                            <input type="password" id="password" class="input-xlarge" name="password" onkeypress="return limitString(this)">
                            <p class="help-block"> 字母、数字组合，不超过32个字。 </p>
                        </div>
                    </div>
                    <?php }?>

                    <div class="control-group">
                        <label for="input01" class="control-label">真实姓名</label>
                        <div class="controls">
                            <input type="text" id="realname" class="input-xlarge" name="realname" value="<?=isset ($data['uname']) ? $data['uname'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">性别</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox3" name="sex" <?=isset($data['sex']) && $data['sex'] == 1 ? 'checked="checked"' : '';?>> 男
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="2" id="inlineCheckbox4" name="sex" <?=isset($data['sex']) && $data['sex'] == 2 ? 'checked="checked"' : '';?>> 女
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="inlineCheckbox5" name="sex" <?=isset($data['sex']) ? ($data['sex'] == '0' ? 'checked="checked"' : '') : 'checked="checked"';?>> 保密
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">手机号码</label>
                        <div class="controls">
                            <input type="text" id="phone" class="input-xlarge" name="phone" value="<?=isset ($data['phone']) ? $data['phone'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">账号绑定类型</label>
                        <div class="controls">
                            <select id="binding_type" class="span2" name="binding_type">
                                <option value="0" >选择绑定类型</option>
                                <?php foreach ($binding_status as $k=>$v){?>
                                <option value="<?=$k?>" <?=isset ($data['binding_type']) && $data['binding_type'] == $k ? 'selected="selected"' : ''?>><?=$v?></option>
                                <?php }?>
                                <!--option value="类别" >支付宝</option-->
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select25" class="control-label">用户状态</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="1" name="status" <?=isset ($data['status']) ? ($data['status'] == 1 ? 'checked="checked"' : '') : 'checked="checked"';?>> 白名单
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="2" name="status" <?=isset ($data['status']) && $data['status'] == '0' ? 'checked="checked"' : '';?>> 黑名单
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">用户描述</label>
                        <div class="controls">
                            <textarea rows="3" id="descr" class="input-xlarge" name="descr"><?=isset ($data['descr']) ? $data['descr'] : ''?></textarea>
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
            var password = $('#password').val();
            var realname = $('#realname').val();
            var phone = $('#phone').val();
            var bind_type = $('#binding_type').val();

            if (name == '' || name == undefined) {
                alert('用户名称为空！');
                return false;
            }
            if (password == '' || password == undefined) {
                alert('用户密码为空！');
                return false;
            }
            if (realname == '' || realname == undefined) {
                //alert('用户真实姓名为空！');
                //return false;
            }
            if (phone == '' || phone == undefined || !isMobile(phone)) {
                alert('用户手机为空或格式不对！');
                return false;
            }
            if (bind_type == '' || bind_type == undefined) {
                alert('请选择绑定状态！');
                return false;
            }
            return true;
        }

        //是否为手机号码
        function isMobile(value) {console.log(value);
            if (/^1[3-9]\d{9}$/.test(value)) {
                return true;
            } else {
                return false;
            }
        }

        function limitString(t)
        {
            if (t.value.length >= 32) {
                return false;
            }
            return true;
        }
    </script>
<?php require(APPPATH . 'views/footer.php');?>
