<?php
echo \yii\bootstrap\Html::a('添加',['admin/add','class'=>'btn btn-info']);
?>

<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'code')->widget(yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-4">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();