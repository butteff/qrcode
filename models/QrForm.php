<?php

namespace app\models;

use Yii;
use yii\base\Model;

class QrForm extends Model
{
    public $url;

    public function rules() //валидация формы главной страницы
    {
        return [
            ['url', 'required', 'message' => 'URL ссылка не должна быть пустой'],
            ['url', 'url', 'message' => 'URL ссылка не валидна'],
        ];
    }
}