<?php

namespace app\models\DB;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id id
 * @property string|null $image Картинка
 * @property int $entity_id id сущности
 * @property string $entity_type Тип сущности
 * @property int $sort Порядок сортировки
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'sort'], 'integer'],
            [['entity_type'], 'required'],
            [['image', 'entity_type'], 'string', 'max' => 255],
            [['entity_id', 'entity_type'], 'unique', 'targetAttribute' => ['entity_id', 'entity_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'image' => 'Картинка',
            'entity_id' => 'id сущности',
            'entity_type' => 'Тип сущности',
            'sort' => 'Порядок сортировки',
        ];
    }

    public static function get($entity_id,$entity_type){
        return Image::find()
            ->where(['entity_id'=>$entity_id,'entity_type'=>$entity_type])
            ->orderBy('sort ASC')
            ->asArray()
            ->all();
    }

    public static function getFirst($entity_id,$entity_type){
        $imageArray = Image::find()
            ->where(['entity_id'=>$entity_id,'entity_type'=>$entity_type])
            ->orderBy('sort ASC')
            ->asArray()
            ->one();
        if(isset($imageArray['image']))return $imageArray['image'];
        return null;
    }
}
