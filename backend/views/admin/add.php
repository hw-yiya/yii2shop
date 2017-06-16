<h2>管理员添加</h2>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'code')->widget(yii\captcha\Captcha::className(),[
    'template'=>'<div class="row"><div class="col-lg-2">{image}</div><div class="col-lg-4">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();