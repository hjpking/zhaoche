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
                <h4>车辆级别列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>价格</th>
                    <th>描述</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v) {?>
                <tr>
                    <td><?=$v['lid']?></td>
                    <td><?=$v['name']?></td>
                    <td><?=fPrice($v['price'])?> 元</td>
                    <td><?=$v['descr']?></td>
                    <td>
                        <a href="<?=url('admin')?>car/car_level_edit/<?=$v['lid']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>car/car_level_delete/<?=$v['lid']?>" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                <!--tr>
                    <td>2</td>
                    <td>舒适型</td>
                    <td>舒适型</td>
                    <td>
                        <a href="<?=url('admin')?>/chauffeur/edit/" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>/chauffeur/recycle_delete/" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr-->
                </tbody>
            </table>

        </div>
    </div>
</div>

<div id="modalbackdroptrue" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>修改车辆级别</h3>
    </div>
    <div class="modal-body">
        <p>
        <form class="form-horizontal" action="" method="post">
            <fieldset>
                <div class="control-group">
                    <label for="input01" class="control-label">车辆级别名称</label>
                    <div class="controls">
                        <input type="text" id="input01" class="input-xlarge">
                        <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                    </div>
                </div>

                <div class="control-group">
                    <label for="textarea" class="control-label">车辆级别描述</label>
                    <div class="controls">
                        <textarea rows="3" id="textarea" class="input-xlarge"></textarea>
                    </div>
                </div>
            </fieldset>
        </form>
        </p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" >取消</a>
        <a href="#" class="btn btn-primary">保存更改</a>
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
