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
                <h4>消息发送记录列表</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户类别</th>
                    <th>消息类别</th>
                    <th>消息ID</th>
                    <th>消息标题</th>
                    <th>消息内容</th>
                    <th>操作人</th>
                    <th>接收人</th>
                    <th>发送时间</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($record_info as $v){?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$user_type[$v['user_type']];?></td>
                    <td><?=$message_type[$v['types']];?></td>
                    <td><?=$v['mid']?></td>
                    <td><?=$v['title']?></td>
                    <td><?=$v['content']?></td>
                    <td><?=$v['staff_id'] ? (isset($staffData[$v['staff_id']]) ? $staffData[$v['staff_id']]['login_name'] : '系统') : '系统'?></td>
                    <!--td><?=$v['staff_id']?></td-->
                    <td><?=empty($v['recipient']) ? '全体'.$user_type[$v['user_type']] : $v['recipient'];?></td>
                    <td><?=$v['create_time']?></td>
                </tr>
                    <?php }?>
                </tbody>
            </table>
            <div class="pagination pagination-right  well form-inline">
                <strong>推送记录数量:<?=(empty($totalNum) ? '0' : $totalNum)?></strong>
            </div>
            <div class="pagination pagination-right">
                <ul><?php if(isset($pageHtml)) echo $pageHtml;?></ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead();
</script>
<?php require(APPPATH . 'views/footer.php');?>
