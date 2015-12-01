<?php

use branchonline\lightbox\Lightbox;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Books'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \app\grid\SpanedGridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'preview',
                'format' => 'raw',
                'value' => function ($model) {
                    return Lightbox::widget([
                        'files' => [
                            [
                                'thumb' => '/' . Yii::$app->params['uploadPreviewPath'] . $model->preview,
                                'original' => '/' . Yii::$app->params['uploadPath'] . $model->preview,
                                'title' => $model->author->fullname . '<br>' . $model->name,
                            ],
                        ]
                    ]);
                },
            ],
            'author.fullname',
            [
                'attribute' => 'date',
                'format' => ['date', 'long']
            ],
            [
                'attribute' => 'date_create',
                'value' => function ($model) {
                    return Yii::$app->formatter->asRelativeTime($model->date_create);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return \yii\helpers\Html::a('[ред]', $url,
                            ['title' => Yii::t('yii', 'Редактировать'), 'data-pjax' => '0']);
                    }
                ],
                'headerOptions' => ['colspan' => 3],
                'header' => 'Кнопки действий',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return \yii\helpers\Html::a('[просм]', false,
                            [
                                'title' => Yii::t('yii', 'Просмотр'),
                                'class' => 'modal-view-link',
                                'data-url' => $url,
                                'data-pjax' => '0'
                            ]);
                    }
                ],
                'headerOptions' => ['header-hide' => true],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return \yii\helpers\Html::a('[удл]', $url,
                            [
                                'title' => Yii::t('yii', 'Удалить'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0'
                            ]);
                    }
                ],
                'headerOptions' => ['header-hide' => true],
            ],
        ],
    ]); ?>

    <?php
    Modal::begin(['id' => 'book-view']);

    echo "<div id='book-view-content'></div>";

    Modal::end();
    ?>

</div>
