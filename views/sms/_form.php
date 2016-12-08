<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ihacklog\sms\models\Sms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php  $box->beginBody(); ?>
            <div class="row">
        <div class="col-sm-3">
                <?=
                $form->field($model, 'channel_type')->dropDownList(
                    $channelTypeArr,
                    [
                        'prompt' => '请选择通道类型...',
                    ]
                ) ?>
        </div>
        </div>
            <div class="row">
        <div class="col-sm-3">
                <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

        </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <label class="control-label" for="sms-template-id">应用短信模板</label>
                <?= Html::activeDropDownList($model, 'template_id', ArrayHelper::map($templateArr, 'id', 'template_name'), ['class' => 'form-control']) ?>
            </div>
        </div>

            <div class="row">
        <div class="col-sm-12">
                <span>如果选择的是验证码通道，短信内容不要填写.</span>
                <?= $form->field($model, 'content')->textArea(['rows' => 4]) ?>

        </div>
        </div>

        <?php  $box->endBody(); ?>
    <?php  $box->beginFooter(); ?>
    <?=  Html::submitButton(
        $model->isNewRecord ? 'Create' : 'Update',
        [
            'class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
        ]
    ) ?>
    <?php  $box->endFooter(); ?>

    <?php ActiveForm::end(); ?>

</div>
