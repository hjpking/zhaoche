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
                <h4><?=$isDelStatus ? '消息回收站' : '消息列表';?></h4>
            </div>
            <?php
            $message_code = '';
            foreach ($messageData as $v) {
                $message_code .= '"'.$v['title'].'",';
            }
            $message_code = substr($message_code, 0, -1);
            ?>
            <form class=" well form-inline" action="<?=url('admin')?>message/index/<?=$isDelStatus?>" method="post">
                <input type="text" class="input-small" placeholder="消息标题" name="title" value="<?=isset($title) ? $title : ''?>"
                       data-provide="typeahead" data-items="4" data-source='[<?=$message_code?>]'>
                <select id="select01" name="category_id" class="span2">
                    <option value="0">选择所属分类</option>
                    <?php foreach ($category_data as $v){?>
                    <option value="<?=$v['cid']?>" <?=isset ($cid) && $cid == $v['cid'] ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                    </option>
                    <?php }?>
                </select>

                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>分类</th>
                    <th>消息标题</th>
                    <th>消息内容</th>
                    <th>消息作者</th>
                    <th>添加时间</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($message_info as $v){?>
                <tr>
                    <td><?=$v['mid']?></td>
                    <td><?=$v['cid'] ? $category_data[$v['cid']]['name'] : '';?></td>
                    <td><?=$v['title']?></td>
                    <td><?=$v['content']?></td>
                    <td><?=$v['staff_id'] ? (isset($staffData[$v['staff_id']]) ? $staffData[$v['staff_id']]['login_name'] : '系统') : '系统'?></td>
                    <td><?=$v['create_time']?></td>
                    <td>
                        <a title="查看" class="btn btn-primary btn-mini backdroptrue" onclick="viewMessage(<?=$v['mid']?>)"><i class="icon-eye-open icon-white"></i></a>
                        <a href="<?=url('admin')?>message/edit/<?=$v['mid']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="javascript:opera('<?=url('admin')?>message/<?=$isDelStatus ? 'recycle_delete' : 'delete'?>/<?=$v['mid']?>');" title="删除"><i class="icon-remove"></i></a>
                        <?php if ($isDelStatus){?>
                            <a href="javascript:opera('<?=url('admin')?>message/restore/<?=$v['mid']?>');" type="恢复"><i class="icon-share-alt"></i></a>
                        <?php }?>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="pagination pagination-right  well form-inline">
                <strong>消息数量:<?=(empty($totalNum) ? '0' : $totalNum)?></strong>
            </div>
            <div class="pagination pagination-right">
                <ul><?php if (isset($pageHtml)) echo $pageHtml;?></ul>
            </div>
        </div>
    </div>
</div>



<div id="modalbackdroptrue" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3 id="title">消息标题</h3>
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
    $('.typeahead').typeahead();

    $('.backdroptrue').on('click',function(evt){
        $('#modalbackdroptrue').modal({
            backdrop:true,
            keyboard:true,
            show:true
        });
    });

    function viewMessage(mId)
    {
        $.ajax({
            type: "POST",
            url: "/message/detail",
            dataType:'json',
            data: "mid="+mId,
            success: function(msg){
                $('#title').html(msg.title);
                $('#content').html('<p>'+msg.content+'</p>');
                //alert( "Data Saved: " + msg );
            }
        });
    }

    function opera(url)
    {
        if (url == '') return false;

        if (window.confirm('确定操作!')) {
            goToUrl(url);
        }
    }

    function goToUrl (url)
    {
        //url = wx.base_url+url;

        url = url.split('#');
        url = url[0];
        /*
         if (wx.isUrl(url) ) {
         alert ('不是一个正确的URL地址!');
         return false;
         }
         //*/

        window.location.href = url;
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
