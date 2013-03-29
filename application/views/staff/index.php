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
                <h4><?=$isDelStatus ? '员工回收站' : '员工列表';?></h4>
            </div>
            <?php
            $str = '[';
            foreach ($staffData as $v) {
                $str .= '"'.$v['realname'].'",';
            }
            $str = substr($str, 0, -1);
            $str .= ']';
            ?>
            <form class=" well form-inline" action="<?=url('admin')?>/staff/index" method="post">
                <input type="text" class="input-small" placeholder="员工真实姓名" data-provide="typeahead" name="realname" value="<?=isset($realname) ? $realname : ''?>"
                       data-items="4" data-source='<?=$str?>'>
                <select id="select01" name="depart_id" class="span2">
                    <option value="0">选择所属部门</option>
                    <?php foreach ($departmentInfo as $v){?>
                    <option value="<?=$v['depart_id']?>" <?=$departId && $v['depart_id'] == $departId ? 'selected="selected"' : '';?>>
                        <?=str_repeat("&nbsp;", $v['floor'] * 8), $v['name'];?>
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
                    <th>登陆名</th>
                    <th>部门</th>
                    <th>真实姓名</th>
                    <th>性别</th>
                    <th>手机号</th>
                    <th>邮箱</th>
                    <th>操作</th>
                <tr>
                </thead>
                <tbody>
                <?php foreach ($staff as $v) {?>
                <tr>
                    <td><?=$v['staff_id']?></td>
                    <td><?=$v['login_name']?></td>
                    <td><?=isset ($departmentInfo[$v['depart_id']]) ? $departmentInfo[$v['depart_id']]['name'] : '';?></td>
                    <td><?=$v['realname']?></td>
                    <td><?=$user_sex[$v['sex']]?></td>
                    <td><?=$v['phone']?></td>
                    <td><?=$v['email']?></td>
                    <td>
                        <a href="<?=url('admin')?>staff/detail/<?=$v['staff_id']?>" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>staff/edit/<?=$v['staff_id']?>" title="编辑"><i class="icon-edit"></i></a>
                        <a href="javascript:opera('<?=url('admin')?>staff/<?=$isDelStatus ? 'recycle_delete' : 'delete'?>/<?=$v['staff_id']?>')" title="删除"><i class="icon-remove"></i></a>
                        <?php if ($isDelStatus){?>
                        <a href="javascript:opera('<?=url('admin')?>/staff/restore/<?=$v['staff_id']?>')" type="恢复"><i class="icon-share-alt"></i></a>
                        <?php }?>
                    </td>
                </tr>
                <?php }?>
                <!--tr>
                    <td>1</td>
                    <td>hjpking</td>
                    <td>产品部</td>
                    <td>花心油</td>
                    <td>男</td>
                    <td>15101559313</td>
                    <td>hjpking@gmail.com</td>
                    <td>
                        <a href="<?=url('admin')?>staff/detail" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>staff/edit" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>staff/recycle_delete" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>hjpking</td>
                    <td>产品部</td>
                    <td>花心油</td>
                    <td>男</td>
                    <td>15101559313</td>
                    <td>hjpking@gmail.com</td>
                    <td>
                        <a href="<?=url('admin')?>staff/detail" title="查看"><i class="icon-eye-open"></i></a>
                        <a href="<?=url('admin')?>staff/edit" title="编辑"><i class="icon-edit"></i></a>
                        <a href="<?=url('admin')?>staff/recycle_delete" title="删除"><i class="icon-remove"></i></a>
                    </td>
                </tr-->
                </tbody>
            </table>
            <?php if(isset($page)) echo $page;?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.typeahead').typeahead()

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
