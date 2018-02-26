<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>logo</td>
        <td>操作</td>
    </tr>
    <?php foreach ($brands as $brand):?>
        <tr>
            <td><?php echo $brand->id?></td>
            <td><?php echo $brand->name?></td>
            <td><?php echo $brand->intro?></td>
            <td><img src="<?php echo $brand->logo?>" width="80px"></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-danger'])?>
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
