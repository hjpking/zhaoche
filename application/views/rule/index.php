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
                <h4>计费规则列表</h4>
            </div>
            <form class=" well form-inline" action="<?=url('admin')?>rule/index" method="post">
                <select id="select01" name="city_id" class="span2">
                    <option value="0" selected="selected">选择所在城市</option>
                    <?php foreach ($cityInfo as $v){?>
                    <option value="<?=$v['city_id']?>" <?=$v['is_city'] == 1 ? '' : 'disabled="disabled"';?>
                        <?=$v['city_id'] == $city_id ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['city_name'];?>
                    </option>
                    <?php }?>
                </select>
                <select id="select02" name="sid" class="span2">
                    <option value="0" selected="selected">选择所属服务</option>
                    <?php foreach ($sf_info as $v){?>
                    <option value="<?=$v['sid']?>"
                        <?=$v['sid'] == $sId ? 'selected="selected"' : '';?>>
                        <?=$v['name'];?>
                    </option>
                    <?php }?>
                </select>
                <select id="select02" name="lid" class="span2">
                    <option value="0" selected="selected">选择车辆级别</option>
                    <?php foreach ($carLevelInfo as $v){?>
                    <option value="<?=$v['lid']?>"
                        <?=$v['lid'] == $lId ? 'selected="selected"' : '';?>>
                        <?=$v['name'];?>
                    </option>
                    <?php }?>
                </select>
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>所属城市</th>
                    <th>所属服务</th>
                    <th>车辆级别</th>
                    <th>基础价格</th>
                    <th>公里单价</th>
                    <th>时间单价</th>
                    <th>夜间服务费</th>
                    <th>空驶费</th>
                    <th>描述</th>
                    <th>创建时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($rule_info as $v) {?>
                <tr>
                    <td><?=$v['rule_id']?></td>
                    <td><?=$cityInfo[$v['city_id']]['city_name']?></td>
                    <td><?=$sf_info[$v['sid']]['name']?></td>
                    <td><?=$carLevelInfo[$v['lid']]['name']?></td>
                    <td><?=fPrice($v['base_price'])?> 元</td>
                    <td><?=fPrice($v['km_price'])?> 元</td>
                    <td><?=fPrice($v['time_price'])?> 元(<?=$v['time']?>分钟)</td>
                    <td><?=fPrice($v['night_service_charge'])?>元</td>
                    <td><?=fPrice($v['kongshi_fee'])?>元</td>
                    <td><?=$v['descr']?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>rule/edit/<?=$v['rule_id']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>rule/delete/<?=$v['rule_id']?>" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="pagination pagination-right  well form-inline">
                <strong>计费规则数量:<?=(empty($totalNum) ? '0' : $totalNum)?></strong>
            </div>
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
