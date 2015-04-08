<?php
/**
 * 下载专区,软件管理
 * @author <jackun@lonlife.net>
 */
class DownloadCenterController extends AdminBaseController{

	public function actionIndex(){
		$softInfoModel = new SoftInfoModel('search');
		$softInfoModel->unsetAttributes();
	
    	$ismake = array(
			array(
				'status' => 1,
				'status_mark' => '未审核'
			),
			array(
				'status' => 2,
				'status_mark' => '已审核'	
			)
		);
		$filterData = array();
		$filterData['filetype'] = $this->_getFormatFiletype();
		$filterData['language'] = $this->_getFormatLanguage();
		$filterData['softRank'] = $this->_getFormatSoftRank();
		$filterData['softType'] = $this->_getFormatSoftType();
		$filterData['accredit'] = $this->_getFormatAccredit();

		$arCriteria['title'] = $_GET['SoftInfoModel']['title'];
		$arCriteria['ismark'] = $_GET['SoftInfoModel']['ismark'];
		$arCriteria['writer'] = $_GET['SoftInfoModel']['writer'];

		if(isset($_GET['SoftInfoModel'])){
			$softInfoModel->attributes = $_GET['SoftInfoModel'];
		}

		$this->render('/download_center/index',array(
			'model' => $softInfoModel,
			'criteria' => $arCriteria,
			'ismake' => $ismake,
			'filterData' => $filterData,
		));
	}

	//添加软件信息
	public function actionAddSoft(){
		$request_url = Yii::app()->request->getUrl();
		$cid = Yii::app()->request->getQuery('cid') ? Yii::app()->request->getQuery('cid') : 14;
		$articleContentModel = new ArticleContentModel();
		$softInfoModel = new SoftInfoModel();
		$allowedPic = Yii::app()->params['allowedPic'];
		$allowedSoft = Yii::app()->params['allowedSoft'];

		if(Yii::app()->request->isPostRequest){

			$articleData = Yii::app()->request->getParam('ArticleContentModel');
			$softData = Yii::app()->request->getParam('SoftInfoModel');

			$articleData['cid'] = $cid;
			// //关键词为空自动获取关键词（默认为5个）
			// if(!$articleData['keywords']){
			// 	$wordsArr = $articleContentModel->buildKeywords($articleData['body']);
			// 	$keywords = implode(',',$wordsArr);
			// 	$articleData['keywords'] = $keywords;
			// }
			if(!$articleData['created']){
				$articleData['created'] = date('Y-m-d H:i:s');
			}
			if($articleData['flag']){
				$articleData['flag'] = implode(',',$articleData['flag']);
			}
			if(!$articleData['writer']){
				$articleData['writer'] = Yii::app()->user->name;
			}
			$articleData['updated'] = date('Y-m-d H:i:s');


			$articleLitpicArr = $_FILES['ArticleContentModel'];
			$softLinkArr = $_FILES['SoftInfoModel'];

			//导入图片上传类
			Yii::import('application.extensions.image.Image');
			//缩略图上传操作
			if($articleLitpicArr['error']['litpic'] == 0){
				$ext = UploadFiles::getExtName($articleLitpicArr['name']['litpic'])['ext'];
				if($ext){
					
					if(!in_array($ext, $allowedPic)){
						$this->_setErrorFlash('添加软件信息失败，不允许的图片类型！');
						return $this->redirect('/admin/DownloadCenter/AddSoft');
					}

					$dirArr = UploadFiles::getDir();
					//数据库内存储的图片路径及名称
					$litpic = $dirArr['dbUrl'].'_small'.$ext;
					//文件保存的真实路径及名称
					$trueUrl = $dirArr['trueUrl'].$ext;
					//缩略图保存的真实路径及名称
					$trueSmallUrl = $dirArr['trueUrl'].'_small'.$ext;

					if(!move_uploaded_file($articleLitpicArr['tmp_name']['litpic'], $trueUrl)){
						$litpic = '';
					}

					//生成缩略图,图片名称例如:14228578606602_small.jpg
					$image = new Image($trueUrl);
					$image->resize(400, 100);
					$image->save($trueSmallUrl);

				}else{
					$litpic = '';
				}

				$articleData['litpic'] = $litpic;
			}

			//软件上传操作
			if($softLinkArr['error']['link'] == 0){
				$ext = UploadFiles::getExtName($softLinkArr['name']['link'])['ext'];
				$fileName = UploadFiles::getExtName($softLinkArr['name']['link'])['preName'];
				if($ext){
					
					if(!in_array($ext, $allowedSoft)){
						$this->_setErrorFlash('添加软件信息失败，不允许的软件类型！');
						return $this->redirect('/admin/DownloadCenter/AddSoft');
					}

					$softDirArr = UploadFiles::getSoftDir();
					$link = $softDirArr['dbUrl'].$fileName.$ext;
					$trueUrl = $softDirArr['trueUrl'].$fileName.$ext;
					if(!move_uploaded_file($softLinkArr['tmp_name']['link'], $trueUrl)){
						$link = '';
					}
				}else{
					$link = '';
				}

				$softData['link'] = $link;
			}

			$aid = $articleContentModel->add($articleData,$errors);

			if($aid){
				$softData['aid'] = $aid;
				if($softInfoModel->addSoft($softData,$error)){
					$this->_setSuccessFlash('添加软件成功');
					return $this->redirect(array('/admin/DownloadCenter/index/cid/'.$cid));
				}else{
					$articleContentModel->articleTrueDelete($aid,$errors);
					$this->_setErrorFlash(Tool::formatError($error));
					return $this->redirect(array($request_url));
				}
			}else{
				$this->_setErrorFlash(Tool::formatError($error));	
				$this->_setErrorFlash('添加软件失败');	
				return $this->redirect(array($request_url));
			}
		}

		$this->render('/download_center/soft_add', array(
			'articleContentModel' => $articleContentModel,
			'softInfoModel' => $softInfoModel,
		));
	}


	//修改软件信息
	public function actionUpdateSoft(){
		$request_url = Yii::app()->request->getUrl();
		$aid = Yii::app()->request->getQuery('aid');

		$articleContentModel = ArticleContentModel::model()->findByPk($aid);
		$softInfoModel = SoftInfoModel::model()->getModelByAid($aid);
		$allowedPic = Yii::app()->params['allowedPic'];
		$allowedSoft = Yii::app()->params['allowedSoft'];

		if(!$articleContentModel || !$softInfoModel){
			$this->_setErrorFlash('该记录不存在，或已经被删除！');
			return $this->redirect(array('/admin/DownloadCenter/Index'));
		}

		//记录旧数据
		$oldArticleData = $articleContentModel->attributes;
		$oldSoftData = $softInfoModel->attributes;

		$flag = explode(',',$oldArticleData['flag']);

		if(Yii::app()->request->isPostRequest){
			$articleData = Yii::app()->request->getParam('ArticleContentModel');
			$softData = Yii::app()->request->getParam('SoftInfoModel');

			if($articleData['flag']){
				$articleData['flag'] = implode(',',$articleData['flag']);
			}else{
				$articleData['flag'] = NULL;
			}

			if(!$articleData['writer']){
				$articleData['writer'] = Yii::app()->user->name;
			}
			$articleData['updated'] = date('Y-m-d H:i:s');


			$articleLitpicArr = $_FILES['ArticleContentModel'];
			$softLinkArr = $_FILES['SoftInfoModel'];

			//导入图片上传类
			Yii::import('application.extensions.image.Image');
			//缩略图上传操作
			if($articleLitpicArr['error']['litpic'] == 0){
				$ext = UploadFiles::getExtName($articleLitpicArr['name']['litpic'])['ext'];
				if($ext){
					
					if(!in_array($ext, $allowedPic)){
						$this->_setErrorFlash('修改软件信息失败，不允许的图片类型！');
						return $this->redirect($request_url);
					}

					$dirArr = UploadFiles::getDir();
					//数据库内存储的图片路径及名称
					$litpic = $dirArr['dbUrl'].'_small'.$ext;
					//文件保存的真实路径及名称
					$trueUrl = $dirArr['trueUrl'].$ext;
					//缩略图保存的真实路径及名称
					$trueSmallUrl = $dirArr['trueUrl'].'_small'.$ext;

					if(!move_uploaded_file($articleLitpicArr['tmp_name']['litpic'], $trueUrl)){
						$litpic = $oldArticleData['litpic'];
					}

					//生成缩略图,图片名称例如:14228578606602_small.jpg
					$image = new Image($trueUrl);
					$image->resize(400, 100);
					$image->save($trueSmallUrl);

				}else{
					$litpic = $oldArticleData['litpic'];
				}
			}else{
				$litpic = $oldArticleData['litpic'];
			}
			$articleData['litpic'] = $litpic;

			//软件上传操作
			if($softLinkArr['error']['link'] == 0){
				$ext = UploadFiles::getExtName($softLinkArr['name']['link'])['ext'];
				$fileName = UploadFiles::getExtName($softLinkArr['name']['link'])['preName'];
				if($ext){
					
					if(!in_array($ext, $allowedSoft)){
						$this->_setErrorFlash('修改软件信息失败，不允许的软件类型！');
						return $this->redirect($request_url);
					}

					$softDirArr = UploadFiles::getSoftDir();
					$link = $softDirArr['dbUrl'].$fileName.$ext;
					$trueUrl = $softDirArr['trueUrl'].$fileName.$ext;
					if(!move_uploaded_file($softLinkArr['tmp_name']['link'], $trueUrl)){
						$link = $oldSoftData['link'];
					}
				}else{
					$link = $oldSoftData['link'];
				}
			}else{
				$link = $oldSoftData['link'];
			}
			$softData['link'] = $link;
			
			if($articleContentModel->articleUpdate($aid,$articleData,$errors)){
				if($softInfoModel->updateSoft($aid,$softData,$error)){
					$this->_setSuccessFlash('软件信息修改成功');
					return $this->redirect(array('/admin/DownloadCenter/index'));
				}else{
					$articleContentModel->articleUpdate($aid,$oldArticleData,$errors);
					$this->_setErrorFlash(Tool::formatError($error));
				}
			}else{
				$this->_setErrorFlash('软件信息修改失败！');
				$this->_setErrorFlash(Tool::formatError($errors));
			}
		}

		$this->render('/download_center/soft_update',array(
			'articleContentModel' => $articleContentModel,
			'softInfoModel' => $softInfoModel,
			'flag' => $flag,
		));
	}

	public function _getFormatFiletype(){
		$i = 0;
		$filetype = Yii::app()->params['filetype'];
		$newFiletype = array();
		foreach ($filetype as $key => $value) {
			$newFiletype[$i]['filetype'] = $key;
			$newFiletype[$i]['filetype_value'] = $value;
			$i++;
		}
		return $newFiletype;
	}

	public function _getFormatLanguage(){
		$i = 0;
		$language = Yii::app()->params['language'];
		$newLanguage = array();
		foreach ($language as $key => $value) {
			$newLanguage[$i]['language'] = $key;
			$newLanguage[$i]['language_value'] = $value;
			$i++;
		}
		return $newLanguage;
	}

	public function _getFormatSoftRank(){
		$i = 0;
		$softRank = Yii::app()->params['softRank'];
		$newSoftRank = array();
		foreach ($softRank as $key => $value) {
			$newSoftRank[$i]['softRank'] = $key;
			$newSoftRank[$i]['softRank_value'] = $value;
			$i++;
		}
		return $newSoftRank;
	}
	public function _getFormatSoftType(){
		$i = 0;
		$softType = Yii::app()->params['softType'];
		$newSoftType = array();
		foreach ($softType as $key => $value) {
			$newSoftType[$i]['softType'] = $key;
			$newSoftType[$i]['softType_value'] = $value;
			$i++;
		}
		return $newSoftType;
	}

	public function _getFormatAccredit(){
		$i = 0;
		$accredit = Yii::app()->params['accredit'];
		$newAccredit = array();
		foreach ($accredit as $key => $value) {
			$newAccredit[$i]['accredit'] = $key;
			$newAccredit[$i]['accredit_value'] = $value;
			$i++;
		}
		return $newAccredit;
	}

	public function actionDeleteSoft($aid){
		$softInfoModel = SoftInfoModel::model()->getModelByAid($aid);
		$ArticleContentModel = ArticleContentModel::model()->getById($aid);

		if(!$softInfoModel){
			$this->_setErrorFlash('该记录不存在，或已经被');
			return $this->redirect('/admin/DownloadCenter/Index');
		}

		if($ArticleContentModel->articleDelete($aid,$error)){
			if($softInfoModel->deleteSoft($aid)){
				$this->_setSuccessFlash('删除软件成功！');
				return $this->redirect('/admin/DownloadCenter/Index');
			}
			$ArticleContentModel->articleUpdate($aid,array('status'=>1),$error);
			$this->_setErrorFlash('删除软件失败！');
		}else{
			$this->_setErrorFlash('删除软件失败！');
		}

		return $this->redirect('/admin/DownloadCenter/Index');
	}

	/**
	 * 批量删除
	 */
	public function actionMutiDelete(){
		$idArr = Yii::app()->request->getParam('idArr');
		foreach ($idArr as $aid) {
			$softInfoModel = SoftInfoModel::model()->getModelByAid($aid);
			$ArticleContentModel = ArticleContentModel::model()->getById($aid);

			// if(!$softInfoModel){
			// 	$this->ajaxMessage(1,'删除失败,该记录不存在，或已经被删除！');
			// }

			if($ArticleContentModel->articleDelete($aid,$error)){
				if($softInfoModel->deleteSoft($aid)){
					$this->ajaxMessage(0,'删除软件成功！');
				}else{
					$ArticleContentModel->articleUpdate($aid,array('status'=>1),$error);
					$this->ajaxMessage(2,'删除软件失败！');
				}
			}else{
				$this->ajaxMessage(2,'删除软件失败！');
			}
			// $this->ajaxMessage(0,'sssss');
		}
	}

	/**
	 * 批量审核
	 */
	public function actionMutiIsmake(){
		$ids = Yii::app()->request->getParam('idArr');
		$attributes = array('ismake' => 2);
		if(ArticleContentModel::model()->updateByPk($ids, $attributes)){
			$this->ajaxMessage(0,'审核成功！');
		}else{
			$this->ajaxMessage(1,'审核失败');
		}
	}

	/**
	 * 自动获取关键词,并返回（默认为5个）
	 */
	public function actionBuildKeywords(){
		$articleContentModel = new ArticleContentModel();
		$content = Yii::app()->request->getParam('content');

		$wordsArr = $articleContentModel->buildKeywords($content, 5, 'n');
		if($wordsArr){
			$keywords = implode(',',$wordsArr);
			$this->ajaxMessage(0,'',$keywords);
		}else{
			$this->ajaxMessage(1,'');
		}
	}
}
