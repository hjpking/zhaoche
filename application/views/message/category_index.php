<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/message_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/message_subnav.php');?>

            <div class="page-header">
                <h4>消息列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>分类名称</th>
                    <th>分类描述</th>
                    <th>创建时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v){?>
                <tr>
                    <td><?=$v['cid']?></td>
                    <td><?php $v['floor'] = isset ($v['floor']) ? $v['floor'] : 0; echo str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?></td>
                    <td><?=$v['descr']?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>message/category_edit/<?=$v['cid']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>message/category_delete/<?=$v['cid']?>" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
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
