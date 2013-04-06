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

            <form class="form-horizontal" action="<?=url('admin');?>chauffeur/save/<?=isset ($isDeleteStatus) ? $isDeleteStatus : ''?>" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="chauffeur_id" value="<?=isset ($data['chauffeur_id']) ? $data['chauffeur_id'] : '';?>"/>
                <input type="hidden" name="url" value="<?=isset ($url) ? $url : '';?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">司机登陆名</label>
                        <div class="controls">
                            <input type="text" id="name" class="input-xlarge" name="username" value="<?=isset ($data['cname']) ? $data['cname'] : '';?>" onkeypress="return limitString(this)">
                            <p class="help-block"> 字母、数字组合，不超过32个字。 </p>
                        </div>
                    </div>

                    <?php if (!isset ($data['chauffeur_id'])){?>
                    <div class="control-group">
                        <label for="input01" class="control-label">司机密码</label>
                        <div class="controls">
                            <input type="password" id="password" class="input-xlarge" name="password" onkeypress="return limitString(this)">
                            <p class="help-block"> 字母、数字组合，不超过32个字。 </p>
                        </div>
                    </div>
                    <?php }?>

                    <div class="control-group">
                        <label for="input01" class="control-label">真实姓名</label>
                        <div class="controls">
                            <input type="text" id="realname" class="input-xlarge" name="realname" value="<?=isset ($data['realname']) ? $data['realname'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">性别</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox3" name="usersex" <?=isset($data['sex']) ? ($data['sex'] == 1 ? 'checked="checked"' : '') : 'checked="checked"';?>> 男
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
                            <input type="text" id="phone" class="input-xlarge" name="phone" value="<?=isset ($data['phone']) ? $data['phone'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">身份证码号</label>
                        <div class="controls">
                            <input type="text" id="id_card" class="input-xlarge" name="id_card" value="<?=isset ($data['id_card']) ? $data['id_card'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所在城市</label>
                        <div class="controls">
                            <select id="city" class="span3" name="city">
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
                            <select id="car" class="span3" name="car_type">
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
                            <input type="text" id="car_no" class="input-xlarge" name="car_no" value="<?=isset ($data['car_no']) ? $data['car_no'] : '';?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">服务状态</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" value="1" id="inlineCheckbox1" name="status" <?=isset ($data['status']) ? ($data['status'] == 1 ? 'checked="checked"' : '') : 'checked="checked"';?>> 服务中
                            </label>
                            <label class="radio inline">
                                <input type="radio" value="0" id="inlineCheckbox2" name="status" <?=isset ($data['status']) && $data['status'] == 0 ? 'checked="checked"' : '';?>> 暂停服务
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">司机描述</label>
                        <div class="controls">
                            <textarea rows="3" id="descr" class="input-xlarge" name="descr"><?=isset ($data['descr']) ? $data['descr'] : '';?></textarea>
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
            var loginName = $('#name').val();
            var password = $('#password').val();
            var realName = $('#realname').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            var car = $('#car').val();
            var car_no = $('#car_no').val();
            var id_card = $('#id_card').val();


            if (loginName == '' || loginName == undefined || loginName.length < 6 || loginName.length > 32) {
                alert('登陆名为空或大于32，小于6个字符!');
                return false;
            }
            if (password == '' || password == undefined || password.length < 6 || password.length > 32) {
                //alert('密码为空或大于32，小于6个字符!');
                //return false;
            }
            if (realName == '' || realName == undefined) {
                alert('真实姓名为空');
                return false;
            }

            if (id_card == '' || id_card == undefined || !isIdCard(id_card)) {
                alert('身份证号码为空或格式错误');
                return false;
            }
            if (city == '0' || !city ) {
                alert('请选择所在城市!');
                return false;
            }
            if (car == '0' || !car) {
                alert('请选择车型!');
                return false;
            }
            if (car_no == '' || car_no == undefined) {
                alert('车牌号为空!');
                return false;
            }
            if (phone == '' || phone == undefined || !isMobile(phone)) {
                alert('手机号码为空或格式错误!');
                return false;
            }

            return true;
        }

        //是否为手机号码
        function isMobile(value) {
            if (/^1[3-9]\d{9}$/.test(value)) {
                return true;
            } else {
                return false;
            }
        }

        //是否为身份证号码
        function isIdCard(sId) {console.log(sId);
            var iSum = 0
            var info = ""
            if (!/^\d{17}(\d|x)$/i.test(sId))return false;
            sId = sId.replace(/x$/i, "a");
            if (aCity[parseInt(sId.substr(0, 2))] == null) {
                //alert("Error:非法地区");
                return false;
            }
            sBirthday = sId.substr(6, 4) + "-" + Number(sId.substr(10, 2)) + "-" + Number(sId.substr(12, 2));
            var d = new Date(sBirthday.replace(/-/g, "/"))
            if (sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
                //alert("Error:非法生日");
                return false;
            }
            for (var i = 17; i >= 0; i--) iSum += (Math.pow(2, i) % 11) * parseInt(sId.charAt(17 - i), 11)
            if (iSum % 11 != 1) {
                //alert("Error:非法证号");
                return false;
            }
            //return aCity[parseInt(sId.substr(0,2))]+","+sBirthday+","+(sId.substr(16,1)%2?"男":"女")
            return true;
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
