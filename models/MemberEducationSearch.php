<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MemberEducation;

/**
 * MemberEducationSearch represents the model behind the search form about `app\models\MemberEducation`.
 */
class MemberEducationSearch extends MemberEducation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['user_id', 'degree', 'institution', 'from_m', 'from_y', 'degree_details', 'to_m', 'to_y'], 'safe'],
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
        $query = MemberEducation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => Yii::$app->user->id,
        ]);

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'degree', $this->degree])
            ->andFilterWhere(['like', 'institution', $this->institution])
            ->andFilterWhere(['like', 'from_y', $this->from_y])
            ->andFilterWhere(['like', 'from_m', $this->from_m])
            ->andFilterWhere(['like', 'degree_details', $this->degree_details])
            ->andFilterWhere(['like', 'to_y', $this->to_y])
            ->andFilterWhere(['like', 'to_m', $this->to_m]);

        return $dataProvider;
    }
}
