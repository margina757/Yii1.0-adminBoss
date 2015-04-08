<?php
/**
 * AdminUser表AR模型
 */
class AdminUserModel extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        //数据库名.表名
        return 'admin_users';
    }
    public $roleName;

    public function primaryKey()
    {
        return 'uid';
    }
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username,password,salt,role','required','message'=>'不能为空','on'=>'insert'),
            array('username,salt,role','required','message'=>'不能为空','on'=>'update'),
            array('username', 'length', 'max'=>255,'message'=>'不能超过255字符'),
            array('weak','safe'),
            array('role,weak', 'numerical', 'integerOnly'=>true),
            array('username', 'unique','message' => '管理员已存在!'),
            array('uid,username,roleName,weak', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
                'resources' => array(self::HAS_MANY, 'ResAuthModel', 'uid'),
                'rolez' => array(self::BELONGS_TO, 'RoleModel', 'role'),
        );
    }


    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('uid',$this->uid);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('salt',$this->salt,true);
        $criteria->compare('role',$this->role);
 
        $criteria->with = array('rolez');
        // $criteria->compare('rolez.name', $this->roleName?$this->roleName:'', true);
        $criteria->compare('rolez.name', $this->roleName, true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize' => 25,
            ),
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'roleName' => array(
                        'asc' => 'rolez.name',
                        'desc' => 'rolez.name DESC',
                        ),
                    '*'
                    ),
                ),
        ));


    }


   
    public static function getRoleName($role){
         $roleMode = RoleModel::model()->findByPk($role);
         if($roleMode)
            return $roleMode->name;
         return "";
    }


     /**
     * 创建
     */
    // public function createAdminUser($arAdminUser, &$error = array())
    // {
    //     $admin_users=new AdminUserModel;
    //     $salt_hash = Salt::generateSalt($arAdminUser['password']);
    //     $arAdminUser['password'] = $salt_hash['hash'];
    //     $arAdminUser['salt']     = $salt_hash['salt'];
    //     // change time 06-13
    //     //   add new field to adminUser 
    //     //   WEEK Default = 0
        
    //     $arAdminUser['weak']  =  0;
    //     // $admin_users->setAttributes($arAdminUser, false);
    //     if ($admin_users->save()) {
    //         // return true;
    //         return $admin_users->uid;
    //     } else {
    //         $error = $admin_users->getErrors();
    //         return false;
    //     }
    // }
     public function addAdminUser($data,&$errors){
        $salt_hash = Salt::generateSalt($data['password']);
        if(@$data['password'])
            $data['password'] = $salt_hash['hash'];
        $data['salt']     = $salt_hash['salt'];

         $data['weak']  =  1;
         $this->attributes = $data;
        if ($this->save()) {
            return $this->uid;
        } else {
            $error = $this->getErrors();
            return false;
        }

     }
    /**
     * 读取
     */
    public function getAdminUserId($uid)
    {
        // $admin_users=AdminUserModel::model()->findByPk($uid);
        $admin_users=$this->findByPk($uid);
        return $admin_users;
    }

    public function listAllAdminUser($arCriterion)
    {
        // $admin_users=AdminUserModel::model()->findAll($arCriterion);
        $admin_users=$this->findAll($arCriterion);
        return $admin_users;
    }

    public function getAdminUserWhere($condition, $params)
    {
        // $admin_users=AdminUserModel::model()->find($condition.'=:'.$condition, array(':'.$condition=>$params));
         $admin_users=$this->find($condition.'=:'.$condition, array(':'.$condition=>$params));
        return $admin_users;
    }

    /**
     * 更新
     */
    public function updateAdminUser($arAdminUser, $uid)
    {
        $admin_users=AdminUserModel::model()->findByPk($uid);
        $salt_hash = Salt::generateSalt($arAdminUser['password']);
        $arAdminUser['password'] = $salt_hash['hash'];
        $arAdminUser['salt']     = $salt_hash['salt'];
        $admin_users->setAttributes($arAdminUser, false);
        return $admin_users->save();
    }

    /**
     * 更新
     * param obj
     * BY obj reference
     */
    public function updateAdminUserRef($arAdminUser){
        $salt_hash = Salt::generateSalt($arAdminUser['password']);
        $arAdminUser['password'] = $salt_hash['hash'];
        $arAdminUser['salt']     = $salt_hash['salt'];
        $this->setAttributes($arAdminUser, false);
        return $this->save();
    }

    /**
     * update 但不更新密码
     */
    public function updateNoPsw($data){
        $this->attributes = $data;
        if($this->save())
            return $this->uid;
        $errors = '更新失败';
        return false; 
    }

    /**
     * 删除
     */
    public function deleteAdminUser($uid)
    {
        // return (AdminUserModel::model()->deleteByPk($uid) ? true : false);
        return ($this->deleteByPk($uid) ? true : false);
    }

    /*
    *  check password
    *  length > 12
    *  [a-z]+  [A-Z]+  [0-9]+数字+字母(必须包含大小写)
    *  #^.*?([a-zA-Z\d])\1\1.*?$#  不允许重复3次以上
    *  /[`~!@#$%^&*_+<>{}\/'[\]]/im  特殊字符
    */
    public function checkPassword($pass){

        $preg1 = '/[a-z]/';
        $preg2 = '/[A-Z]/';
        $preg3 = '/[1-9]/';
        $preg4 = '#^.*?([a-zA-Z\d])\1\1.*?$#';
        $preg5 = "/[`~!@#$%^&*_+<>{}\/'[\]]/im";

        if( strlen($pass) >32 || strlen($pass) < 12){
            return array('code'=>1,'msg'=>'长度错误');
        }
        if( !preg_match($preg1, $pass) && !preg_match($preg2, $pass) && !preg_match($preg3, $pass)){
            return array('code'=>2,'msg'=>'数字+字母(必须包含大小写)');
        }
        if( preg_match($preg4, $pass)){
            return array('code'=>3,'msg'=>'字符不允许重复3次以上');
        }
        if( !preg_match($preg5, $pass)){
            return array('code'=>4,'msg'=>'必须包含特殊字符');
        }

        return array('code'=>0,'msg'=>'修改成功');
    }



}
