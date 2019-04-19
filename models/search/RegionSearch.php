<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\Region;

/**
 * RegionSearch represents the model behind the search form of `core\models\Region`.
 */
class RegionSearch extends Region
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'user_created', 'user_updated'], 'integer'],
            [['name', 'created_at', 'updated_at',
                'city.name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['city.name']);
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
        $query = Region::find()
            ->joinWith(['city']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);

        $dataProvider->sort->attributes['city.name'] = [
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
            'region.id' => $this->id,
            'region.city_id' => $this->city_id,
            'region.created_at' => $this->created_at,
            'region.user_created' => $this->user_created,
            'region.updated_at' => $this->updated_at,
            'region.user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'region.name', $this->name])
            ->andFilterWhere(['ilike', 'city.name', $this->getAttribute('city.name')]);

        return $dataProvider;
    }
}
