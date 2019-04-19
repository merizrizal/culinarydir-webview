<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\City;

/**
 * CitySearch represents the model behind the search form of `core\models\City`.
 */
class CitySearch extends City
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'province_id', 'user_created', 'user_updated'], 'integer'],
            [['name', 'created_at', 'updated_at',
                'province.name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['province.name']);
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
        $query = City::find()
            ->joinWith(['province']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);

        $dataProvider->sort->attributes['province.name'] = [
            'asc' => ['province.name' => SORT_ASC],
            'desc' => ['province.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'city.id' => $this->id,
            'city.province_id' => $this->province_id,
            'city.created_at' => $this->created_at,
            'city.user_created' => $this->user_created,
            'city.updated_at' => $this->updated_at,
            'city.user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'city.name', $this->name])
            ->andFilterWhere(['ilike', 'province.name', $this->getAttribute('province.name')]);

        return $dataProvider;
    }
}
