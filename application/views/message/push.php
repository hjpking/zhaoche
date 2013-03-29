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
                <h4>消息推送</h4>
            </div>

            <form class=" well form-inline" action="<?=url('admin')?>/message/send" method="post" onsubmit="return checkForm()">
                <select class="span2" name="user_type">
                    <?php foreach ($user_type as $k=>$v) {?>
                    <option value="<?=$k?>"><?=$v?></option>
                    <?php }?>
                </select>
                <select class="span2" name="type">
                    <?php foreach ($message_type as $k=>$v) {?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?php }?>
                </select>

                <select class="span2" onchange="getMessageBycId(this.value)" name="cid" id="cid">
                    <option value="0" selected="selected">选择所属分类</option>
                    <?php foreach ($category as $v){?>
                    <option value="<?=$v['cid']?>" <?=isset ($data['cid']) && $data['cid'] == $v['cid'] ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                    </option>
                    <?php }?>
                </select>

                <select id="message" name="mid" class="span2">
                    <option value="0">选择消息</option>
                </select>

                <br/><br/>
                <textarea placeholder="输入用户/司机账号，以逗号分隔，例如：hjpking,goodboy,thinkpeople。如不输入将给所选组中所有人发送。"
                          style="width: 436px; height: 149px;" name="recipient"></textarea>

                <br/><br/>
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 发送</button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getMessageBycId(cid)
    {
        if (cid == '' || cid == undefined) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/message/getMessageBycId",
            dataType:'json',
            data: "cid="+cid,
            success: function(msg){
                if (msg['error'] == '0') {
                    $('#message').html('<option value="0" selected="selected">选择所属分类</option>');
                    return ;
                }
                var html = '';
                for(var i in msg) {
                    html += '<option value="'+msg[i]['mid']+'">'+msg[i]['title']+'</option>'
                }

                $('#message').html(html);
            }
        });
    }

    function checkForm()
    {
        var cid = $('#cid').val();
        var message = $('#message').val();

        if (cid == '' || cid == undefined || cid == '0') {
            alert('请选择分类！');
            return false;
        }
        if (message == '' || message == undefined || message == '0') {
            alert('请选择消息！');
            return false;
        }

        return true;
    }
</script>


<?php require(APPPATH . 'views/footer.php');?>
