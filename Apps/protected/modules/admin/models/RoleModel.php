<?php

/**
 * This is the model class for table "role".
 *
 * The followings are the available columns in table 'role':
 * @property integer $role
 * @property string $name
 * @property string $created
 * @property string $updated
 */
class RoleModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->params['mainDb'].'.role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'unique','message'=>'用户组[{value}] 已经存在.'),
			array('name', 'length', 'max'=>50),
			array('created, updated', 'safe'),
			array('updated','default', 'value'=>new CDbExpression('NOW()'),
			'setOnEmpty'=>false,'on'=>'update'),
			array('created,updated','default', 'value'=>new CDbExpression('NOW()'),
			'setOnEmpty'=>false,'on'=>'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('role, name, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'role' => '主键',
			'name' => '记录用户uid',
			'created' => '创建时间',
			'updated' => '修改时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('role',$this->role);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoleModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

   /**
     * addRole
     * @param  mixed $data 
     * @return void
     */
    public function addRole($data, &$errors) {

        $roleModel = new RoleModel();
        $roleModel->attributes = $data;
        if ($roleModel->save()) {
            return $roleModel->role;
        }

        $errors = $roleModel->getErrors();
        return false;
    }

    /**
     * updateRole 
     * 
     * @param  mixed $data 
     * @param  mixed $errors 
     * @return void
     */
    public function updateRole($sid, $data, &$errors) {

        $roleModel = RoleModel::model()->findByPk($sid);
        $roleModel->attributes = $data;
        if ($roleModel->save()) {
            // TODO cache
            return true;
        }

        $errors = $roleModel->getErrors();
        return false;
    }

    /**
     * getRole 
     * 
     * @param  mixed $id 
     * @return void
     */
    public function getRole($id) {

        $roleAr = RoleModel::model()->findByPk($id);

        if ($roleAr) {
            return $roleAr->attributes;
        }

        return array();
    }

    /**
     * deleteRole 
     * 
     * @param  mixed $id 
     * @param  mixed $errors 
     * @return void
     */
    public function deleteRole($id, &$errors = null){
        $result = RoleModel::model()->findByPk($id)->delete();

        return $result;
    }

	/**
	 * listAllRole 
	 * @return void
	*/
	public function listAllRole($criteria=false) {

		if(!$criteria)
			$criteria = new CDbCriteria();
		$criteria->addCondition('role >= 100');
		
		$result = RoleModel::model()->findAll($criteria);

		if ($result) {
			$result = Tool::convert2Array($result);
			return $result;
		}

		return array();
	}
}
