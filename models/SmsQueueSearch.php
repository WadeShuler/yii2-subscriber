<?php

namespace wadeshuler\subscriber\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wadeshuler\subscriber\models\SmsQueue;

/**
 * SmsQueueSearch represents the model behind the search form of `wadeshuler\subscriber\models\SmsQueue`.
 */
class SmsQueueSearch extends SmsQueue
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'campaign_id', 'subscriber_id', 'attempts'], 'integer'],
            [['data', 'last_attempt_time', 'time_to_send', 'sent_time', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = SmsQueue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'subscriber_id' => $this->subscriber_id,
            'attempts' => $this->attempts,
            'last_attempt_time' => $this->last_attempt_time,
            'time_to_send' => $this->time_to_send,
            'sent_time' => $this->sent_time,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
