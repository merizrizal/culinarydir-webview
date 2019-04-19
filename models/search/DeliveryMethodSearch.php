<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\DeliveryMethod;

/**
 * DeliveryMethodSearch represents the model behind the search form of `core\models\DeliveryMethod`.
 */
class DeliveryMethodSearch extends DeliveryMethod
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'delivery_name', 'created_at', 'user_created', 'updated_at', 'user_updated', 'note', 'description'], 'safe'],
            [['not_active'], 'boolean'],
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
        $query = DeliveryMethod::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
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
            'not_active' => $this->not_active,
            'created_at' => $this->created_at,
            'user_created' => $this->user_created,
            'updated_at' => $this->updated_at,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'delivery_name', $this->delivery_name]);

        return $dataProvider;
    }
}
