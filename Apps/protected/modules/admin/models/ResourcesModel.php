<?php

/**
 * This is the model class for table "resources".
 *
 * The followings are the available columns in table 'resources':
 * @property integer $rid
 * @property string $controller
 * @property string $action
 * @property string $description
 */
class ResourcesModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->params['mainDb'].'.resources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('controller, action, description', 'required'),
			array('controller, action, description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rid, controller, action, description', 'safe', 'on'=>'search'),
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
			'auth'  => array(self::HAS_MANY, 'ResourcesAuthModel', 'frid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rid' => '主键',
			'controller' => '控制器名称',
			'action' => '动作名称',
			'description' => '资源描述',
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
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 20,
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ResourcesModel the static model class
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
    public function createRes($arRes, &$error = null)
    {
        $res=new ResourcesModel;
        $res->controller  = $arRes['controller'];
        $res->action      = $arRes['action'];
        $res->description = $arRes['description'];
        if ($res->save()) {
            return $res->rid;
        }
        $error = $res->getErrors();

        return false;
    }

    /**
     * 读取
     */
    public function getResId($rid)
    {
        $res=ResourcesModel::model()->findByPk($rid);
        return $res;
    }

    public function getResWhere($condition, $params)
    {
        $res=ResourcesModel::model()->find($condition.'=:'.$condition, array(':'.$condition=>$params));
        return $res;
    }

    public function listAllResource() {
        $result = ResourcesModel::model()->findAll();

        if ($result) {
            $result = Tool::convert2Array($result);
            return $result;
        }

        return array();
    }

    public function listAllByType() {

        $result = ResourcesModel::model()->findAll();

        if ($result) {
            $result = Tool::convert2Array($result);
            return $result;
        }

        return array();
    }

    /**
     * 更新
     */
    public function updateRes($arRes, $rid)
    {
        $model = ResourcesModel::model();
        $res=ResourcesModel::model()->findByPk($rid);
        $res->setAttributes($arRes, false);
        $res->save();
    }

    /**
     * 更新所有资源方法
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public function updateAllRes($arResAuth, $condition, $params)
    {
        ResourcesModel::model()->updateAll($arResAuth, $condition, $params);
    }

    /**
     * 删除
     */
    public function deleteRes($rid)
    {
        return (ResourcesModel::model()->deleteByPk($rid) ? true : false);
    }

    /**
     * 统计方方法
     */
    public function countRes($arCriterion)
    {
        $count = ResourcesModel::model()->count($arCriterion);
        return $count;
    }

}
