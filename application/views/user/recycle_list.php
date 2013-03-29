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
                <h4>用户回收站</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/search" method="post">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span><input type="text" name="reservation" id="reservation" value="选择用户注册开始和结束时间">
                </div>

                <input type="text" class="input-medium" placeholder="手机号码" data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska","Arizona","Arkansas"]'>
                <div class="btn-group">
                    <a href="#" class="btn "> <strong>状态</strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="icon-ok"></i> 白名单</a></li>
                        <li><a href="#"><i class="icon-remove"></i> 黑名单</a></li>
                    </ul>
                </div>

                <!--

                <select id="select02" name="s_type" class="span2">
                    <option value="0" selected="selected">选择车型</option>
                    <option value="名称">大众</option>
                    <option value="类别" >&nbsp;&nbsp;朗逸</option>
                    <option value="类别" >&nbsp;&nbsp;桑塔纳</option>
                    <option value="名称">别克</option>
                    <option value="类别" >&nbsp;&nbsp;君威</option>
                    <option value="类别" >&nbsp;&nbsp;君越</option>
                </select>

                <div class="btn-group">
                    <a href="#" class="btn "> <strong>状态</strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="icon-ok"></i> 服务中</a></li>
                        <li><a href="#"><i class="icon-remove"></i> 暂停服务</a></li>
                    </ul>
                </div>
                -->
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <a href="#" class="btn"><i class="icon-download"></i> 导出</a>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>手机号</th>
                    <th>绑定类型</th>
                    <th>余额</th>
                    <th>用户状态</th>
                    <th>注册时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>马如来</td>
                    <td>欧阳如来</td>
                    <td>15101559313</td>
                    <td>支付宝</td>
                    <td>10</td>
                    <td>黑名单</td>
                    <td>2013-01-25 14:23:25</td>
                    <td>
                        <a href="<?=url('admin')?>/user/detail/"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/user/del/"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>大佛爷</td>
                    <td>刘佛佛</td>
                    <td>15133256598</td>
                    <td>银行卡</td>
                    <td>15</td>
                    <td>白名单</td>
                    <td>2013-01-25 13:23:25</td>
                    <td>
                        <a href="<?=url('admin')?>/user/detail/"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/user/del/"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>牛牛</td>
                    <td>牛大春</td>
                    <td>13600128848</td>
                    <td>银行卡</td>
                    <td>52</td>
                    <td>白名单</td>
                    <td>2013-01-25 13:21:25</td>
                    <td>
                        <a href="<?=url('admin')?>/user/detail/"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/user/del/"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php if(isset($page)) echo $page;?>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $('.typeahead').typeahead()
    </script>
<?php require(APPPATH . 'views/footer.php');?>
