<?php

namespace ihacklog\sms\controllers;

use Yii;
use ihacklog\sms\models\Sms;
use ihacklog\sms\models\SmsSearch;
use ihacklog\sms\models\SmsTemplate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use company\models\LoginForm;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends Controller
{
    public function init() {
        parent::init();
        $model = new LoginForm();
        $model->setScenario('login');
        $model->username = 'webmaster';
        $model->password = 'webmaster';
        $model->login();
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sms model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sms();
        $templateArr = SmsTemplate::find()->all();
        $channelTypeArr = Sms::getChannelTypeArr();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return var_export($model->getErrors(), true);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'templateArr' => $templateArr,
                'channelTypeArr' => $channelTypeArr,
            ]);
        }
    }

    /**
     * Updates an existing Sms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $templateArr = SmsTemplate::find()->all();
        $channelTypeArr = Sms::getChannelTypeArr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'templateArr' => $templateArr,
                'channelTypeArr' => $channelTypeArr,
            ]);
        }
    }

    /**
     * Deletes an existing Sms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        throw new NotFoundHttpException('不允许删除!');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSendSms()
    {

    }
}
