<?php
/**
 * @file  FlinkController.php
 * @synopsis 友情链接管理
 * @author <jackun@lonlife.net>
 * @created 2015-02-04  15:39:33
 * @modified 
 */
class FlinkController extends AdminBaseController{

	/**
	 * 友情链接列表
	 */
	public function actionIndex(){
		$flinkModel = new FlinkModel('search');
		
		$flinkModel->unsetAttributes();
		if(isset($_GET['FlinkModel'])){
			$flinkModel->attributes = $_GET['FlinkModel'];
		}

		$isCheck = array(
			array(
				'status' => 0,
				'status_check' => '未审核'
			),
			array(
				'status' => 1,
				'status_check' => '已审核'
			)
		);

		$flinkType = $this->_getformatFlinkType();

		$this->render('flink_list',array(
			'model' => $flinkModel,
			'isCheck' => $isCheck,
			'flinkType' => $flinkType,

 		));
	}

	/**
	 * 添加友情链接
	 */
	public function actionAdd(){
		$flinkModel = new FlinkModel();
		
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getParam('FlinkModel');
			$data['created'] = date('Y-m-d H:i:s');
			$data['updated'] = date('Y-m-d H:i:s');
			$allowedPic = Yii::app()->params['allowedPic'];

			$upFile = CUploadedFile::getInstance($flinkModel,'logo');
			if(is_object($upFile) && get_class($upFile)==='CUploadedFile'){
				$dirArr = UploadFiles::getDir();
				$ext = $upFile->extensionName;

				if(!in_array('.'.$ext, $allowedPic)){
					$this->_setErrorFlash('添加友情链接失败，不允许的图片类型！');
					return $this->redirect('/admin/Flink/Add');
				}

				$dbFileName = $dirArr['dbUrl'].'.'.$ext;
				$trueFileName = $dirArr['trueUrl'].'.'.$ext;
				$upFile->saveAs($trueFileName);
				$data['logo'] = $dbFileName;
			}else{
				$data['logo'] = '';
			}

			if($flinkModel->addFlink($data,$error)){
				$this->_setSuccessFlash('添加友情链接成功！');
				return $this->redirect(array('/admin/Flink/Index'));
			}
			if($error){
				$this->_setErrorFlash(Tool::formatError($error));
			}else{
				$this->_setErrorFlash('添加友情链接失败！');
			}
		}

		$this->render('flink_add',array(
			'model' => $flinkModel,
		));
	}

	/**
	 * 修改友情链接信息
	 */
	public function actionUpdate(){
		$id = Yii::app()->request->getQuery('id');
		$flinkModel = FlinkModel::model()->getModelById($id);
		
		if(!$flinkModel){
			$this->_setErrorFlash('修改的栏目不存在，或已经被删除！');
			return $this->redirect(array('/admin/Flink/Index'));
		}

		$oldFlinkData =  $flinkModel->attributes;

		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getParam('FlinkModel');
			$data['updated'] = date('Y-m-d H:i:s');
			$allowedPic = Yii::app()->params['allowedPic'];
			$requestUrl = Yii::app()->request->getUrl();

			$upFile = CUploadedFile::getInstance($flinkModel,'logo');
			if(is_object($upFile) && get_class($upFile)==='CUploadedFile'){
				$dirArr = UploadFiles::getDir();
				$ext = $upFile->extensionName;
				
				if(!in_array('.'.$ext, $allowedPic)){
					$this->_setErrorFlash('修改友情链接失败，不允许的图片类型！');
					return $this->redirect($requestUrl);
				}

				$dbFileName = $dirArr['dbUrl'].'.'.$ext;
				$trueFileName = $dirArr['trueUrl'].'.'.$ext;
				$upFile->saveAs($trueFileName);
				$data['logo'] = $dbFileName;
			}else{
				$data['logo'] = $oldFlinkData['logo'];
			}

			if($flinkModel->updateFlink($id,$data,$error)){
				//删除旧logo
				if(isset($oldFlinkData['logo'])){
					$oldLogo = Yii::app()->basePath.'/..'.$oldFlinkData['logo'];
					if(is_file($oldlogo)){
						@unlink($oldLogo);
					}
				}
				$this->_setSuccessFlash('修改友情链接成功！');
				return $this->redirect(array('/admin/Flink/Index'));
			}
			if($error){
				$this->_setErrorFlash(Tool::formatError($error));
			}else{
				$this->_setErrorFlash('修改友情链接失败！');
			}
		}

		$this->render('flink_add',array(
			'model' => $flinkModel,
			'id' => $id,
		));
	}

	/**
	 * 删除友情链接
	 */
	public function actionDelete($id){
		$flinkModel = FlinkModel::model()->getModelById($id);
		
		if(!$flinkModel){
			$this->_setErrorFlash('修改的栏目不存在，或已经被删除！');
			return $this->redirect(array('/admin/Flink/Index'));
		}

		$oldFlinkData =  $flinkModel->attributes;

		if($flinkModel->delFlink($id)){
			if(isset($oldFlinkData['logo'])){
				$oldLogo = Yii::app()->basePath.'/..'.$oldFlinkData['logo'];
				if(is_file($oldlogo)){
					@unlink($oldLogo);
				}
			}
			$this->_setSuccessFlash('删除友情链接成功！');
			return $this->redirect(array('/admin/Flink/Index'));
		}else{
			$this->_setErrorFlash('删除友情链接失败！');
		}
	}

	/**
	 * 格式化链接类型，用于TbHtml::listData()
	 */
	public function _getformatFlinkType(){
		$i = 0;
		$flinkType = Yii::app()->params['flinkType'];
		$newFlinkType = array();
		foreach ($flinkType as $key => $value) {
			$newFlinkType[$i]['flink'] = $key;
			$newFlinkType[$i]['flink_value'] = $value;
			$i++;
		}
		return $newFlinkType;
	}

	/**
	 * 批量审核链接
	 */
	public function actionMutiIsCheck(){
		$ids = YIi::app()->request->getParam('idArr');
		
		$attributes = array('isCheck' => 1);
		if(FlinkModel::model()->updateByPk($ids,$attributes)){
			$this->ajaxMessage(0,'审核成功！');
		}else{
			$this->ajaxMessage(1,'审核失败！');
		}
	}

	/**
	 * 批量删除友情链接
	 */
	public function actionMutiDelete(){
		$ids = YIi::app()->request->getParam('idArr');
		foreach ($ids as $id) {
			$flinkModel = FlinkModel::model()->getModelById($id);
		
			if(!$flinkModel){
				return $this->ajaxMessage(1,'栏目不存在，或已经被删除！');
			}

			$oldFlinkData =  $flinkModel->attributes;

			if($flinkModel->delFlink($id)){
				if(isset($oldFlinkData['logo'])){
					$oldLogo = Yii::app()->basePath.'/..'.$oldFlinkData['logo'];
					if(is_file($oldlogo)){
						@unlink($oldLogo);
					}
				}
				$this->ajaxMessage(0,'删除友情链接成功！');
			}else{
				$this->ajaxMessage(2,'删除友情链接失败！');
			}
		}
	}

}