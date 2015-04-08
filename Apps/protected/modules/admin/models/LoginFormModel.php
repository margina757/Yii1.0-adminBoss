<?php
class LoginFormModel extends CFormModel
{
    public $username;
    public $password;

    private $_identity;

    public function rules()
    {
        return array(
            array('username, password', 'required', 'message'=>'用户名或密码不能为空'),
            array('password', 'authenticate')
        );
    }

    public function authenticate($attributes, $params)
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate()) {
            $this->addError('password','错误的用户名或密码。');
        } else {
            $duration=3600*24*30; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
    }
}

