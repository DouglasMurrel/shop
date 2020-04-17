<?php


namespace app\models\Forms;


use app\models\Basket;
use app\models\DB\Color;
use app\models\DB\Product;
use Yii;
use yii\base\Model;

class BasketForm extends Model
{

    public $count;
    public $id;
    public $color_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['count', 'integer', 'message'=>'Некорректное значение'],
            ['id', 'integer', 'message'=>'Некорректный товар'],
            ['id', 'exist', 'targetClass' => Product::className(), 'message'=>'Некорректный товар'],
            ['color_id', 'integer', 'message'=>'Некорректный цвет'],
            ['color_id', 'exist', 'targetClass' => Color::className(),
                'targetAttribute' => 'id',
                'message'=>'Некорректный цвет',
                'when' => function($model) {
                    return $model->color_id != 0;
                }
            ],
        ];
    }
}