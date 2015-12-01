<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BooksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model,
        'author_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Authors::find()->all(), 'id', 'fullname'),
        ['prompt' => 'Автор'])->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название книги'])->label(false) ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::className(), [
        'model' => $model,
        'language' => 'ru',
        'separator' => 'до',
        'attribute' => 'date_start',
        'attribute2' => 'date_end',
        'options' => ['placeholder' => '31/12/2014'],
        'options2' => ['placeholder' => '31/02/2015'],
        'type' => DatePicker::TYPE_RANGE,
        'form' => $form,
        'pluginOptions' => [
            'format' => 'dd/mm/yyyy',
            'autoclose' => true,
        ]
    ])->label('Дата выхода книги') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), [''], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
