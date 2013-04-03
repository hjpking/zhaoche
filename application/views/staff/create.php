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
                <h4><?=isset ($data['staff_id']) ? '修改员工' : '添加员工';?></h4>
            </div>


            <form class="form-horizontal" action="<?=url('admin');?>staff/save" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="staff_id" value="<?=isset($data['staff_id']) ? $data['staff_id'] : ''?>"/>
                <fieldset>

                    <div class="control-group">
                        <label for="input01" class="control-label">登陆名 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="login_name" id="login_name"
                                   value="<?=isset ($data['login_name']) ? $data['login_name'] : ''?>">
                            <p class="help-block"> 字母、数字组合，不超过32个字符。 </p>
                        </div>
                    </div>

                    <?php if (!isset ($data['staff_id'])){ ?>
                    <div class="control-group">
                        <label for="input01" class="control-label">登陆密码 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <input type="password" id="password" class="input-xlarge" name="password">
                            <p class="help-block"> 字母、数字组合，不超过32个字符。 </p>
                        </div>
                    </div>
                    <?php }?>

                    <div class="control-group">
                        <label for="input01" class="control-label">真实姓名 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="realname" id="realname"
                                   value="<?=isset($data['realname']) ? $data['realname'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">性别 <strong style="color: red;">*</strong></label>

                        <div class="controls">
                            <?php foreach ($user_sex as $k=>$v){?>
                            <label class="radio inline">
                                <input type="radio" value="<?=$k?>" id="3" name="sex" <?=isset ($data['sex']) ? ($data['sex'] == $k ? 'checked="checked"' : '') : ($k == 0 ? 'checked' : '')?>> <?=$v?>
                            </label>
                            <?php }?>
                            <!--label class="radio inline">
                                <input type="radio" value="option2" id="4" name="sex"> 女
                            </label-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">手机号码 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <input type="text" id="phone" class="input-xlarge" name="phone" value="<?=isset($data['phone']) ? $data['phone'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">身份证码号</label>
                        <div class="controls">
                            <input type="text" id="id_card" class="input-xlarge" name="id_card" value="<?=isset ($data['id_card']) ? $data['id_card'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="input01" class="control-label">邮箱 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <input type="text" id="email" class="input-xlarge" name="email" value="<?=isset($data['email']) ? $data['email'] : ''?>">
                            <!--<p class="help-block"> 字母、数字组合，不超过32个字符。 </p>-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属部门 <strong style="color: red;">*</strong></label>
                        <div class="controls">
                            <select id="depart_id" class="span2" name="depart_id">
                                <option value="0">选择所属部门</option>
                                <?php foreach ($departmentInfo as $v){?>
                                <option value="<?=$v['depart_id']?>" <?=isset($data['depart_id']) && $v['depart_id'] == $data['depart_id'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">员工描述</label>
                        <div class="controls">
                            <textarea rows="3" id="textarea" class="input-xlarge" name="descr"><?=isset($data['descr']) ? $data['descr'] : ''?></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">员工权限</label>
                        <div class="controls span4">
                            <table class="table table-hover">
                            <?php
                                foreach ($competence as $k=>$v){
                                    //*
                                    $str = '';
                                    if (isset ($userCompetence)) {
                                        if (array_key_exists($k, $userCompetence)) {
                                            $str = 'checked="checked"';
                                        }
                                    }
                                    //*/
                                ?>
                                <tr style="background-color: #f5f5f5;">
                                    <td><input type="checkbox" value="<?=$k?>" name="competence[]" <?=$str?> onclick="checkAll(<?=$k?>)" class="selects<?=$k?>"></td>
                                    <td><strong><?=$v['title']?></strong> <strong class="pull-right" style="font-size: 10px;" onclick="switchs(<?=$k?>)">展开/收起</strong></td>
                                </tr>

                                <?php foreach ($v['links'] as $lk=>$lv){
                                        $str = '';
                                        if (isset ($userCompetence)) {
                                            if (array_key_exists($lk, $userCompetence)) $str = 'checked="checked"';
                                        }
                                        ?>
                                        <tr class="tr_<?=$k?>" style="display: none;">
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="checkbox" value="<?=$lk?>" name="competence[]" <?=$str?> class="option<?=$k?>" onclick="selectParent(<?=$k?>, this)"></td>
                                            <td><?=$lv['title']?></td>
                                        </tr>
                                <?php }?>


                            <?php }?>
                            </table>
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

    function checkAll(v)
    {
        var currStatus = $(".selects"+v).attr("checked");
        if (currStatus == 'checked') {
            jQuery(".option"+v).attr("checked", true);
        } else {
            jQuery(".option"+v).attr("checked", false);
        }
    }

    function selectParent(v,t)
    {
        var currStatus = t.checked;
        if (currStatus == true) {
            jQuery(".selects"+v).attr("checked", true);
        }
    }

    function checkForm()
    {
        var loginName = $('#login_name').val();
        var password = $('#password').val();
        var realName = $('#realname').val();
        var phone = $('#phone').val();
        var id_card = $('#id_card').val();
        var email = $('#email').val();
        var departId = $('#depart_id').val();


        if (loginName == '' || loginName == undefined) {
            alert('登陆名为空');
            return false;
        }
        //if (password == '' || password == undefined) {
            //alert('密码为空');
            //return false;
        //}
        if (realName == '' || realName == undefined) {
            alert('真实姓名为空');
            return false;
        }
        if (phone == '' || phone == undefined || !isMobile(phone)) {
            alert('手机号码为空或格式错误');
            return false;
        }
        if (email == '' || email == undefined || !isEmail(email)) {
            alert('邮箱地址为空或格式错误');
            return false;
        }
        if (departId == '0' || !departId) {
            alert('请选择部门');
            return false;
        }
        //if (id_card == '' || id_card == undefined || !isIdCard(id_card)) {
            //alert('身份证号码为空或格式错误');
            //return false;
        //}

        return true;
    }
    //是否为邮件地址
    function isEmail( str ){
        var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/;

        return myReg.test(str) ? true : false;
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
    function isIdCard(sId) {
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

    function switchs(v)
    {
        $('.tr_'+v).toggle();
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
