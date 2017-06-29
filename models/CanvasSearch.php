<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use app\models\Canvas;

/**
 * CanvasSearch represents the model behind the search form about `app\models\Canvas`.
 */
class CanvasSearch extends Canvas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'content', 'date_added', 'date_modified'], 'safe'],
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
        $query = Canvas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_added' => $this->date_added,
            'date_modified' => $this->date_modified,
            'assigned_to'=>Yii::$app->user->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
    
    public function searchAll($params)
    {
        $query = Canvas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }    
    
    public function searchByFilter($filter)
    {
        $sql = " FROM canvases INNER JOIN member ON canvases.created_by = member.id WHERE 1=1 AND canvases.trash='No'  ";
        
        if(is_numeric($filter->subsector))
        {
            $sql .= " AND canvases.subsector = '" . $filter->subsector . "'";
        }
        else
        {
            if(is_numeric($filter->sector))
            {
                $sql .= " AND canvases.sector = '" . $filter->sector . "'";
            }   
        }
        
        $sql_count = "SELECT count(*) " . $sql; 
        $count = Yii::$app->db->createCommand($sql_count)->queryScalar();
        
        $sql_rows = "SELECT member.given_name AS given_name, member.family_name AS family_name, canvases.id AS id, canvases.eng_summary AS eng_summary, canvases.title AS title " . $sql;

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
