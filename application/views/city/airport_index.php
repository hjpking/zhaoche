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
                <h4>机场列表</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/city/airport_index" method="post">
                <select id="select01" name="city_id" class="span2">
                    <option value="0" selected="selected">选择所在城市</option>
                    <?php foreach ($city as $v){?>
                        <option value="<?=$v['city_id']?>" <?=$v['is_city'] == 1 ? '' : 'disabled="disabled"';?>
                            <?=$v['city_id'] == $city_id ? 'selected="selected"' : '';?>>
                            <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?>
                        </option>
                    <?php }?>
                </select>
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

                <!--a href="#" class="btn"><i class="icon-download"></i> 导出</a-->
            </form>


            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>所属城市</th>
                    <th>名称</th>
                    <th>经度</th>
                    <th>纬度</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($useful_data as $v){ ?>
                    <tr>
                        <td><?=$v['id'];?></td>
                        <td><?=$city[$v['city_id']]['city_name'];?></td>
                        <td><?=$v['airport_name'];?></td>
                        <td><?=$v['longitude'];?></td>
                        <td><?=$v['latitude'];?></td>
                        <td>
                            <a href="<?=url('admin')?>city/airport_edit/<?=$v['id'];?>" title="编辑"><i class="icon-edit"></i></a>
                            <a href="<?=url('admin')?>city/airport_delete/<?=$v['id'];?>" title="删除"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                <?php }?>
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
