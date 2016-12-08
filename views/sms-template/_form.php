<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ihacklog\sms\models\SmsTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'template_label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'template_name')->textInput(['maxlength' => true]) ?>

    <p>
        模板同标签说明：用户名 {username}, 验证码： {verify_code}, 手机号： {mobile}, 邮箱：{email}
    </p>
    <?= $form->field($model, 'template_content')->textarea(['rows' => 10, 'cols' => 8]) ?>

    <?=
    $form->field($model, 'template_type')->dropDownList(
        $templateTypeArr,
        [
            'prompt' => '请选择模板类型...',
        ]
    ) ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
