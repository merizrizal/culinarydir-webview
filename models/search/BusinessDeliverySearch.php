<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\BusinessDelivery;

/**
 * BusinessDeliverySearch represents the model behind the search form of `core\models\BusinessDelivery`.
 */
class BusinessDeliverySearch extends BusinessDelivery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'business_id', 'delivery_method_id', 'user_created', 'user_updated'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at', 'note',
                'deliveryMethod.delivery_name'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['deliveryMethod.delivery_name']);
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
        $query = BusinessDelivery::find()
            ->joinWith(['deliveryMethod']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);
        
        $dataProvider->sort->attributes['deliveryMethod.delivery_name'] = [
            'asc' => ['delivery_method.delivery_name' => SORT_ASC],
            'desc' => ['delivery_method.delivery_name' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'business_delivery.id' => $this->id,
            'business_delivery.business_id' => $this->business_id,
            'business_delivery.delivery_method_id' => $this->delivery_method_id,
            'business_delivery.is_active' => $this->is_active,
            'business_delivery.created_at' => $this->created_at,
            'business_delivery.user_created' => $this->user_created,
            'business_delivery.updated_at' => $this->updated_at,
            'business_delivery.user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'business_delivery.note', $this->note])
            ->andFilterWhere(['ilike', 'delivery_method.delivery_name', $this->getAttribute('deliveryMethod.delivery_name')]);

        return $dataProvider;
    }
}
