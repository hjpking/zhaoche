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
                <h4><?=isset ($isEdit) ? '编辑' : '添加'?>消息</h4>
            </div>

            <form class="form-horizontal" action="<?=url('admin')?>message/save" method="post" onsubmit="return checkForm()">
                <input type="hidden" name="mid" value="<?=isset ($data['mid']) ? $data['mid'] : ''?>"/>
                <fieldset>
                    <div class="control-group">
                        <label for="input01" class="control-label">消息标题</label>
                        <div class="controls">
                            <input type="text" id="title" class="input-xlarge" name="title" value="<?=isset ($data['title']) ? $data['title'] : ''?>">
                            <!--p class="help-block"> 字母、数字组合，不超过32个字符。 </p-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="select01" class="control-label">所属分类</label>
                        <div class="controls">
                            <select id="select02" class="span3" name="cid">
                                <?php foreach ($category_data as $v){?>
                                <option value="<?=$v['cid']?>" <?=isset ($data['cid']) && $data['cid'] == $v['cid'] ? 'selected="selected"' : '';?>>
                                    <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
                                </option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="textarea" class="control-label">消息内容</label>
                        <div class="controls">
                            <textarea rows="3" id="content" class="input-xlarge" name="content" onkeydown="return limitChars('content', 100, 'content_notice')" onkeypress="return limitChars('content', 100, 'content_notice')" size="100" maxlength="100"><?=isset ($data['content']) ? $data['content'] : ''?></textarea>
                            <p class="help-block" id="content_notice"> 消息字数限制为100个字。 </p>
                        </div>
                    </div>


                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">保存更改</button>
                        <button class="btn">取消</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead();

    function checkForm()
    {
        var content = $('#content').val();
        var title = $('#title').val();

        if (title == '' || title == undefined) {
            alert('标题为空！');
            return false;
        }
        if (content == '' || content.length > 100) {
            alert('内容为空或字数超过100个！');
            return false;
        }
        return true;
    }

    /**
     * 动态显示用户输入了多少字
     * textid 文本框的id号
     * limit  限制输入的字数
     * infodiv 显示提示信息的id号
     */
    function limitChars(textid, limit, infodiv){
        var text = jQuery('#'+textid).val();
        var textlength = getstrlength(text);
        if(textlength > limit){
            jQuery('#' + infodiv).html('限 '+limit+' 字符!');
            //jQuery('#'+textid).val(text.substr(0,limit));
            return false;
        }else{
            var leftnum = limit - textlength;
            jQuery('#' + infodiv).html('还可输入'+ leftnum +'字');
            return true;
        }
    }

    /**
     * 获取字符串的长度，半角符号算一个，全角符号算2个
     */
    function getstrlength(s){
        var l = 0;
        var l2 = 0;
        var reg = /[^\x00-\xff]/;//匹配双字节
        for (var i=0;i<s.length;i++){
            if (reg.test(s.charAt(i))){//全角
                l++;
            }else{//半角
                l2++;
            }
        }
        len = Math.ceil(l2/2)+l;
        return len;
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
