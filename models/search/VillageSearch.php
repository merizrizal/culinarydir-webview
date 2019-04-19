<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\Village;

/**
 * VillageSearch represents the model behind the search form of `core\models\Village`.
 */
class VillageSearch extends Village
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'district_id', 'user_created', 'user_updated'], 'integer'],
            [['name', 'created_at', 'updated_at',
                'district.name', 'district.region.name', 'district.region.city.name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['district.name', 'district.region.name', 'district.region.city.name']);
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
        $query = Village::find()
            ->joinWith([
                'district',
                'district.region',
                'district.region.city'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);

        $dataProvider->sort->attributes['district.name'] = [
            'asc' => ['district.name' => SORT_ASC],
            'desc' => ['district.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['district.region.name'] = [
            'asc' => ['region.name' => SORT_ASC],
            'desc' => ['region.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['district.region.city.name'] = [
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
            'id' => $this->id,
            'district_id' => $this->district_id,
            'created_at' => $this->created_at,
            'user_created' => $this->user_created,
            'updated_at' => $this->updated_at,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'district.name', $this->getAttribute('district.name')])
            ->andFilterWhere(['ilike', 'region.name', $this->getAttribute('district.region.name')])
            ->andFilterWhere(['ilike', 'city.name', $this->getAttribute('district.region.city.name')]);

        return $dataProvider;
    }
}
