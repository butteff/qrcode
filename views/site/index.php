<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Qr';
?>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'qr-form']); ?>
                <?= $form
                    ->field($model, 'url')
                    ->textInput(['autofocus' => true])
                    ->label('Введите url для генерации Qr кода и короткой ссылки:');
                ?>
                <?= Html::submitButton('Ok', ['class' => 'btn btn-primary okbtn', 'name' => 'contact-button']) ?>
                <p id="errortext"></p>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="loader"></div>
    <?= $this->render('_modal');?>
</div>