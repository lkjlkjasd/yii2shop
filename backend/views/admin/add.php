<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
if($model->getIsNewRecord()){
    echo $form->field($model,'password_hash')->passwordInput();
}
echo $form->field($model,'email')->textInput();
if(!$model->getIsNewRecord()){
    echo $form->field($model,'status',['inline'=>1])->radioList([1=>'禁用',0=>'启用']);
}

echo $form->field($model,'rule',['inline'=>1])->checkboxList(\backend\models\Admin::getRule());
echo "<button type='submit' class='btn bg-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();