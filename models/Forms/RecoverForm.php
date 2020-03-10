<?php

namespace app\models\Forms;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use app\models\DB\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RecoverForm extends Model
{
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::className(),  'message' => 'Пользователь с таким email не существует'],
        ];
    }

    /**
     * @return bool
     */
    public function sendmail()
    {
        if ($this->validate()) {
            $user = User::findByUsername($this->email);
            $code = Yii::$app->security->generatePasswordHash($user->getId().'_'.microtime(true));
            $code = str_replace('/','-',$code);
            $user->passwordHash = md5($code.Yii::$app->params['salt']);
            $user->save();
            $link = Url::base(true)."/reset/$code/".$this->email;
            Yii::$app->mailer->compose('resetPassword',['link'=>$link,'site'=>Url::base(true)])
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setSubject('Сброс пароля')
                ->send();
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function confirmation($flag=true)
    {
        if($flag) {
            return "На адрес " . $this->email . " выслано письмо с подтверждением. Пройдите по приведенной там ссылке.";
        }else{
            return "По техническим причинам отправить письмо с подтверждением сброса пароля не удалось. Приносим извинения за возможные неудобства:(";
        }
    }
}
