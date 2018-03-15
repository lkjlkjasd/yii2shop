<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'rememberMe')->checkbox();
echo "<button type='submit' class='btn bg-primary'>登录</button>";
\yii\bootstrap\ActiveForm::end();