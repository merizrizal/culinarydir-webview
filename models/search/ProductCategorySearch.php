<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\ProductCategory;

/**
 * ProductCategorySearch represents the model behind the search form of `core\models\ProductCategory`.
 */
class ProductCategorySearch extends ProductCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_created', 'user_updated'], 'integer'],
            [['name', 'type', 'is_active', 'created_at', 'updated_at'], 'safe'],
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
        $query = ProductCategory::find();

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
            'product_category.id' => $this->id,
            'product_category.type' => $this->type,
            'product_category.is_active' => $this->is_active,
            'product_category.created_at' => $this->created_at,
            'product_category.user_created' => $this->user_created,
            'product_category.updated_at' => $this->updated_at,
            'product_category.user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'product_category.name', $this->name]);

        return $dataProvider;
    }
}
