<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\BusinessProduct;

/**
 * BusinessProductSearch represents the model behind the search form of `core\models\BusinessProduct`.
 */
class BusinessProductSearch extends BusinessProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'business_id', 'price', 'user_created', 'user_updated', 'order'], 'integer'],
            [['name', 'description', 'image', 'created_at', 'updated_at',
                'businessProductCategory.productCategory.name'], 'safe'],
            [['not_active'], 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['businessProductCategory.productCategory.name']);
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
        $query = BusinessProduct::find()
            ->joinWith(['businessProductCategory.productCategory']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['order' => SORT_ASC]],
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);
        
        $dataProvider->sort->attributes['businessProductCategory.productCategory.name'] = [
            'asc' => ['product_category.name' => SORT_ASC],
            'desc' => ['product_category.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'business_product.id' => $this->id,
            'business_product.business_id' => $this->business_id,
            'business_product.price' => $this->price,
            'business_product.not_active' => $this->not_active,
            'business_product.created_at' => $this->created_at,
            'business_product.user_created' => $this->user_created,
            'business_product.updated_at' => $this->updated_at,
            'business_product.user_updated' => $this->user_updated,
            'business_product.order' => $this->order,
        ]);

        $query->andFilterWhere(['ilike', 'business_product.name', $this->name])
            ->andFilterWhere(['ilike', 'business_product.description', $this->description])
            ->andFilterWhere(['ilike', 'business_product.image', $this->image])
            ->andFilterWhere(['ilike', 'product_category.name', $this->getAttribute('businessProductCategory.productCategory.name')]);

        return $dataProvider;
    }
}
