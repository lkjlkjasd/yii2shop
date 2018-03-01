<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['article-category/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>文章分类</td>
        <td>排序</td>
        <td>时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?php echo $article->id?></td>
            <td><?php echo $article->name?></td>
            <td><?php echo $article->intro?></td>
            <td><?php echo $article->articleCategory->name?></td>
            <td><?php echo $article->sort?></td>
            <td><?php echo date('Y-m-d H:i:s',$article->create)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('查看',['article/see','id'=>$article->id],['class'=>'btn btn-success'])?>
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
