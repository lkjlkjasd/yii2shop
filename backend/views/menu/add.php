<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'s_menu')->dropDownList(\backend\models\Menu::addMenu());
echo $form->field($model,'rule')->dropDownList(\backend\models\Menu::getPemission());
echo $form->field($model,'sort')->textInput();
echo "<button type='submit' class='btn bg-primary'>登录</button>";
\yii\bootstrap\ActiveForm::end();