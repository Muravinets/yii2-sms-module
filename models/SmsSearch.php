<?php

namespace ihacklog\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ihacklog\sms\models\Sms;

/**
 * SmsSearch represents the model behind the search form about `ihacklog\sms\models\Sms`.
 */
class SmsSearch extends Sms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'channel_type', 'template_id', 'verify_result', 'send_status', 'client_ip', 'created_at', 'updated_at'], 'integer'],
            [['mobile', 'content', 'device_id', 'verify_code', 'error_msg', 'provider'], 'safe'],
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
        $query = Sms::find();

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
            'channel_type' => $this->channel_type,
            'template_id' => $this->template_id,
            'verify_result' => $this->verify_result,
            'send_status' => $this->send_status,
            'client_ip' => $this->client_ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'device_id', $this->device_id])
            ->andFilterWhere(['like', 'verify_code', $this->verify_code])
            ->andFilterWhere(['like', 'error_msg', $this->error_msg])
            ->andFilterWhere(['like', 'provider', $this->provider]);

        return $dataProvider;
    }
}
