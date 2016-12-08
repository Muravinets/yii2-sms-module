<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ihacklog\sms\models\SmsTemplate */
?>
<div class="sms-template-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'template_name',
            'template_content',
            'template_type',
        ],
    ]) ?>

</div>
