<?php
class UserIdentity extends CUserIdentity
{
    /**
     * 身份验证
     * 根据用户名从管理员表中取得用户信息
     * 将传入的密码与根据用户名获取出的salt进行加密
     * 将加密结果与用户表中的密码字段匹配。
     * @author Howe Isamu <margina757@gmail.com>
     * @return object errorCode
     */
    private $_id;
    private $_role;
    public function authenticate()
    {
        // $user=Service::factory('AdminUserService')->getAdminUserWhere('username', $this->username);

        $criteria = new CDbCriteria;
        $criteria->condition = "username = :username";
        $criteria->params = array(':username' => $this->username);
        $userInfo = AdminUserModel::model()->find($criteria);
        if(empty($userInfo->username))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif(Salt::vertifySalt($this->password, $userInfo->salt) != $userInfo->password)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
            $this->errorCode=self::ERROR_NONE;
        if(!empty($userInfo->uid)) {
            $this->_id=$userInfo->uid;
            $this->_role=$userInfo->role;
            $this->setState('role', $userInfo->role); //设定权限状态
            $this->setState('uid', $userInfo->uid);
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}
