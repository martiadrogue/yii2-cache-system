<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'stock'], 'required'],
            ['name', 'string',  'min' => 4,  'max' => 512],
            ['stock', 'integer', 'integerOnly' => true, 'min' => 0],
        ];
    }
}
