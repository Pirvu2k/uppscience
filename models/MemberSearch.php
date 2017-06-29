<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;

/**
 * MemberExperienceSearch represents the model behind the search form about `app\models\MemberExperience`.
 */
class MemberSearch extends \yii\db\ActiveRecord
{
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
    public function searchAll($params)
    {
        $query = Member::find();

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
        if(is_numeric($filter->subsector))
        {
            $sql = "FROM member INNER JOIN member_sub_sector ms ON ms.member = member.id WHERE given_name IS NOT NULL AND family_name IS NOT NULL AND member.trash='No' ";
        }
        else
        {
            $sql = "FROM member INNER JOIN member_sector ms ON ms.member = member.id WHERE given_name IS NOT NULL AND family_name IS NOT NULL AND member.trash='No' ";
        }
        
        if(is_numeric($filter->subsector))
        {
            $sql .= " AND ms.subsector = '" . $filter->subsector . "'";
        }
        else
        {
            if(is_numeric($filter->sector))
            {
                $sql .= " AND ms.sector_id = '" . $filter->sector . "'";
            }   
        }
        
        $sql .= " GROUP BY member.id, member.given_name, member.family_name, member.role, member.country, is_pro, is_student";
        
        $sql_count = "SELECT member.id " . $sql; 
        $count_result = Yii::$app->db->createCommand($sql_count)->queryAll();
        
        $count = count($count_result);
        
        $sql_rows = "SELECT max(ms.id), member.id, member.given_name, member.family_name, member.role, member.country, member.is_pro, member.is_student " . $sql;

        // add conditions that should always apply here

        $dataProvider = new SqlDataProvider([
            'sql' => $sql_rows,
            'totalCount' => $count,
            'pagination' => [
            'defaultPageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'given_name' => SORT_ASC,
                    'family_name' => SORT_ASC,
                ],
                'attributes' => [
                    'given_name',
                    'family_name',
                    'country',
                    'role',
                    'is_pro',
                    'is_student'
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
    
    public function searchByFilterOnlyConfirmed($filter)
    {
        if(is_numeric($filter->subsector))
        {
            $sql = "FROM member INNER JOIN member_sub_sector ms ON ms.member = member.id WHERE given_name IS NOT NULL AND family_name IS NOT NULL AND confirmed = 'Yes' AND member.trash='No' ";
        }
        else
        {
            $sql = "FROM member INNER JOIN member_sector ms ON ms.member = member.id WHERE given_name IS NOT NULL AND family_name IS NOT NULL AND confirmed = 'Yes' AND member.trash='No' ";
        }
        
        if(is_numeric($filter->subsector))
        {
            $sql .= " AND ms.subsector = '" . $filter->subsector . "'";
        }
        else
        {
            if(is_numeric($filter->sector))
            {
                $sql .= " AND ms.sector_id = '" . $filter->sector . "'";
            }   
        }
        
        $sql .= " GROUP BY member.id, member.given_name, member.family_name, member.role, member.country, member.is_pro, member.is_student, email";
        
        $sql_count = "SELECT member.id " . $sql; 
        $count_result = Yii::$app->db->createCommand($sql_count)->queryAll();
        
        $count = count($count_result);
        
        $sql_rows = "SELECT max(ms.id), member.id, member.given_name, member.family_name, member.role, member.country, member.is_pro, member.is_student, member.email " . $sql;

        // add conditions that should always apply here

        $dataProvider = new SqlDataProvider([
            'sql' => $sql_rows,
            'totalCount' => $count,
            'pagination' => [
            'defaultPageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'given_name' => SORT_ASC,
                    'family_name' => SORT_ASC,
                ],
                'attributes' => [
                    'given_name',
                    'family_name',
                    'country',
                    'role',
                    'is_pro',
                    'is_student',
                    'email'
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
    
    public static function tableName()
    {
        return 'member';
    }
}
