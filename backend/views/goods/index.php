<?php

?>
<form action="<?php \yii\helpers\Url::to(['goods/index'])?>" method="get">
    <input type="text" name="name" placeholder="商品号">
    <input type="text" name="sn" placeholder="货号">
    <input type="text" name="start" placeholder="￥">
    <input type="text" name="end" placeholder="￥">
    <input type="submit" value="搜索" class="btn btn-success"/>
</form>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['goods/add'])?>">添加</a>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>货号</td>
        <td>商品分类</td>
        <td>logo</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>浏览次数</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><?php echo $good->id?></td>
            <td><?php echo $good->name?></td>
            <td><?php echo $good->sn?></td>
            <td><?php echo $good->goods_category_id==0?'顶级分类':$good->goods->name?></td>
            <td><img src="<?php echo $good->logo?>" width="80px"></td>
            <td><?php echo $good->brand->name?></td>
            <td><?php echo $good->market_price?></td>
            <td><?php echo $good->shop_price?></td>
            <td><?php echo $good->stock?></td>
            <td><?php echo $good->is_on_sale==1?'在售':'下架'?></td>
            <td><?php echo $good->sort?></td>
            <td><?php echo date('Y-m-d H:i:s',$good->create_time)?></td>
            <td><?php echo $good->view_times?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$good->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('查看',['goods/see','id'=>$good->id],['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('相册',['goods-gallery/index','id'=>$good->id],['class'=>'btn btn-success'])?>
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
