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
                <h4>卡列表</h4>
            </div>
            <form class=" well form-inline" action="<?=url('admin')?>system/card_index" method="post">
                <input type="text" class="input-small" placeholder="卡号" data-provide="typeahead" name="card_no" value="<?=isset($card_no) ? $card_no : ''?>">
                <select id="select01" name="model_id" class="span2">
                    <option value="0">选择卡模型</option>
                    <?php foreach ($card_model as $v){?>
                    <option value="<?=$v['model_id']?>" <?=isset ($model_id) && $v['model_id'] == $model_id ? 'selected="selected"' : '';?>>
                        <?=$v['name'];?>
                    </option>
                    <?php }?>
                    <!--option value="类别">产品部</option>
                    <option value="类别">财务部</option-->
                </select>
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>

            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>卡号</th>
                    <th>卡模型</th>
                    <th>金额</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>过期时间</th>
                    <th>生成时间</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($card_data as $v) {?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$v['card_no']?></td>
                    <td><?=$card_model[$v['model_id']]['name']?></td>
                    <td><?=fPrice($v['amount'])?> 元</td>
                    <td><?=$v['uid']?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=$v['end_time']?></td>
                    <td><?=$v['create_time']?></td>
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
