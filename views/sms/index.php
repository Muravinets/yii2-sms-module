<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use vova07\themes\admin\widgets\Box;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
//use vova07\themes\admin\widgets\GridView;
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
        /*
        'views',

        [
            'attribute' => 'send_status',
            'format' => 'html',
            'value' => function ($model) {
                $class = ($model->status_id === $model::STATUS_PUBLISHED) ? 'label-success' : 'label-danger';

                return '<span class="label ' . $class . '">' . $model->status . '</span>';
            },

            'filter' => Html::activeDropDownList(
                $searchModel,
                'status_id',
                $statusArray,
                [
                    'class' => 'form-control',
                    'prompt' => Module::t('blogs', 'BACKEND_PROMPT_STATUS')
                ]
            )
        ],*/
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
        <?php  Box::begin(
            [
                'title' => $this->title,
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => $boxButtons,
                'grid' => $gridId
            ]
        ); ?>
        <?=  GridView::widget($gridConfig); ?>
        <?php  Box::end(); ?>
    </div>
</div>
