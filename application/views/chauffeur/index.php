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
                <h4><?=$is_del_status ? '司机回收站' : '司机列表'?></h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/chauffeur/index/<?=$is_del_status?>" method="post">
                <?php
                $str = '[';
                foreach ($chauffeur as $v) {
                    $str .= '"'.$v['cname'].'",';
                }
                $str = substr($str, 0, -1);
                $str .= ']';
                ?>
                <input type="hidden" name="status" id="status" value="<?=isset($status) ? $status : ''?>"/>
                <input type="text" class="input-small" placeholder="用户名" name="cname" value="<?=isset ($username) ? $username : '';?>"
                       data-provide="typeahead" data-items="4"
                       data-source='<?=$str?>'>
                <select id="select01" name="city_id" class="span2">
                    <option value="0" selected="selected">选择所在城市</option>
                    <?php foreach ($city as $v){?>
                    <option value="<?=$v['city_id']?>" <?=$v['is_city'] == 1 ? '' : 'disabled="disabled"';?>
                        <?=$v['city_id'] == $city_id ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?>
                    </option>
                    <?php }?>
                </select>
                <select id="select02" name="car_id" class="span2">
                    <option value="0" selected="selected">选择车型</option>
                    <?php foreach ($car as $v){?>
                    <option value="<?=$v['car_id']?>" <?=$v['is_car_model'] == 1 ? '' : 'disabled="disabled"';?>
                        <?=$v['car_id'] == $car_model ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                    </option>
                    <?php }?>
                </select>

                <div class="btn-group">
                    <a href="#" class="btn " data-toggle="dropdown" > <strong id="status_text">
                        <?php
                        if ($status == 1) {
                            echo '正常服务';
                        } else if ($status === '0') {
                            echo '暂停服务';
                        } else {
                            echo '服务状态';
                        }
                        ?></strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="changeStatus(1)"><i class="icon-ok"></i> 正常服务</a></li>
                        <li><a href="javascript:void(0);" onclick="changeStatus(0)"><i class="icon-remove"></i> 暂停服务</a></li>
                    </ul>
                </div>

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
                    <th>所在城市</th>
                    <th>手机号</th>
                    <th>服务状态</th>
                    <th>接单量</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>

                <?php
                if (empty ($car_info)) {
                    echo '<tr>
                            <td colspan="9" style="text-align: center;height: 50px;"><strong>没有相关数据</strong></td>
                          </tr>';
                } else {

                foreach ($car_info as $v){?>
                <tr>
                    <td><?=$v['chauffeur_id']?></td>
                    <td><?=$v['cname']?></td>
                    <td><?=$v['realname']?></td>
                    <td><?=isset ($car[$v['car_id']]) ? $car[$v['car_id']]['name'] : '无'?></td>
                    <td><?=isset ($city[$v['city_id']]) ? $city[$v['city_id']]['city_name'] : '未知'?></td>
                    <td><?=$v['phone']?></td>
                    <td><?=$v['status'] ? '正常服务' : '暂时服务'?></td>
                    <td><?=$v['recent_order']?></td>
                    <td>
                        <a href="<?=url('admin')?>chauffeur/detail/<?=$v['chauffeur_id']?>" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>chauffeur/edit/<?=$is_del_status?>/<?=$v['chauffeur_id']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>chauffeur/<?=$is_del_status ? 'recycle_delete' : 'delete'?>/<?=$v['chauffeur_id']?>" title="删除"><i class="icon-remove"></i></a>
                        <?php if ($is_del_status){?>
                        <a href="<?=url('admin')?>chauffeur/restore/<?=$v['chauffeur_id']?>" type="恢复"><i class="icon-share-alt"></i></a>
                        <?php }?>
                    </td>
                </tr>
                <?php } }?>
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

        function changeStatus(t)
        {
            if (t) {
                $('#status')[0].value = 1;
                $('#status_text').text('正常服务');
                return;
            }
            $('#status')[0].value = 0;
            $('#status_text').text('暂停服务');
        }
    </script>
<?php require(APPPATH . 'views/footer.php');?>