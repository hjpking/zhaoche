<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/system_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/system_subnav.php');?>

            <div class="page-header">
                <h4>卡模型列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>金额</th>
                    <th>数量</th>
                    <th>是否生成</th>
                    <th>发送数量</th>
                    <th>过期时间</th>
                    <th>描述</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v){?>
                <tr>
                    <td><?=$v['model_id']?></td>
                    <td><?=$v['name']?></td>
                    <td><?=fPrice($v['amount'])?>元</td>
                    <td><?=$v['num']?></td>
                    <td><?=$v['is_genera'] ? '已生成' : '未生成'?></td>
                    <td><?=$v['recent_num']?></td>
                    <td><?=$v['end_time']?></td>
                    <td><?=$v['descr']?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <?php if (!$v['is_genera']){?>
                        <a href="<?=url('admin')?>system/card_model_genera/<?=$v['model_id']?>" type="生成"><i class="icon-play"></i></a>
                        <?php }?>
                        <a href="<?=url('admin')?>system/card_model_edit/<?=$v['model_id']?>" type="修改"><i class="icon-pencil"></i></a>
                        <a href="<?=url('admin')?>system/card_model_delete/<?=$v['model_id']?>" type="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="pagination pagination-right"><ul><?php if(isset($pageHtml)) echo $pageHtml;?></ul></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()
</script>
<?php require(APPPATH . 'views/footer.php');?>
