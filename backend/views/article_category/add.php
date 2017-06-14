<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'隐藏',1=>'正常']);
echo $form->field($model,'is_help',['inline'=>true])->radioList([1=>'是',2=>'否']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();