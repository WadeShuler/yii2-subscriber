<?php
namespace wadeshuler\subscriber\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use wadeshuler\subscriber\models\Subscriber;

/**
 * SubscriberSearch represents the model behind the search form about `common\models\Subscriber`.
 */
class SubscriberSearch extends Subscriber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'email_marked_spam', 'email_bounced', 'email_unsubscribed', 'email_unsubscribed_at', 'sms_bounced', 'sms_unsubscribed', 'sms_unsubscribed_at', 'created_at', 'updated_at'], 'integer'],
            [['name', 'email_address', 'phone_number', 'ip_address'], 'safe'],
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
        $query = Subscriber::find();

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
            'email_marked_spam' => $this->email_marked_spam,
            'email_bounced' => $this->email_bounced,
            'email_unsubscribed' => $this->email_unsubscribed,
            'email_unsubscribed_at' => $this->email_unsubscribed_at,
            'sms_bounced' => $this->sms_bounced,
            'sms_unsubscribed' => $this->sms_unsubscribed,
            'sms_unsubscribed_at' => $this->sms_unsubscribed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email_address', $this->email_address])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        return $dataProvider;
    }
}
