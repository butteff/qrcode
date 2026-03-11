<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class Url extends ActiveRecord
{
    public static function tableName() // Название вашей таблицы
    {
        return 'urls';
    }

    public function rules() //валидация данных
    {
        return [
            [['url', 'short'], 'required'],
            ['url', 'url'],
            ['short', 'string', 'max'=> 6],
            ['count', 'integer'],
        ];
    }

    public function getLogs() // соотношения
    {
        return $this->hasMany(Log::class, ['id' => 'url_id']);
    }

    public static function generateShortLink() // Рекурсивная генерация уникальной короткой ссылки с проверкой наличия в БД
    {
        $shortUrl = Yii::$app->getSecurity()->generateRandomString(6);
        $shortUrl = preg_replace("/[^A-Za-z0-9]/", '', $shortUrl);
        $exists = Url::find()->where(['short' => $shortUrl])->one();
        if ($exists)
            return $this->generateShortLink();
        else 
            return $shortUrl;
    }

    public static function check($url) { // берем статус код через curl head request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $http_code;
    }

    public function beforeSave($data) { // дополнительная фильтрация перед сохранением
        if (parent::beforeSave($data)) {
            $this->url = filter_var($this->url, FILTER_SANITIZE_URL);
            $this->url = Html::encode($this->url);
            return true;
        } 
        
        return false;
    }
}