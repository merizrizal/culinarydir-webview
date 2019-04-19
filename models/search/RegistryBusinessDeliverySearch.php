<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\RegistryBusinessDelivery;

/**
 * RegistryBusinessDeliverySearch represents the model behind the search form of `core\models\RegistryBusinessDelivery`.
 */
class RegistryBusinessDeliverySearch extends RegistryBusinessDelivery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'registry_business_id', 'delivery_method_id', 'user_created', 'user_updated'], 'integer'],
            [['created_at', 'updated_at', 'note', 
                'deliveryMethod.delivery_name'], 'safe'],
            [['is_active'], 'boolean'],
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
        $query = RegistryBusinessDelivery::find()
            ->joinWith(['deliveryMethod']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);
        
        $dataProvider->sort->attributes['deliveryMethod.delivery_name'] = [
            'asc' => ['delivery_method.delivery_name' => SORT_ASC],
            'desc' => ['delivery_method.delivery_name' => SORT_DESC],
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
            'registry_business_id' => $this->registry_business_id,
            'delivery_method_id' => $this->delivery_method_id,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'user_created' => $this->user_created,
            'updated_at' => $this->updated_at,
            'user_updated' => $this->user_updated,
        ]);

        $query->andFilterWhere(['ilike', 'note', $this->note])
            ->andFilterWhere(['ilike', 'delivery_method.delivery_name', $this->getAttribute('deliveryMethod.delivery_name')]);

        return $dataProvider;
    }
}
