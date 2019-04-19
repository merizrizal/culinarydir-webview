<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\BusinessProductCategory;

/**
 * BusinessProductCategorySearch represents the model behind the search form of `core\models\BusinessProductCategory`.
 */
class BusinessProductCategorySearch extends BusinessProductCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'unique_id', 'business_id', 'product_category_id', 'created_at', 'user_created', 'updated_at', 'user_updated',
                'productCategory.name'], 'safe'],
            [['is_active'], 'boolean'],
            [['order'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['productCategory.name']);
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
        $query = BusinessProductCategory::find()
            ->joinWith(['productCategory']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['order' => SORT_ASC]],
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);
        
        $dataProvider->sort->attributes['productCategory.name'] = [
            'asc' => ['product_category.name' => SORT_ASC],
            'desc' => ['product_category.name' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'business_product_category.is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'unique_id', $this->unique_id])
            ->andFilterWhere(['ilike', 'business_id', $this->business_id])
            ->andFilterWhere(['ilike', 'product_category_id', $this->product_category_id])
            ->andFilterWhere(['ilike', 'user_created', $this->user_created])
            ->andFilterWhere(['ilike', 'user_updated', $this->user_updated])
            ->andFilterWhere(['ilike', 'product_category.name', $this->getAttribute('productCategory.name')]);

        return $dataProvider;
    }
}
