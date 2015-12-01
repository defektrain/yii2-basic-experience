<?php

namespace app\models;

use webvimark\image\Image;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "books".
 *
 * @property integer $id
 * @property string $name
 * @property integer $date_create
 * @property integer $date_update
 * @property string $preview
 * @property integer $date
 * @property integer $author_id
 *
 * @property Authors $author
 */
class Books extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'books';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update'],
                ],
            ],
        ];
    }

    public function beforeDelete()
    {
        if (unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $this->preview) &&
            unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $this->preview)
        ) {
            return parent::beforeDelete();
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date', 'author_id'], 'required'],
            [['date_create', 'date_update', 'author_id'], 'integer'],
            [['preview'], 'string'],
//            [['imageFile'], 'required', 'on' => 'create'],
            [
                ['imageFile'],
                'required',
                'when' => function ($model) {
                    return !$model->preview;
                },
                'whenClient' => "function (attribute, value) {
                    return !$('#books-preview').val();
                }"
            ],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
            [['name'], 'string', 'max' => 255],
            [['date', 'date_create', 'date_update', 'fullname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'date_create' => Yii::t('app', 'Дата добавления'),
            'date_update' => Yii::t('app', 'Дата изменения'),
            'preview' => Yii::t('app', 'Превью'),
            'imageFile' => Yii::t('app', 'Превью'),
            'date' => Yii::t('app', 'Дата выхода книги'),
            'author_id' => Yii::t('app', 'Автор'),
            'fullname' => Yii::t('app', 'Автор'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }

    public function getFullname()
    {
        return $this->author->firstname . ' ' . $this->author->lastname;
    }

    public function uploadImage()
    {
        if ($this->validate()) {
            if (!$this->imageFile) {
                return true;
            }
            if ($this->preview) {
                unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $this->preview);
                unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $this->preview);
            }

            $imageName = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            $imageDatePath = date('Ymd') . '/';

            $imageDir = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $imageDatePath;
            FileHelper::createDirectory($imageDir);
            $resultSaveImage = $this->imageFile->saveAs($imageDir . $imageName);

            if ($resultSaveImage) {
                $imagePreview = Image::factory($imageDir . $imageName);
                $imagePreview->resize(200, 200);

                $imagePreviewDir = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $imageDatePath;
                FileHelper::createDirectory($imagePreviewDir);
                $resultSaveImagePreview = $imagePreview->save($imagePreviewDir . $imageName);

                if ($resultSaveImagePreview) {
                    $this->preview = $imageDatePath . $imageName;

                    return true;
                }
            }
        }

        return false;
    }
}
