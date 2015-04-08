<?php 
/**
 * @file  LibInfoController.php
 * @synopsis 本馆概况信息管理
 * @author <jackun@lonlife.net>
 * @created 2015-02-02 09:20:44
 * @modified 
 */
class LibInfoController extends AdminBaseController{

	/**
	 * @synopsis 图书馆概况栏目列表
	 */
	public function actionIndex(){
		$libInfoModel = new LibInfoModel('search');

		$libInfoModel->unsetAttributes();

		if(isset($_GET['LibInfoModel'])){
			$libInfoModel->attributes = $_GET['LibInfoModel'];
		}

		$this->render('/libinfo/lib_list',array(
			'model' => $libInfoModel,
		));
		
	}

	/**
	 * @synopsis 图书馆概况栏目添加
	 */
	public function actionAddLibInfo(){
		$libInfoModel = new LibInfoModel();

		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getParam('LibInfoModel');
			$data['writer'] = Yii::app()->user->name;
			$data['created'] = date('Y-m-d H:i:s');
			$data['updated'] = date('Y-m-d H:i:s');
			
			if($libInfoModel->addLibInfo($data,$error)){
				$this->_setSuccessFlash('添加子栏目成功！');
				return $this->redirect('index');
			}else{
				$this->_setErrorFlash(Tool::formatError($error));
			}
		}

		$this->render('/libinfo/lib_add',array(
			'model' => $libInfoModel,
		));
	}

	/**
	 * @synopsis 更新图书馆栏目信息
	 */
	public function actionUpdateLibInfo(){
		$id = Yii::app()->request->getQuery('id');
		$libInfoModel = LibInfoModel::model()->getModelById($id);
		
		if(!$libInfoModel){
			$this->_setErrorFlash('要更新的栏目不存在，或已被删除！');
			return $this->redirect('/admin/LibInfo/Index');
		}

		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getParam('LibInfoModel');
			$data['updated'] = date('Y-m-d H:i:s');

			if($libInfoModel->updateLibInfo($id,$data,$error)){
				$this->_setSuccessFlash('更新栏目信息成功！');
				return $this->redirect('/admin/LibInfo/Index');
			}else{
				$this->_setErrorFlash(Tool::formatError($error));
			}
		}

		$this->render('/libinfo/lib_add',array(
			'model' => $libInfoModel,
			'id' => $id,
		));
	}

	/**
	 * @synopsis ​删除子栏目信息
	 */
	public function  actionDelLibInfo($id){
		$libInfoModel = LibInfoModel::model()->getModelById($id);

		if(!$libInfoModel){
			$this->_setErrorFlash('栏目不存在，或已被删除！');
			return $this->redirect('/admin/LibInfo/Index');
		}

		if($libInfoModel->delLibInfo($id,$error)){
			$this->_setSuccessFlash('');
		}else{
			$this->_setErrorFlash(Tool::formatError($error));
		}

		return $this->redirect('/admin/LibInfo/Index');

	}

}