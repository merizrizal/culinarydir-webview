<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\MembershipType;

/**
 * MembershipTypeSearch represents the model behind the search form of `core\models\MembershipType`.
 */
class MembershipTypeSearch extends MembershipType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_premium', 'time_limit', 'price', 'is_active', 'order', 'as_archive', 'user_created', 'user_updated'], 'integer'],
            [['name', 'note', 'created_at', 'updated_at'], 'safe'],
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
        $query = MembershipType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['order' => SORT_ASC]
            ],
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
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
            'is_premium' => $this->is_premium,
            'time_limit' => $this->time_limit,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'as_archive' => $this->as_archive,
            'created_at' => $this->created_at,
            'user_created' => $this->user_created,
            'updated_at' => $this->updated_at,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'note', $this->note]);

        return $dataProvider;
    }
}
