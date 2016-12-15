<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel ihacklog\sms\models\SmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sms';
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'blogs-grid';

$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
        'id',
        'mobile',
        'verify_code',
        [
            'attribute' => 'content',
            'format' => 'html',
            'value' => function ($model) {
                return Html::a(
                    $model['content'],
                    ['view', 'id' => $model['id']]
                );
            }
        ],
        [
            'attribute' => 'send_status',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->send_status === $model::SEND_STATUS_SUCC) ? 'label-success' : 'label-danger';

                return '<span class="label ' . $class . '">' . $model->send_status . '</span>';
            },

            'filter' => Html::activeDropDownList(
                $searchModel,
                'send_status',
                ['0' => '未发送', '1' => '发送成功', '2' => '发送失败'],
                [
                    'class' => 'form-control',
                    'prompt' => '== 发送状态 =='
                ]
            )
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d H:i:s'],
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'yy.mm.dd',
                    ]
                ]
            )
        ],
        [
            'attribute' => 'updated_at',
            'format' => ['date', 'php:Y-m-d H:i:s'],
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'dateFormat' => 'yy.mm.dd',
                    ]
                ]
            )
        ]
    ]
];

$boxButtons = $actions = ['{create}'];
$showActions = false;

/*
if (Yii::$app->user->can('BCreateBlogs')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateBlogs')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
if (Yii::$app->user->can('BDeleteBlogs')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}
*/

//$boxButtons[] = '{create}';
$actions[] = '{update}';
$actions[] = '{delete}';

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions)
    ];
}

$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="row">
    <div class="col-xs-12">
        <?=  GridView::widget($gridConfig); ?>
    </div>
</div>
