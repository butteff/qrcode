<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\validators\IpValidator;
use app\models\Url;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return 'logs'; // Название вашей таблицы
    }

    public function rules() //валидация данных
    {
        return [
            [['url_id', 'ip', 'visited_at'], 'required'],
            ['ip', 'string', 'max'=> 45],
            ['ip', 'ip'],
            ['url_id', 'integer'],
            ['visited_at', 'safe'],
        ];
    }

    public function getUrl() // соотношения
    {
        return $this->hasOne(Url::class, ['url_id' => 'id']);
    }
}