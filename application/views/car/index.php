<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/car_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/car_subnav.php');?>

            <div class="page-header">
                <h4>车辆列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>所属级别</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>是否为车型</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v) {?>
                <tr>
                    <td><?=$v['car_id']?></td>
                    <td><?=$car_level_data[$v['lid']]['name'];?></td>
                    <td><?php $v['floor'] = isset ($v['floor']) ? $v['floor'] : 0; echo str_repeat("&nbsp;", $v['floor'] * 8), $v['name']?></td>
                    <td><?=$v['descr']?></td>
                    <td><?=$is_car_model[$v['is_car_model']]?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>car/edit/<?=$v['car_id']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>car/delete/<?=$v['car_id']?>" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                <!--tr>
                    <td>1</td>
                    <td>舒适型</td>
                    <td>大众</td>
                    <td>车辆品牌商名称</td>
                    <td>2013-03-07 18:32:23</td>
                    <td>
                        <a title="编辑"><i class="icon-edit"></i></a>
                        <a title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr-->
                </tbody>
            </table>

        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()

    $('#backdroptrue').on('click',function(evt){
        $('#modalbackdroptrue').modal({
            backdrop:true,
            keyboard:true,
            show:true
        });
    });
</script>
<?php require(APPPATH . 'views/footer.php');?>
