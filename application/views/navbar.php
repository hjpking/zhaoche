<?php
//$_view_nav_conf = config_item('view_nav');//$this->competenceList;
$_view_nav_conf = $this->competenceList;
?>
<!--头部靠航开始-->
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="<?=url('admin')?>">智能招车管理系统 </a>
            <div class="nav-collapse">
                <p class="navbar-text pull-right">欢迎您！
                    <a href="<?=url('admin')?>system/profile"><i class="icon-user"></i><?=$this->amInfo['realname'];?> </a>&nbsp;&nbsp;&nbsp;
                    <a href="<?=url('admin')?>login/logout">退出</a>
                </p>
                <ul class="nav">
                    <li class="active"><a href="<?=url('admin')?>index/index">首页</a></li>
                    <?php foreach($_view_nav_conf as $item):?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?=$item['title']?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php foreach($item['links'] as $v):?>
                            <li><a href="<?php echo url('admin'),$v['url']?>"><?=$v['title']?></a></li>
                            <?php endforeach;?>
                            <!--分割线 li class="divider"></li-->
                            <!--li class="nav-header">导航头</li-->
                        </ul>
                    </li>
                    <?php endforeach;?>
                </ul>

            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<!--头部靠航结束-->