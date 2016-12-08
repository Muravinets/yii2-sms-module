<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ihacklog\sms\models\SmsTemplate */
?>
<div class="sms-template-update">

    <?= $this->render('_form', [
        'model' => $model,
        'templateTypeArr' => $templateTypeArr,
    ]) ?>

</div>
