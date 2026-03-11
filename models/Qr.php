<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Da\QrCode\QrCode;
use app\models\Url;

class Qr extends Model
{
    public $shortUrl = false;
    public $qrCode = false;

    public function __construct($url = false) 
    {
        $this->shortUrl = $url ? $url->short : Url::generateShortLink();

    }

    public function generateQrCode() // Генерация изображения Qr кода
    {
        $qrCode = (new QrCode(Yii::getAlias('@url').'/'.$this->shortUrl))
            ->setSize(250)
            ->setMargin(5)
            ->setBackgroundColor(248, 249, 250);
        $this->qrCode = $qrCode->writeDataUri();
    }
}