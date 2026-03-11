<?php

namespace app\controllers;

use Yii;
//use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
//use yii\filters\VerbFilter;
use app\models\QrForm;
use app\models\Qr;
use app\models\Log;
use app\models\Url;
use yii\helpers\Html;
use yii\web\HttpException;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new QrForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $status = Url::check($model->url); // Получение статус кода по ссылке

            if (in_array($status, [200, 202, 301])) { // Если статус код об активной ссылке
                $url = Url::find()->where(['url' => $model->url])->one(); // ищем в БД существующий короткий код
                $qr = new Qr($url); // Инициализируем модель для QR c короткой ссылкой или без, если без, то она сгенерируется в конструкторе
                $qr->generateQrCode(); // Генерируем QR код

                if ($qr->qrCode && $qr->shortUrl) { // если существует короткая ссылка и QR код после методов в модели QR
                    if (!$url) { //если в БД не было короткой ссылки, то сохраняем в БД
                        $url = new Url();
                        $url->url = $model->url;
                        $url->short = $qr->shortUrl;
                        if (!$url->save()) {
                            return [
                                'status' => false,
                                'message' => 'Ошибка сохранения короткой ссылки'
                            ];      
                        }
                    }

                    return [
                        'status' => true,
                        'qr' => $qr->qrCode,
                        'shortUrl' => Yii::getAlias('@url').'/'.$qr->shortUrl
                    ];
                    
                } else {
                    return [
                        'status' => false,
                        'message' => 'Ошибка генерации QR'
                    ];    
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Данный URL недоступен'
                ];
            }
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionRedirect($short) {
        if (strlen($short) <= 6) {
            $short = Html::encode($short);
            $url = Url::find()->where(['short' => $short])->one(); // ищем в БД существующий короткий код
            if ($url) {
                //создаем записи в логах, если существует короткая ссылка в БД:
                $log = new Log();
                $log->ip = $_SERVER['REMOTE_ADDR'];
                $log->url_id = $url->id;
                $log->visited_at = date('Y-m-d h:i:s');
                $log->save();

                //увеличиваем счетчик переходов по ссылке:
                $url->count +=1;
                $url->update();

                return $this->redirect($url->url); //редирект на необходимый url
            } else {
                throw new HttpException(404, 'Короткой ссылки не существует');
            }
        } else {
            throw new HttpException(500, 'Короткий код неверный');
        }
    }


}
