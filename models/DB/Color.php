<?php

namespace app\models\DB;

use Yii;

/**
 * This is the model class for table "Color".
 *
 * @property int $id
 * @property string $name
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id','integer'],
//            [['name'], 'required','message'=>'Укажите название цвета'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
        ];
    }

    public static function getAll(){
        return Color::find()->all();
    }

    public function saveColor(){
        if(trim($this->name)!=='') {
            $checkObject = Color::findOne($this->id);
            if ($checkObject){
                $checkObject->name = $this->name;
                $checkObject->update();
            }else{
                $this->insert();
            }
        }
    }

    /**
     * @param $id
     * return string|null
     */
    public static function colorById($id){
        $colorObj = Color::findOne($id);
        if($colorObj)return $colorObj->name;
        else return null;
    }
}
