<?php require(APPPATH . 'views/header.php');?>
<div class="container">
    <div class="page-header">
        <h3>智能招车管理系统</h3>
    </div>
    <br/><br/><br/>
    <form class="form-inline" action="<?=url('admin')?>login/doLogin" method="post">
        <i class="icon-user"></i> <input name="username" type="text" class="input-large" placeholder="用户名">
        <input name="password" type="password" class="input-large" placeholder="密码">
        <!--input name="verify_code" type="text" class="input-small" placeholder="验证码">
        <img style="cursor:pointer;" src="<?=url('admin')?>index/verifyCode?<?=mt_rand()?>" width="80" height="30"-->
        <button type="submit" class="btn btn-primary">登录</button>
    </form>
    <br/><br/><br/>
    <hr/>
<?php require(APPPATH . 'views/footer.php');?>
</div>
