<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
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
$upload_url = \yii\helpers\Url::to(['goods/img-upload']);
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
    $('#goods-logo').val(imgUrl);
    //图片回显
    $('#logo-view').attr('src',imgUrl)
});
JS
);
//-----------------------JS代码---------------
//---------------------------HTML----------------------
//---------------------------加载文件-----------------
echo "<img id='logo-view' width='120px' src=''/>";
echo $form->field($model,'goods_category_id')->hiddenInput();
//----------------------------加载文件-----------
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    //解决jquery依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
//----------------------------加载文件-----------
//----------------------------JS-----------------
$this->registerJs(
    <<<JS
var zTreeObj;
// zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		}
	},
	callback:{
		onClick:function(event, treeId, treeNode) {
    $("#goods-goods_category_id").val(treeNode.id)
}
	}
	
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodels};
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      zTreeObj.expandAll(true);
       //回显选中的节点
        zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null));
JS
);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

//----------------------------JS-----------------
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::addBrand());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'sort')->textInput();
echo $form->field($content,'content')->widget(\kucha\ueditor\UEditor::className());
echo "<button type='submit' class='btn bg-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();