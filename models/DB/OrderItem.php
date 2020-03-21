<?php

namespace app\models\DB;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id Идентификатор элемента
 * @property int $order_id Идентификатор заказа
 * @property int|null $product_id Идентификатор товара
 * @property string $name Наименование товара
 * @property float $price Цена товара
 * @property int $quantity Количество в заказе
 * @property float $cost Стоимость = Цена * Кол-во
 *
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price', 'cost'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор элемента',
            'order_id' => 'Идентификатор заказа',
            'product_id' => 'Идентификатор товара',
            'name' => 'Наименование товара',
            'price' => 'Цена товара',
            'quantity' => 'Количество в заказе',
            'cost' => 'Стоимость = Цена * Кол-во',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
