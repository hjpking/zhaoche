<ul class="nav nav-tabs nav-stacked">
    <?php $_view_nav_id = 4;?>
    <li class="nav-header font-1em"><?=$_view_nav_conf[$_view_nav_id]['title'];?></li>
    <?php foreach ($_view_nav_conf[$_view_nav_id]['links'] as $v): ?>
    <li><a href="<?=url('admin')?><?=$v['url']?>"><?=$v['title']?></a></li>
    <?php endforeach;?>
</ul>