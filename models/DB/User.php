<?php

namespace app\models\DB;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "product".
 *
 * @property int $id id
 * @property string $email
 * @property string $password
 * @property string $passwordHash
 * @property string $roles
 * @property string $basket
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id'])->orderBy('created desc')->asArray()->inverseOf('user');
    }

    public function fullOrdersInfo(){
        $orders = $this->orders;
        foreach($orders as $k=>$v){
            $v['items'] = OrderItem::find()->where(['order_id'=>$v['id']])->asArray()->all();
            unset($v['user']);
            $orders[$k] = $v;
        }
        return $orders;
    }

    public static function userList(){
        $query = User::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Yii::$app->params['pageSize'], // кол-во товаров на странице
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);
        $arrResult = $query
            ->orderBy('roles,email')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return ['users'=>$arrResult,'pages'=>$pages];
    }

    public function setAdmin($val){
        if($val==0)$rolesArr = ['user'];
        if($val==1)$rolesArr = ['user','admin'];
        $roles = json_encode($rolesArr);
        $this->roles = $roles;
        $this->save();
    }

    public function isAdmin(){
        $roles = json_decode($this->roles);
        return in_array('admin', $roles);
    }

    public static function isAdminById($id){
        $user = User::findOne($id);
        if($user) {
            $roles = json_decode($user->roles);
            return in_array('admin', $roles);
        }
        return 0;
    }
}
