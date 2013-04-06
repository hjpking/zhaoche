<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/feedback_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/feedback_subnav.php');?>

            <div class="page-header">
                <h4>投诉建议列表</h4>
            </div>

            <?php
            $username_code = '';
            foreach ($userData as $v) {
                $username_code .= '"'.$v['uname'].'",';
            }
            $username_code = substr($username_code, 0, -1);
            ?>
            <form class=" well form-inline" action="<?=url('admin')?>/feedback/index" method="post">
                <input type="hidden" name="category_id" id="category_id" value="<?=isset($categoryId) ? $categoryId : ''?>"/>
                <input type="hidden" name="process_status" id="process_status" value="<?=isset($processStatus) ? $processStatus : ''?>"/>

                <input type="text" class="input-small" placeholder="用户名" data-provide="typeahead" name="uname" value="<?=isset($uName) ? $uName : ''?>"
                       data-items="4" data-source='[<?=$username_code?>]'>

                <div class="input-prepend">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                    <input type="text" id="reservation" name="time" placeholder="选择订单开始与结束时间" value="<?=isset($time) ? $time : ''?>">
                </div>

                <div class="btn-group">
                    <a href="#" class="btn " data-toggle="dropdown"> <strong id="category_id_text">
                        <?php
                        $categoryText = '全部';
                        if ($categoryId == '1') {
                            $categoryText = '投诉';
                        } elseif ($categoryId == '2') {
                            $categoryText = '建议';
                        }
                        echo $categoryText;
                        ?>
                        </strong> </a>
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="changeCategory()"><i class="icon-ok"></i> 全部</a></li>
                        <li><a href="javascript:void(0);" onclick="changeCategory(1)"><i class="icon-ok"></i> 投诉</a></li>
                        <li><a href="javascript:void(0);" onclick="changeCategory(2)"><i class="icon-remove"></i> 建议</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <a href="javascript:void(0);" class="btn " data-toggle="dropdown"> <strong id="process_status_text">
                        <?php
                        $processText = '全部';
                        if ($processStatus === '0') {
                            $processText = '未处理';
                        } elseif ($processStatus === '1') {
                            $processText = '已处理';
                        }
                        echo $processText;
                        ?>
                        </strong> </a>
                    <a href="javascript:void(0);" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="processStatus()"><i class="icon-ok"></i> 全部</a></li>
                        <li><a href="javascript:void(0);" onclick="processStatus(0)"><i class="icon-ok"></i> 未处理</a></li>
                        <li><a href="javascript:void(0);" onclick="processStatus(1)"><i class="icon-remove"></i> 已处理</a></li>
                    </ul>
                </div>

                <!--div class="btn-group">
                    <a href="#" class="btn "> <strong>状态</strong> </a>
                    <a href="#" data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="icon-ok"></i> 服务中</a></li>
                        <li><a href="#"><i class="icon-remove"></i> 暂停服务</a></li>
                    </ul>
                </div-->
                <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> 搜索</button>
            </form>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>订单ID</th>
                    <th>分类</th>
                    <th>用户类型</th>
                    <th>用户名称</th>
                    <th>手机号码</th>
                    <th>描述</th>
                    <th>提交时间</th>
                    <th>处理状态</th>
                    <th>处理结果</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($feedback as $v) {?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$v['order_sn']?></td>
                    <td><?=$feedback_category[$v['category_id']]?></td>
                    <td><?=$user_type[$v['user_type']]?></td>
                    <td><?=$v['uname']?></td>
                    <td><?=$v['phone']?></td>
                    <td><?=$v['descr']?></td>
                    <td><?=$v['create_time']?></td>
                    <td><?=$process_status[$v['process_status']]?></td>
                    <td><?=$v['process_result']?></td>
                    <td>
                        <a title="处理" id="backdroptrue" class="btn btn-primary btn-mini"><i class="icon-wrench icon-white"></i></a>
                    </td>
                </tr>
                <?php }?>
                </tbody>
            </table>
            <?php if(isset($page)) echo $page;?>
        </div>
    </div>
</div>


<div id="modalbackdroptrue" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>处理投诉建议</h3>
    </div>
    <div class="modal-body">
        <p>
            <textarea name="process_result" style="width: 506px; height: 106px;"></textarea>
        </p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" >关闭</a>
        <a href="#" class="btn btn-primary">处理</a>
    </div>
</div>


<script type="text/javascript">
    $('.typeahead').typeahead()
    $(document).ready(function() {
        $('#reservation').daterangepicker();
    });

    $('#myModal').modal({
        backdrop:true,
        keyboard:true,
        show:true
    });

    $('#backdroptrue').on('click',function(evt){
        $('#modalbackdroptrue').modal({
            backdrop:true,
            keyboard:true,
            show:true
        });
    });

    function changeCategory(t)
    {
        switch (t) {
            case 1:
                $('#category_id')[0].value = 1;
                $('#category_id_text').text('投诉');
                break;
            case 2:
                $('#category_id')[0].value = 2;
                $('#category_id_text').text('建议');
                break;
            default :
                $('#category_id')[0].value = '';
                $('#category_id_text').text('全部');
                break;
        }
    }

    function processStatus(t)
    {
        switch (t) {
            case 1:
                $('#category_id')[0].value = 1;
                $('#category_id_text').text('已处理');
                break;
            case 0:
                $('#category_id')[0].value = 1;
                $('#category_id_text').text('未处理');
                break;
            default :
                $('#process_status')[0].value = '';
                $('#process_status_text').text('全部');
                break;
        }
    }
</script>
<?php require(APPPATH . 'views/footer.php');?>
