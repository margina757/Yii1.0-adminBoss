<?php

/**
 * This is the model class for table "resources_auth".
 *
 * The followings are the available columns in table 'resources_auth':
 * @property integer $rid
 * @property integer $frid
 * @property integer $uid
 * @property string $controller
 * @property string $action
 * @property integer $status
 */
class ResourcesAuthModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->params['mainDb'].'.resources_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, controller, action', 'required'),
			array('frid, uid, status', 'numerical', 'integerOnly'=>true),
			array('controller, action', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rid, frid, uid, controller, action, status', 'safe', 'on'=>'search'),
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
			// @todo 请按照下面格式创建关系, 并更新上方注释用例
			'res'  => array(self::BELONGS_TO, 'ResourcesModel', 'rid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rid' => '主键',
			'frid' => '外键',
			'uid' => '用户角色ID',
			'controller' => '控制器名称',
			'action' => '动作名称',
			'status' => '授权状态',
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

		$criteria->compare('rid',$this->rid);
		$criteria->compare('frid',$this->frid);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ResourcesAuthModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function primaryKey()
	{
		return 'rid';
	}

   /**
     * 创建
     */
    public function createResAuth($arResAuth)
    {
        $auth= new ResourcesAuthModel();
        $auth->frid       = $arResAuth['frid'];
        $auth->uid        = $arResAuth['uid'];
        $auth->controller = $arResAuth['controller'];
        $auth->action     = $arResAuth['action'];
        $auth->status     = $arResAuth['status'];
        return $auth->save();
    }

    /**
     * 读取
     */
    public function getResAuthId($rid)
    {
        $auth=ResourcesAuthModel::model()->findByPk($rid);
        return $auth;
    }

    public function listAllResourceAuth($condition, $params)
    {
        $result = ResourcesAuthModel::model()->findAll($condition, $params);

        if ($result) {
            $result = Tool::convert2Array($result);
            return $result;
        }

        return array();
    }

    public function getResAuthWhere($condition, $params)
    {
        $auth=ResourcesAuthModel::model()->find($condition.'=:'.$condition, array(':'.$condition=>$params));
        return $auth;
    }

    public function getAuth($id) {
        // todo cache

        $authAr = ResourcesAuthModel::model()->findByPk($id);

        if ($authAr) {
            return $authAr->attributes;
        }

        return array();
    }

    public function listAllByType($id) {

        $result = ResourcesAuthModel::model()->findAll('uid=:uid', array(':uid'=>$id));

        if ($result) {
            $result = Tool::convert2Array($result);
            return $result;
        }

        return array();
    }

    /**
     * 更新
     */
    public function updateResAuth($arResAuth, $rid)
    {
        $model = ResourcesAuthModel::model();
        $transaction = $model->dbConnection->beginTransaction();
        try
        {
            $auth=ResourcesAuthModel::model()->findByPk($rid);
            $auth->setAttributes($arResAuth, false);
            $transaction->commit();
            $auth->save();
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
        }
    }

    /**
     * 更新所有授权资源方法
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public function updateAllResAuth($arResAuth, $condition, $params)
    {
        ResourcesAuthModel::model()->updateAll($arResAuth, $condition, $params);
    }

    /**
     * 删除
     */
    public function deleteResAuth($rid)
    {
        return (ResourcesAuthModel::model()->deleteByPk($rid) ? true : false);
    }

    /**
     * 统计方方法
     */
    public function countResAuth($arCriterion)
    {
        $count = ResourcesAuthModel::model()->count($arCriterion);
        return $count;
    }
}
