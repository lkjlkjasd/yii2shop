<?php
/**
 * @var $this yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
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
    $("#goodscategory-parent_id").val(treeNode.id)
}
	}
	
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodels};
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
      zTreeObj.expandAll(true);
       //回显选中的节点
        zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}", null));
JS
);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

//----------------------------JS-----------------
echo $form->field($model,'intro')->textarea();
echo "<button type='submit' class='btn bg-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();