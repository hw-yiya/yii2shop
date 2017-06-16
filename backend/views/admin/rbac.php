<h2>管理员授权</h2>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'roles')->checkboxList(\backend\models\UserForm::getRoleOption());
echo \yii\bootstrap\Html::submitButton('授权',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();