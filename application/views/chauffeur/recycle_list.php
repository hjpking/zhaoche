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
                <h4>司机回收站</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/search" method="post">
                <input type="text" class="input-small" placeholder="用户名" data-provide="typeahead" data-items="4" data-source='["Alabama","Alaska","Arizona","Arkansas"]'>
                <select id="select01" name="s_type" class="span2">
                    <option value="0" selected="selected">选择所在城市</option>
                    <option value="名称">北京市</option>
                    <option value="类别" >上海市</option>
                    <option value="类别" >湖南省</option>
                    <option value="类别" >&nbsp;&nbsp;长沙市</option>
                    <option value="类别" >&nbsp;&nbsp;株洲市</option>
                    <option value="类别" >河北省</option>
                    <option value="类别" >&nbsp;&nbsp;石家庄市</option>
                    <option value="类别" >&nbsp;&nbsp;张家口市</option>
                </select>
                <select id="select02" name="s_type" class="span2">
                    <option value="0" selected="selected">选择车型</option>
                    <option value="名称">大众</option>
                    <option value="类别" >&nbsp;&nbsp;朗逸</option>
                    <option value="类别" >&nbsp;&nbsp;桑塔纳</option>
                    <option value="名称">别克</option>
                    <option value="类别" >&nbsp;&nbsp;君威</option>
                    <option value="类别" >&nbsp;&nbsp;君越</option>
                </select>

                <!--div class="btn-group">
                    <a href="#" class="btn "> <strong>状态</strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="icon-ok"></i> 服务中</a></li>
                        <li><a href="#"><i class="icon-remove"></i> 暂停服务</a></li>
                    </ul>
                </div-->
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <a href="#" class="btn"><i class="icon-download"></i> 导出</a>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>司机ID</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>车型</th>
                    <th>手机号</th>
                    <th>当前状态</th>
                    <th>接单量</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>hjpking</td>
                    <td>花心油</td>
                    <td>大众 捷达</td>
                    <td>15101559313</td>
                    <td>服务中</td>
                    <td>10</td>
                    <td>
                        <a href="<?=url('admin')?>/chauffeur/detail/" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/chauffeur/recycle_delete/" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>hjpking</td>
                    <td>花心油</td>
                    <td>大众 捷达</td>
                    <td>15101559313</td>
                    <td>服务中</td>
                    <td>10</td>
                    <td>
                        <a href="<?=url('admin')?>/chauffeur/detail/" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/chauffeur/recycle_delete/" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>hjpking</td>
                    <td>花心油</td>
                    <td>大众 捷达</td>
                    <td>15101559313</td>
                    <td>服务中</td>
                    <td>10</td>
                    <td>
                        <a href="<?=url('admin')?>/chauffeur/detail/" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>/chauffeur/recycle_delete/" title="删除"><i class="icon-remove"></i></a>
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
