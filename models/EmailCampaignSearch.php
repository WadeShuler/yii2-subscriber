<?php

namespace wadeshuler\subscriber\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wadeshuler\subscriber\models\EmailCampaign;

/**
 * EmailCampaignSearch represents the model behind the search form of `wadeshuler\subscriber\models\EmailCampaign`.
 */
class EmailCampaignSearch extends EmailCampaign
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['subject', 'pretext', 'message', 'batch_id'], 'safe'],
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
        $query = EmailCampaign::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'pretext', $this->pretext])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'batch_id', $this->batch_id]);

        return $dataProvider;
    }
}
