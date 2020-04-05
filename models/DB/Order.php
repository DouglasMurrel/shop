<?php

namespace app\models\DB;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "order".
 *
 * @property int $id Идентификатор заказа
 * @property int $user_id Идентификатор пользователя
 * @property string $name Имя и фамилия покупателя
 * @property string $email Почта покупателя
 * @property string $phone Телефон покупателя
 * @property string $address Адрес доставки
 * @property string $comment Комментарий к заказу
 * @property float $amount Сумма заказа
 * @property int $status Статус заказа
 * @property string $created Дата и время создания
 * @property string $updated Дата и время обновления
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount','status'], 'number'],
            [['created', 'updated'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 50, 'message' => 'Значение не может быть длиннее 50 символов'],
            [['address', 'comment'], 'string', 'max' => 255, 'message' => 'Значение не может быть длиннее 50 символов'],
            ['phone','required','message'=>'Укажите номер телефона'],
            ['email','email','message'=>'Некорректный email'],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор заказа',
            'user_id' => 'Идентификатор пользователя',
            'name' => 'Имя и фамилия покупателя',
            'email' => 'Электронная почта покупателя',
            'phone' => 'Телефон покупателя',
            'address' => 'Адрес доставки',
            'comment' => 'Комментарий к заказу',
            'amount' => 'Сумма заказа',
            'status' => 'Статус заказа',
            'created' => 'Дата и время создания',
            'updated' => 'Дата и время обновления',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id'])->inverseOf('order');
    }

    /**
     * Добавляет записи в таблицу БД `order_item`
     */
    public function addItems($basket) {
        // получаем товары в корзине
        $products = $basket['products'];
        // добавляем товары по одному
        foreach ($products as $product_id => $product) {
            $item = new OrderItem();
            $item->order_id = $this->id;
            $item->product_id = $product_id;
            $item->name = $product['name'];
            $discount = 0;
            if(isset($product['discount']))$discount = $product['discount'];
            $item->discount = $discount;
            $item->price = $product['price'];
            $item->quantity = $product['count'];
            $item->cost = $product['price'] * $product['count'] * (100 - $discount)/100;
            $item->save();
        }
    }

    public static function orderList(){
        $query = Order::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Yii::$app->params['pageSize'], // кол-во товаров на странице
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);
        $arrResult = $query
            ->orderBy('created desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return ['orders'=>$arrResult,'pages'=>$pages];
    }
}
