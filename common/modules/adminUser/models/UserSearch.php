<?php

namespace common\modules\adminUser\models;

use Yii;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public function search($params)
    {
        $query = self::baseSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'role_id' => $this->role_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhereLowercase(['like', 'email', $this->email])
            ->andFilterWhereLowercase(['like', 'username', $this->username])
            ->andFilterWhereLowercase(['like', 'position', $this->position])
            ->andFilterWhereLowercase(['like', 'desc', $this->desc])
            ->andFilterWhereLowercase(['like', 'dob', $this->dob])
            ->andFilterWhereLowercase(['like', 'fullname', $this->fullname]);

        return $dataProvider;
    }
}
