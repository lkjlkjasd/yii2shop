<?php
/*
 *var $this \Yii\web\view
 * */
$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');

//$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.js',[
//    //解决jquery依赖
//    'depends'=>\yii\web\JqueryAsset::className()
//]);

$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js',[
    //解决jquery依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['rbac/add'])?>">添加</a>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <td>路由</td>
        <td>详情</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($getPermissions as $getPermission):?>
        <tr>
            <td><?php echo $getPermission->name?></td>
            <td><?php echo $getPermission->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['rbac/delete','name'=>$getPermission->name],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('修改',['rbac/edit','name'=>$getPermission->name],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
$this->registerJs(
    <<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
     "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
    });
} );
JS
);
