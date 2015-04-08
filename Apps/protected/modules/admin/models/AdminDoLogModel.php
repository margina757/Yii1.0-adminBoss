<?php

/**
 * This is the model class for table "admin_do_log".
 *
 * The followings are the available columns in table 'admin_do_log':
 * @property integer $adlid
 * @property string $module
 * @property string $client_ip
 * @property string $handler
 * @property integer $target_id
 * @property string $info
 * @property string $memo
 * @property string $table
 * @property string $operation
 * @property string $created
 * @property string $updated
 */
class AdminDoLogModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_do_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module, client_ip, handler, target_id, info, memo', 'required'),
			array('target_id', 'numerical', 'integerOnly'=>true),
			array('module, client_ip, handler, table, operation', 'length', 'max'=>30),
			array('info', 'length', 'max'=>6000),
			array('memo', 'length', 'max'=>1000),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adlid, module, client_ip, handler, target_id, info, memo, table, operation, created, updated', 'safe', 'on'=>'search'),
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
			'adlid' => '主键',
			'module' => '模块',
			'client_ip' => '客户端IP',
			'handler' => '操作人',
			'target_id' => '目标ID',
			'info' => 'SQL语句',
			'memo' => '描述',
			'table' => '操作表名称',
			'operation' => 'SQL操作类型',
			'created' => '创建时间',
			'updated' => '更新时间',
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

		$criteria->compare('adlid',$this->adlid);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('client_ip',$this->client_ip,true);
		$criteria->compare('handler',$this->handler,true);
		$criteria->compare('target_id',$this->target_id);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('table',$this->table,true);
		$criteria->compare('operation',$this->operation,true);
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
	 * @return AdminDoLogModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
