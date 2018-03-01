<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['article-category/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>logo</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?php echo $row->id?></td>
            <td><?php echo $row->name?></td>
            <td><?php echo $row->intro?></td>
            <td><?php echo $row->sort?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$row->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$row->id],['class'=>'btn btn-danger'])?>
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
