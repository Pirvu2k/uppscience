<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Paper;

/**
 * PaperSearch represents the model behind the search form about `app\models\Paper`.
 */
class PaperSearch extends Paper
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'discipline', 'language', 'created_by', 'last_modified_by', 'trash'], 'integer'],
            [['title', 'title_in_english', 'abstract', 'abstract_in_english', 'keywords', 'introduction', 'content', 'conclusion', 'references', 'created_on', 'last_modified_on', 'submission_date', 'status'], 'safe'],
            [['rating'], 'number'],
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
        $query = Paper::find();

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
            'discipline' => $this->discipline,
            'language' => $this->language,
            'created_by' => $this->created_by,
            'last_modified_by' => $this->last_modified_by,
            'created_on' => $this->created_on,
            'last_modified_on' => $this->last_modified_on,
            'submission_date' => $this->submission_date,
            'trash' => $this->trash,
            'rating' => $this->rating,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_in_english', $this->title_in_english])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'abstract_in_english', $this->abstract_in_english])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'introduction', $this->introduction])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'conclusion', $this->conclusion])
            ->andFilterWhere(['like', 'references', $this->references])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
    
    public function searchByFilter($filter)
    {
        $sql = " FROM paper INNER JOIN member ON paper.created_by = member.id WHERE 1=1 ";

        if(is_numeric($filter->discipline))
        {
            $sql .= " AND paper.discipline = '" . $filter->discipline . "'";
        }   

        
        $sql_count = "SELECT count(*) " . $sql; 
        $count = Yii::$app->db->createCommand($sql_count)->queryScalar();
        
        $sql_rows = "SELECT member.given_name AS given_name, member.family_name AS family_name, paper.id AS id, LEFT(paper.content , 50) as content, paper.title AS title, paper.created_by AS author " . $sql;

        // add conditions that should always apply here

        $dataProvider = new SqlDataProvider([
            'sql' => $sql_rows,
            'totalCount' => $count,
            'pagination' => [
            'defaultPageSize' => 50,
            ],
            'sort' => [
                'attributes' => [
                    'title' => [
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    } 
}
