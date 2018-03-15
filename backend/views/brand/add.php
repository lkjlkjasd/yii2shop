<?php
/**
 * @var $this yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
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
    console.log(imgUrl);
    // console.log(imgUrl);
    $('#brand-logo').val(imgUrl);
    //图片回显
    $('#logo-view').attr('src',imgUrl)
});
JS
);
//-----------------------JS代码---------------
//---------------------------HTML----------------------
//---------------------------加载文件-----------------

echo "<img id='logo-view' width='120px' src='{$model->logo}'/>";
echo $form->field($model,'sort')->textInput();
echo "<button type='submit' class='btn bg-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();
