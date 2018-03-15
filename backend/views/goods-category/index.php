<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['goods-category/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><?php echo $good->id?></td>
            <td><?php echo $good->name?></td>
            <td><?php echo $good->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$good->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$good->id],['class'=>'btn btn-danger'])?>
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
