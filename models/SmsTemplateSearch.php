<?php

namespace ihacklog\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ihacklog\sms\models\SmsTemplate;

/**
 * SmsTemplateSearch represents the model behind the search form about `ihacklog\sms\models\SmsTemplate`.
 */
class SmsTemplateSearch extends SmsTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'template_type'], 'integer'],
            [['template_name', 'template_content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SmsTemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'template_type' => $this->template_type,
        ]);

        $query->andFilterWhere(['like', 'template_name', $this->template_name])
            ->andFilterWhere(['like', 'template_content', $this->template_content]);

        return $dataProvider;
    }
}
