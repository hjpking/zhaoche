<?php require(APPPATH . 'views/header.php');?>
<?php require(APPPATH . 'views/navbar.php');?>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span2">
            <?php require(APPPATH . 'views/left/staff_leftnav.php');?>
        </div>
        <div class="span10">
            <?php require(APPPATH . 'views/sub/staff_subnav.php');?>

            <div class="page-header">
                <h4>订单详情</h4>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>字段</th>
                    <th>信息</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tr>
                    <td></td>
                    <td>登陆名：</td>
                    <td><?=$data['login_name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>真实姓名：</td>
                    <td><?=$data['realname']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>手机：</td>
                    <td> <?=$data['phone']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>身份证号码：</td>
                    <td> <?=$data['id_card']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>邮箱：</td>
                    <td> <?=$data['email']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>性别：</td>
                    <td> <?=$user_sex[$data['sex']]?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>部门：</td>
                    <td> <?=$departmentInfo[$data['depart_id']]['name']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>描述：</td>
                    <td> <?=$data['descr']?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>权限：</td>
                    <td>
                    <?php
                        $i = 1;
                        foreach ($competence as $k=>$v){
                            if (array_key_exists($k, $userCompetence)) {
                                echo $k.'. '.$v['title'].'<br>';
                                $i++;
                            }

                            foreach ($v['links'] as $uck=>$ucv) {
                                if (array_key_exists($uck, $userCompetence)) {
                                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$uck.'.&nbsp;&nbsp;'.$ucv['title'].'&nbsp;&nbsp;<br/>';
                                    $i++;
                                }
                            }
                            echo '<br/>';
                        }
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
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
