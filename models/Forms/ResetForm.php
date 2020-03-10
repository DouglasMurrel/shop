<?php

namespace app\models\Forms;

use Yii;
use yii\base\Model;
use app\models\DB\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ResetForm extends Model
{
    public $email;
    public $password;
    public $password2;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['password', 'password2'], 'required'],
            ['password2', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->password!=$this->password2) {
                $this->addError($attribute, 'Введенные пароли не совпадают.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function reset()
    {
        if ($this->validate()) {
            $user = User::findByUsername($this->email);
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->passwordHash = '';
            if($user->save()) {
                return "Пароль успешно изменен!<br>Теперь вы можете войти на сайт <a href='/' data-pjax='0'>здесь</a>";
            }
        }
        return false;
    }
}
