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
class RegisterForm extends Model
{
    public $email;
    public $password;
    public $password2;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['email', 'required', 'message'=>'EMail не может быть пустым'],
            ['password', 'required', 'message'=>'Пароль не может быть пустым'],
            ['password2', 'required', 'message'=>'Пароль не может быть пустым'],
            ['email', 'email', 'message'=>'Некорректный email'],
            ['rememberMe', 'boolean'],
            ['email', 'unique', 'targetClass' => User::className(),  'message' => "Пользователь с таким email уже существует.\n Возможно, вы уже входили через сетевой аккаунт, связанный с этим email?"],
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
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->roles = json_encode(['user']);
            if($user->save()) {
                return Yii::$app->user->login($user, 3600 * 24 * 30);
            }
        }
        return false;
    }
}
