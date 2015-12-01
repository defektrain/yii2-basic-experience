<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BooksSearch represents the model behind the search form about `app\models\Books`.
 */
class BooksSearch extends Books
{
    public $fullname;
    public $date_start;
    public $date_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id'], 'integer'],
            [['name', 'preview', 'fullname', 'date_start', 'date_end', 'date_create', 'date_update', 'date'], 'safe'],
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
        $query = Books::find();
        $query->joinWith(['author']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        $dataProvider->sort->attributes['author.fullname'] = [
            'asc' => ['firstname' => SORT_ASC, 'lastname' => SORT_ASC],
            'desc' => ['firstname' => SORT_DESC, 'lastname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
//            'id' => $this->id,
//            'date_create' => $this->date_create,
//            'date_update' => $this->date_update,
//            'date' => $this->date,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
//            ->andFilterWhere(['like', 'preview', $this->preview]);

        if ($this->date_start && $this->date_end) {
            $startDate = \DateTime::createFromFormat('d/m/Y', $this->date_start)->format('Y-m-d');
            $endDate = \DateTime::createFromFormat('d/m/Y', $this->date_end)->format('Y-m-d');
            $query->andWhere('date BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
        }

        return $dataProvider;
    }
}
