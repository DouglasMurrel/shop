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
            ['count', 'integer'],
            ['id', 'integer'],
            ['id', 'exist', 'targetClass' => Product::className()],
        ];
    }

    /**
     * Метод добавляет товар в корзину
     */
    public function addToBasket()
    {
        if ($this->validate()) {
            Yii::info($this->id);
            Yii::info($this->count);
            Basket::addToBasket($this->id,$this->count);
        }
    }
}