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
                <h4>城市列表</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/city/index" method="post">
                <?php
                $str = '[';
                foreach ($city as $v) {
                    $str .= '"'.$v['city_code'].'",';
                }
                $str = substr($str, 0, -1);
                $str .= ']';
                ?>
                <input type="text" class="input-small" placeholder="城市代码" data-provide="typeahead" name="city_code" value="<?=isset($city_code) ? $city_code : '';?>"
                       data-items="4" data-source='<?=$str?>'>

                <!--div class="btn-group">
                    <a href="#" class="btn "> <strong>状态</strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="icon-ok"></i> 服务中</a></li>
                        <li><a href="#"><i class="icon-remove"></i> 暂停服务</a></li>
                    </ul>
                </div-->
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <!--a href="#" class="btn"><i class="icon-download"></i> 导出</a-->
            </form>


            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>城市ID</th>
                    <th>名称</th>
                    <th>城市代码</th>
                    <th>是否为城市</th>
                    <th>描述</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($city_data as $v){ ?>
                <tr>
                    <td><?=$v['city_id'];?></td>
                    <td><?php $v['floor'] = isset ($v['floor']) ? $v['floor'] : 0; echo str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?></td>
                    <td><?=$v['city_code'];?></td>
                    <td><?=$v['is_city'] ? '是' : '否';?></td>
                    <td><?=$v['descr'];?></td>
                    <td><?=$v['create_time'];?></td>
                    <td>
                        <a href="<?=url('admin')?>city/edit/<?=$v['city_id'];?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>city/delete/<?=$v['city_id'];?>" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                <!--tr>
                    <td>2</td>
                    <td>北京市</td>
                    <td>北京</td>
                    <td>BJ</td>
                    <td>2013-01-25 14:23:12</td>
                    <td>
                        <a href="<?=url('admin')?>city/edit/" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>city/delete/" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>北京市</td>
                    <td>北京</td>
                    <td>BJ</td>
                    <td>2013-01-25 14:23:12</td>
                    <td>
                        <a href="<?=url('admin')?>city/edit/" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>city/delete/" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr-->
                </tbody>
            </table>
            <div class="pagination pagination-right">
                <ul><?php if(isset($pageHtml)) echo $pageHtml;?></ul>
              </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()

    $(document).ready(function() {
        $('#reservation').daterangepicker();
    });
</script>
<?php require(APPPATH . 'views/footer.php');?>
