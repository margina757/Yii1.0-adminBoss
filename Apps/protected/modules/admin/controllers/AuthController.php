<?php
/**
 * 2014-07-30 liuxue
 * 权限控制
 */
class AuthController extends AdminBaseController{
	/**
	 * list admin_users
	 */
	public function actionIndex(){
		$model = new AdminUserModel('search');

		$model->unsetAttributes();
		if(Yii::app()->request->getParam('AdminUserModel')){
			$model->attributes = $_GET['AdminUserModel'];
		}

		$this->render('list',array(
			'model'=>$model
		));
	}

	/**
	 * add admin_users
	 */
	public function actionAdd(){
		$adminUserModel = new AdminUserModel();

		if(Yii::app()->request->isPostRequest){
			$userData = $_POST['AdminUserModel'];

			if($adminUserModel->addAdminUser($userData,$error)){
			$this->_setSuccessFlash('添加用户成功');
			$this->redirect('/admin/auth');
			}else{
			$this->_setErrorFlash(Tool::formatError($error));
			}
		}

		//非提交
		$criteria = new CDbCriteria;
		$criteria->select = "role,name";
		$tmpArr = RoleModel::model()->listAllRole($criteria);
		$listAllRole = array();
		foreach ($tmpArr as $key => $value) {
			$listAllRole[$value['role']] = $value['name'];
		}

		$this->render('user',array(
			'model' => $adminUserModel,
			'listAllRole' => $listAllRole,
		));
	}

	/**
	 * update admin_users
	 */
	public function actionUpdate(){
		$uid = Yii::app()->request->getParam('id');
		$adminUserModel = AdminUserModel::model()->findByPk($uid);

		if(Yii::app()->request->isPostRequest){
			$adminUserData = Yii::app()->request->getParam('AdminUserModel');
			if($adminUserData['password'])
				$res = $adminUserModel->updateAdminUserRef($adminUserData);
			else
				$res = $adminUserModel->updateNoPsw($adminUserData);

			if($res){
				$this->_setSuccessFlash('更新成功');
				$this->redirect('/admin/auth');
			}else
				$this->_setErrorFlash('更新失败');
		}

		$adminUserModel->password = '';
		$criteria = new CDbCriteria;
		$criteria->select = "role,name";
		$tmpArr = RoleModel::model()->listAllRole($criteria);
		$listAllRole = array();
		foreach ($tmpArr as $key => $value) {
			$listAllRole[$value['role']] = $value['name'];
		}

		$this->render('user',array(
			'title'=>'更新管理员',
			'model'=>$adminUserModel,
			'isUpdate'=>'1',
			'listAllRole'=>$listAllRole
		));
	}

	/**
	 * delete admin_user
	 */
	public function actionDelete(){
		$uid = Yii::app()->request->getParam('id');
		$adminUserModel = AdminUserModel::model()->findByPk($uid);

		if($adminUserModel->delete())
			$this->_setSuccessFlash('删除管理员成功');
		else
			$this->_setErrorFlash('删除管理员失败');
		$this->redirect('/admin/auth');
	}

	/**
	 * role list
	 */
	public function actionListRole(){
		$roleModel = new RoleModel('search');
		$roleModel->unsetAttributes();
		if(Yii::app()->request->getParam('RoleModel')){
			$roleModel->attributes = $_GET['RoleModel'];
		}

		$this->render('role_list',array(
			'model'=>$roleModel,
		));
	}

	/**
	 * add role
	 */
	public function actionAddRole(){
		$roleModel = new RoleModel();
		if ( Yii::app()->request->isPostRequest ) {
			if ( $user = $roleModel->addRole($_POST['RoleModel'], $error)) {
				$this->_syncResAuth($user);
				$this->_setSuccessFlash('创建成功');
				return $this->redirect(array('/admin/auth/listRole'));
			}else{
				$this->_setErrorFlash(Tool::formatError($error));
			}
		}

		$this->render('role_update', array( 
			'roleModel' => $roleModel,
			'title' => '添加用户组',
			'addRole' => true
		));
	}

	/**
	 * update role
	 */
	public function actionUpdateRole(){
		$id = Yii::app()->request->getParam('id');
		$model = $this->loadModel($id);
		$roleModel = $this->loadRoleModel($id);
		$resourcesAuthModel = ResourcesAuthModel::model();
		$resList = $resourcesAuthModel->listAllByType($id);
		$resAuth = $this->_convertResAuthList($resList);

		if ( Yii::app()->request->isPostRequest ) {

			if ($_POST['RoleModel']) {
				$roleUpdate = $_POST['RoleModel'];
				RoleModel::model()->updateRole($roleUpdate['role'], $roleUpdate, $error);
			}

			if ( isset($_POST['ResourcesAuthModel']) ) {

				// 将所有状态初始化为停用状态
				ResourcesAuthModel::model()->updateAllResAuth(array('status'=>0),
				'uid=:uid', array(':uid'=>$id));

				// 循环被勾选的上列表并更新为启用状态
				foreach ( $_POST['ResourcesAuthModel']['rid'] as $rid )
				{
					ResourcesAuthModel::model()->updateResAuth(array('status'=>1), $rid);
				}

				$this->_setSuccessFlash('更新授权成功');
				return $this->redirect(array('/admin/auth/listRole'));
			}
		}

		$this->render('role_update',array(
			'model'=>$model,
			'resAuth' => $resAuth,
			'roleModel' => $roleModel
		));
	}

	/**
	 * delete role
	 * admin_users and admin_user_info : role 
	 */
	public function actiondelRole(){
		$id = Yii::app()->request->getParam('id');
		$roleModel = $this->loadRoleModel($id);
		$oldRoleAttrs = $roleModel->attributes;
		$resourcesAuthModel = ResourcesAuthModel::model();

		if($roleModel->delete()){
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid={$id}");
			if($resourcesAuthModel->deleteAll($criteria)){
				$this->_setSuccessFlash('删除成功');
			}else{
				$this->_setErrorFlash('删除失败');
				$roleModel->attributes = $oldRoleAttrs;
				$roleModel->save();
			}
		}else{
			$this->_setErrorFlash('删除失败');
		}
		$this->redirect('/admin/auth/listRole');
	}

	/**
	 * list resource
	 */
	public function actionListRes(){
		$resourcesModel = new ResourcesModel('search');
		$resourcesModel->unsetAttributes();
		if ( isset($_GET['ResourcesModel']) ){
			$resourcesModel->attributes=$_GET['ResourcesModel'];
		}

		$this->render('res_list', array(
			 'model' => $resourcesModel
		));
	}

	/**
	 * add resource
	 */
	public function actionAddRes(){
		$resourcesModel = new ResourcesModel();
		if ( Yii::app()->request->isPostRequest ) {
			$res_id = $resourcesModel->createRes($_POST['ResourcesModel'],$error);
			if ($res_id) {
				// 将新资源同步至授权表 默认状态为停用
				$this->_syncRoleAuth($res_id);
				$this->_setSuccessFlash('创建成功');
				return $this->redirect(array('/admin/auth/listRes'));
			} else {
				$errMsg = Tool::formatError($error);
				$this->_setErrorFlash($errMsg);
			}
		}
		$this->render('add_auth', array( 
			'model' => $resourcesModel,
		));
	}

	/**
	 * update resouce
	 */
	public function actionUpdateRes(){
		$id = Yii::app()->request->getParam('id');
		$model = $this->loadResourcesModel($id);
		if ( Yii::app()->request->isPostRequest ) {
			if ( isset($_POST['ResourcesModel']) ) {
				$resAR = array(
					'controller' => $_POST['ResourcesModel']['controller'],
					'action' => $_POST['ResourcesModel']['action'],
					'description'=>$_POST['ResourcesModel']['description']
				);
				$model->updateRes($resAR, $id);
				// 同步更新至授权信息表
				ResourcesAuthModel::model()->updateAllResAuth($resAR,
				'frid=:frid', array(':frid'=>$id));

				$this->_setSuccessFlash('更新资源成功');
				return $this->redirect(array('/admin/auth/listRes'));
			}
		}

		$this->render('res_update',array(
			'model'=>$model
		));
	}

	/**
	 * delete resouce
	 */
	public function actionDelRes(){
		//暂时不添加删除功能
	}

	/**
	 * 同步新创建的资源给授权验证表
	 * @author Howe Isamu <xi4oh4o@gmail.com>
	 */
	private function _syncRoleAuth($rid){
		$roleModel = RoleModel::model();
		foreach ($roleModel->listAllRole() as $role )
		{
			$res = ResourcesModel::model()->getResId($rid);
			$arResAuth = array(
				'uid'        => $role['role'],
				'frid'       => $res->rid,
				'controller' => $res->controller,
				'action'     => $res->action,
				'status'     => 0
			);
			$resourcesAuthModel = new ResourcesAuthModel();
			$resourcesAuthModel->createResAuth($arResAuth);
		}
	}

	/**
	 * 加载资源模型
	 * @author Howe Isamu <xi4oh4o@gmail.com>
	 */
	public function loadResourcesModel($id){
		$resourcesModel = ResourcesModel::model()->findByPk($id);
		if ( $resourcesModel===null )
			throw new CHttpException(404, 'The requested page does not exist.');
		return $resourcesModel;
	}

	/**
	* 同步所有可用资源给授权验证表当前用户
	* @author Howe Isamu <xi4oh4o@gmail.com>
	*/
	private function _syncResAuth($uid){
		$resourcesAuthModel = new ResourcesAuthModel();
		$allResource = ResourcesModel::model()->listAllResource();
		foreach ( $allResource as $res )
		{
			$arResAuth = array(
				'uid'        => $uid,
				'frid'       => $res['rid'],
				'controller' => $res['controller'],
				'action'     => $res['action'],
				'status'     => 0
			);
			if(!$resourcesAuthModel->createResAuth($arResAuth)){
				// echo "errors"; var_dump($resourcesAuthModel);die;
			}
		}
	}

	/**
	 * 根据uid加载资源授权模型
	 * @author Howe Isamu <xi4oh4o@gmail.com>
	 */
	public function loadModel($id){
		$resourcesAuthModel=ResourcesAuthModel::model()->find('uid=:uid', array(':uid'=>$id));
		if( $resourcesAuthModel === null ){
			if (Yii::app()->user->id == 1 ) {
				$this->_setErrorFlash('超级管理员不能编辑权限');
				return $this->redirect(array('/admin/auth/listRole'));
			}
			throw new CHttpException(404, '当前用户没有任何授权，请先添加授权');
		}

		$resAuth = $this->loadAllResAuth($id);
		$result = array();
		foreach ( $resAuth as $v ) {
			$result[] = $v['rid'];
		}
		$resourcesAuthModel->rid = $result;
		return $resourcesAuthModel;
	}

	/**
	 * 加载当前uid所有授权信息
	 * @author Howe Isamu <xi4oh4o@gmail.com>
	 */
	public function loadAllResAuth($id){
		$criteria = new CDbCriteria();
		$criteria->condition = 'uid = :uid and status = :status';
		$criteria->params = array(':uid' => $id, ':status' => 1);

		$resourcesAuthModel=ResourcesAuthModel::model()->findAll($criteria);
		if($resourcesAuthModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$resourcesAuthModel = $this->convert2Array($resourcesAuthModel);
		return $resourcesAuthModel;
	}

	/**
	* loadRoleModel
	*
	* @param  mixed $id
	* @return void
	*/
	public function loadRoleModel($id){
		$roleModel = RoleModel::model()->findByPk($id);
		if ( $roleModel === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $roleModel;
	}

	/**
	 * 根据需求转换数据结构
	 * @return array rid => description
	 * @author Howe Isamu <xi4oh4o@gmail.com>
	 */
	private function _convertResAuthList($resList) {
		$result = array();
		$resourcesModel = ResourcesModel::model();
		foreach ($resList as $item) {
			$result[$item['controller']][$item['rid']] = $resourcesModel->getResId($item['frid'])->description;
		}
		return $result;
	}

	/**
	* convert2Array 
	* 对象数组 => 属性数组
	*/
	private function convert2Array($objects) {
		if (!is_array($objects) || !is_object(current($objects))) {
			return $objects;
		}

		$result = array();
		foreach ($objects as $obj) {
			$result[] = $obj->attributes;
		}

		return $result;
	}

}