<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/user_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/user_subnav.php');?>

            <div class="page-header">
                <h4>用户发票列表</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>user/invoice_index" method="post">

                <input type="text" class="input-medium" placeholder="用户名" data-provide="typeahead" name="uname" value="<?=isset($uname) ? $uname : ''?>">

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>抬头</th>
                    <th>内容</th>
                    <th>创建时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($invoice as $v){?>
                <tr>
                    <td><?=$v['invoice_id']?></td>
                    <td><?=$v['uid']?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=$v['payable']?></td>
                    <td><?=$v['content']?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a href="<?=url('admin')?>user/invoiceDelete/<?=$v['invoice_id']?>" type="删除"><i class="icon-remove"></i></a>
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

        $(document).ready(function() {
            $('#reservation').daterangepicker();
        });

        function changeStatus(t)
        {
            if (t) {
                $('#status')[0].value = 1;
                $('#status_text').text('白名单');
                return;
            }
            $('#status')[0].value = 0;
            $('#status_text').text('黑名单');
        }
    </script>
<?php require(APPPATH . 'views/footer.php');?>
