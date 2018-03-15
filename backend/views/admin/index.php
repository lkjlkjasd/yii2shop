<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['admin/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>登录时间</td>
        <td>登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr>
            <td><?php echo $admin->id?></td>
            <td><?php echo $admin->username?></td>
            <td><?php echo $admin->email?></td>
            <td><?php echo $admin->status==1?'禁用':'启用'?></td>
            <td><?php echo date('Y-m-d H:i:s',$admin->last_login_time)?></td>
            <td><?php echo long2ip($admin->last_login_ip)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$admin->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$admin->id],['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('重置密码',['admin/pass','id'=>$admin->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'hideOnSinglePage'=>0
])
?>
