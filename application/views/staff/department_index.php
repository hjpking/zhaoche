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
                <h4>部门列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v) {?>
                <tr>
                    <td><?=$v['depart_id']?></td>
                    <td><?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?></td>
                    <td><?=$v['descr']?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>staff/department_edit/<?=$v['depart_id']?>" title="编辑"><i class="icon-edit"></i></a>
                        <!--a href="<?=url('admin')?>staff/department_delete/<?=$v['depart_id']?>" title="删除"><i class="icon-remove"></i></a-->
                        <a href="javascript:deleteDepartment(<?=$v['depart_id']?>)" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <?php if(isset($page)) echo $page;?>
        </div>
    </div>
</div>

<div id="modalbackdroptrue" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3 id="title">错误提示消息</h3>
    </div>
    <div class="modal-body" id="content">
        <p>
            <!--img src="" title="加载中..."/-->
        <div class="progress progress-striped active">
            <div class="bar" style="width: 40%;"></div>
        </div>
        </p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" >关闭</a>
        <!--a href="#" class="btn btn-primary">处理</a-->
    </div>
</div>

<script type="text/javascript">
    $('.typeahead').typeahead()

    $(document).ready(function() {
        $('#reservation').daterangepicker();
    });

    function deleteDepartment(dId)
    {
        if (window.confirm('确定操作！')) {
            $.ajax({
                type: "POST",
                url: "/staff/department_delete",
                dataType:'json',
                data: "did="+dId,
                success: function(msg){
                    //var code = msg['code'];
                    if ( msg['code'] != '0') {
                        $('#content').html('<p>'+msg['msg']+'</p>');
                        $('#modalbackdroptrue').modal({
                            backdrop:true,
                            keyboard:true,
                            show:true
                        });
                        return false;
                    }
                    pageReload();
                }
            });
        }
    }

    function pageReload(time)
    {
        if (time) {
            time = time * 1000;
            setTimeout('window.location.reload()', time);
        } else {
            window.location.reload();
        }
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
