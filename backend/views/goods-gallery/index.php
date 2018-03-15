<?php
//---------------------------加载文件-----------------
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    //解决jquery依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
//---------------------------HTML----------------------
echo <<<HTML
<div id="uploader-demo">
    <!--用来存放文件信息-->
    <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
</div>
HTML;
$upload_url = \yii\helpers\Url::to(['brand/upload']);
$url = \yii\helpers\Url::to(['goods-gallery/index']);
//-----------------------JS代码---------------
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:'/webuploader/Uploader.swf',

    // 文件接收服务端。
    server: '{$upload_url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        //解决某些浏览器选择文件时很慢的问题
        mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function(file,response) {
    var imgUrl = response.url;
    var id = $id;
    data = {
      'id':id,
      'imgUrl':imgUrl
    };
    $.post('{$url}',data,function(data) {
      //   var html = "";
      // $(data).each(function(i,v){
      //   html+='<tr><td><img src="'+v.imgUrl+'" /></td><td>删除</td></tr>';
      //  $('#table').appendTo(html);
      // })
    },'json');
   location.reload();
});
JS
);
?>
<table id="table" class="table">
    <?php foreach ($models as $mod):?>
    <tr>
        <td><img src="<?php echo $mod['path']?>" width="150px"></td>
        <td><?=\yii\bootstrap\Html::a('删除',['goods-gallery/delete','id'=>$mod->id,'goods_id'=>$mod->goods_id],['class'=>'btn btn-info','style'=>'font-size: 12px;float: right'])?></td>
    </tr>
    <?php endforeach;?>
</table>
