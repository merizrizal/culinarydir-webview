<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\Business;

/**
 * BusinessSearch represents the model behind the search form of `core\models\Business`.
 */
class BusinessSearch extends Business
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'user_in_charge', 'user_created', 'user_updated'], 'integer'],
            [['name', 'unique_name', 'about', 'email', 'phone1', 'phone2', 'phone3', 'is_active', 'created_at', 'updated_at',
                'membershipType.name', 'district.name','village.name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['membershipType.name','district.name','village.name']);
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
        $query = Business::find()
            ->joinWith([
                'membershipType',
                'userInCharge',
                'businessLocation.district',
                'businessLocation.village'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_ASC]
            ],
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);

        $dataProvider->sort->attributes['membershipType.name'] = [
            'asc' => ['membership_type.name' => SORT_ASC],
            'desc' => ['membership_type.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'business.id' => $this->id,
            'business.parent_id' => $this->parent_id,
            'business.user_in_charge' => $this->user_in_charge,
            '(business.created_at + interval \'7 hour\')::date' => $this->created_at,
            'business.user_created' => $this->user_created,
            '(business.updated_at + interval \'7 hour\')::date' => $this->updated_at,
            'business.user_updated' => $this->user_updated,
            'district.name' => $this->getAttribute('district.name'),
            'village.name' => $this->getAttribute('village.name'),
        ]);

        $query->andFilterWhere(['ilike', 'business.name', $this->name])
            ->andFilterWhere(['ilike', 'unique_name', $this->unique_name])
            ->andFilterWhere(['ilike', 'about', $this->about])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'phone1', $this->phone1])
            ->andFilterWhere(['ilike', 'phone2', $this->phone2])
            ->andFilterWhere(['ilike', 'phone3', $this->phone3])
            ->andFilterWhere(['ilike', 'is_active', $this->is_active])
            ->andFilterWhere(['membership_type.name' => $this->getAttribute('membershipType.name')]);

        return $dataProvider;
    }
}
