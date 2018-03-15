<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'pemission',['inline'=>1])->checkboxList(\backend\models\RbacForm::getPemission());
echo "<button type='submit' class='btn bg-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();