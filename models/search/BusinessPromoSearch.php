<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\BusinessPromo;

/**
 * BusinessPromoSearch represents the model behind the search form of `core\models\BusinessPromo`.
 */
class BusinessPromoSearch extends BusinessPromo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'business_id', 'user_created', 'user_updated'], 'integer'],
            [['title', 'short_description', 'description', 'image', 'date_start', 'date_end', 'created-at', 'updated_at'], 'safe'],
            [['not_active'], 'boolean'],
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
        $query = BusinessPromo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'business_id' => $this->business_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'not_active' => $this->not_active,
            'created_at' => $this->created_at,
            'user_created' => $this->user_created,
            'updated_at' => $this->updated_at,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'short_description', $this->short_description])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'image', $this->image]);

        return $dataProvider;
    }
}
