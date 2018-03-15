<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['menu/add'])?>">添加</a>
<table class="table table-bordered">
    <tbody>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    </tbody>

    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?php echo $menu->id?></td>
            <td><?php echo $menu->name?></td>
            <td><?php echo $menu->sort?></td>
            <td>
                <button class="btn btn-info" data="<?=$menu->id?>">删除</button>
                <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$ajax_url = \yii\helpers\Url::to(['menu/delete']);
$this->registerJs(
    <<<JS
    $('button').click(function() {
        
        var id=$(this).attr('data');
        var tr=$(this).closest('tr');
        data = {
           'id':id
       };
        if (confirm('确定要删除吗？')){
            
            $.post('{$ajax_url}',data,function(data) {
                // console.log(v);
               if (data.code ==1){
                   tr.remove();
               }
      },'json');
        }
      
    })
JS

);


echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'hideOnSinglePage'=>0
])
?>
