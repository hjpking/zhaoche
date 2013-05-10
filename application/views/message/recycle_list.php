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

            <form class=" well form-inline" action="<?=url('admin')?>/search" method="post">
                <input type="text" class="input-small" placeholder="消息名称">
                <select id="select01" name="s_type" class="span2">
                    <option value="0" selected="selected">选择所属大分类</option>
                    <option value="名称">用户</option>
                    <option value="类别" >司机</option>
                </select>

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>所属大分类</th>
                    <th>分类</th>
                    <th>消息标题</th>
                    <th>消息内容</th>
                    <th>消息作者</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>用户</td>
                    <td>天气情况</td>
                    <td>空气质量低</td>
                    <td>空气质量低。。。</td>
                    <td>花心油</td>
                    <td>2013-03-05 13:25:35</td>
                    <td>
                        <a href="<?=url('admin')?>message/delete" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>用户</td>
                    <td>天气情况</td>
                    <td>空气质量低</td>
                    <td>空气质量低。。。</td>
                    <td>花心油</td>
                    <td>2013-03-05 13:25:35</td>
                    <td>
                        <a href="<?=url('admin')?>message/delete" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>用户</td>
                    <td>天气情况</td>
                    <td>空气质量低</td>
                    <td>空气质量低。。。</td>
                    <td>花心油</td>
                    <td>2013-03-05 13:25:35</td>
                    <td>
                        <a href="<?=url('admin')?>message/delete" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php if(isset($page)) echo $page;?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.typeahead').typeahead();
</script>
<?php require(APPPATH . 'views/footer.php');?>
