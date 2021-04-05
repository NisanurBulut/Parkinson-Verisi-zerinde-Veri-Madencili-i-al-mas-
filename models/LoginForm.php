<?php

use app\core\Application;
use app\core\Model;
use app\models\User;

class LoginForm extends Model
{
    public string $email;
    public string $password;

    public function rules():array{
        return [
            'email'=>[self::RULE_UNIQUE, self::RULE_EMAIL],
            'password'=>[self::RULE_REQUIRED]
        ];
    }
    public function login(){
        // need to find user
        $userEntity = new User();
        $user=$userEntity->where(['email'=>$this->email]);
        if(!$user)
        {
          $this->addError('email','Kullanıcı bulunamadı');
        }

        Application::$app->login();
    }
}