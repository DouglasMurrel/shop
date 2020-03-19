<?php


namespace app\models\Forms;


use app\models\Basket;
use app\models\DB\Product;
use Yii;
use yii\base\Model;

class BasketForm extends Model
{

    public $count;
    public $id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['count', 'integer', 'message'=>'Некорректное значение'],
            ['id', 'integer', 'message'=>'Некорректный товар'],
            ['id', 'exist', 'targetClass' => Product::className(), 'message'=>'Некорректный товар'],
        ];
    }
}