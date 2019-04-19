<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\PromoItem;

/**
 * PromoItemSearch represents the model behind the search form of `core\models\PromoItem`.
 */
class PromoItemSearch extends PromoItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'promo_id', 'business_claimed', 'created_at', 'user_created', 'updated_at', 'user_updated',
                'businessClaimed.name', 'userPromoItem.user.username'], 'safe'],
            [['amount'], 'integer'],
            [['not_active'], 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['businessClaimed.name', 'userPromoItem.user.username']);
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
        $query = PromoItem::find()
            ->joinWith([
                'businessClaimed',
                'userPromoItem.user'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array(
                'pageSize' => Yii::$app->params['pageSize'],
            ),
        ]);
        
        $dataProvider->sort->attributes['businessClaimed.name'] = [
            'asc' => ['business.name' => SORT_ASC],
            'desc' => ['business.name' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['userPromoItem.user.username'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'amount' => $this->amount,
            'promo_item.not_active' => $this->not_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'promo_item.id', $this->id])
            ->andFilterWhere(['ilike', 'promo_id', $this->promo_id])
            ->andFilterWhere(['ilike', 'business_claimed', $this->business_claimed])
            ->andFilterWhere(['ilike', 'user_created', $this->user_created])
            ->andFilterWhere(['ilike', 'user_updated', $this->user_updated])
            ->andFilterWhere(['ilike', 'business.name', $this->getAttribute('businessClaimed.name')])
            ->andFilterWhere(['ilike', 'user.username', $this->getAttribute('userPromoItem.user.username')]);

        return $dataProvider;
    }
}
