<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\District;

/**
 * DistrictSearch represents the model behind the search form of `core\models\District`.
 */
class DistrictSearch extends District
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'user_created', 'user_updated'], 'integer'],
            [['name', 'created_at', 'updated_at',
                'region.name', 'region.city.name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['region.name', 'region.city.name']);
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
        $query = District::find()
            ->joinWith([
                'region',
                'region.city'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);

        $dataProvider->sort->attributes['region.name'] = [
            'asc' => ['region.name' => SORT_ASC],
            'desc' => ['region.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['region.city.name'] = [
            'asc' => ['city.name' => SORT_ASC],
            'desc' => ['city.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'district.id' => $this->id,
            'district.region_id' => $this->region_id,
            'district.created_at' => $this->created_at,
            'district.user_created' => $this->user_created,
            'district.updated_at' => $this->updated_at,
            'district.user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'district.name', $this->name])
            ->andFilterWhere(['ilike', 'region.name', $this->getAttribute('region.name')])
            ->andFilterWhere(['ilike', 'city.name', $this->getAttribute('region.city.name')]);

        return $dataProvider;
    }
}
