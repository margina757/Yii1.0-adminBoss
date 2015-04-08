<?php

class ArticleContentController extends AdminBaseController
{
	/**
	 * @file  ArticleContentController.php
	 * @ 添加文章
	 * @date 2015-1-27
	 */
	public function actionAddArticle()
	{
		$model = new ArticleContentModel;
		$channelModel = new ArticleChannelModel;
		$digitalResourcesModel = new DigitalResourcesModel();
		$channelModel -> getMenuAllArray();
		$channelList = ArticleChannelModel::$ListArr;
		unset($channelList[0]);
		$allowedPic = Yii::app()->params['allowedPic'];
		$resChannels = $channelModel->listResChannel();
		$resourceIds = array();
		foreach ($resChannels as $row) {
			array_push($resourceIds, $row['id']);
		}

		if(Yii::app()->request->isPostRequest){
			
			$data = Yii::app()->request->getParam('ArticleContentModel');
			//资源链接信息
			$digitalData = Yii::app()->request->getParam('DigitalResourcesModel');

			if(empty($data['cid'])){
				$this->_setErrorFlash('请选择文章所属栏目！');
				$this->redirect(Yii::app()->request->urlReferrer);
			}

			if(!$data['created']){
				$data['created'] = date('Y-m-d H:i:s');
			}
			if($data['flag']){
				$data['flag'] = implode(',',$data['flag']);
			}
			if(!$data['writer']){
				$data['writer'] = Yii::app()->user->name;
			}

			$file = CUploadedFile::getInstance($model, 'litpic');
			if(is_object($file) && get_class($file) === 'CUploadedFile'){
				$ext = $file->getExtensionName();
				if(!in_array('.'.$ext, $allowedPic)){
					$this->_setErrorFlash('添加文章信息失败，不允许图片类型！');
					return $this->redirect('/admin/ArticleContent/addArticle');
				}

				$filePath = $model->litpicUpload($file);
				if($filePath !== false){
					$data['litpic']=$filePath;
				}
			}
			$aid = $model->add($data, $error);
			if($aid){
				$digitalData['aid'] = $aid;
				$digitalData['created'] = $data['created'];
				if(in_array($data['cid'],$resourceIds)){
					$digitalData['cid'] = $data['cid'];
					if(!$digitalResourcesModel->add($digitalData,$errors)){
						$this->_setErrorFlash('文章添加失败！');
					}
					$this->_setSuccessFlash('文章添加成功！');
				}else{
					$this->_setSuccessFlash('文章添加成功！');
				}
				$this->redirect(Yii::app()->request->urlReferrer);
			}	
		}

		$this->render('article_add',array(
			'model' => $model,
			'channelList' => $channelList,
			'digitalResourcesModel' => $digitalResourcesModel,
			'resourceIds' => json_encode($resourceIds),
		));
	}

	/**
	 * 更新文章
	 */
	public function actionUpdateArticle()
	{
		$articleModel = new ArticleContentModel;
		$aid = Yii::app()->request->getParam('id');
		$model = $articleModel->getById($aid);
		$channelModel = new ArticleChannelModel;
		$channelModel -> getMenuAllArray(0,'',false);
		$channelList = ArticleChannelModel::$ListArr;
		unset($channelList[0]);
		$oldLitpic = $model->litpic;
		$allowedPic = Yii::app()->params['allowedPic'];
		$flag = explode(',',$model->flag);
		$digitalResourcesModel = DigitalResourcesModel::model()->getByAid($aid);

		$resChannels = $channelModel->listResChannel();
		$resourceIds = array();
		foreach ($resChannels as $row) {
			array_push($resourceIds, $row['id']);
		}

		if(Yii::app()->request->isPostRequest){
			$data=Yii::app()->request->getParam('ArticleContentModel');
			$digitalData = Yii::app()->request->getParam('DigitalResourcesModel');

			if(empty($data['cid'])){
				$this->_setErrorFlash('请选择文章所属栏目！');
				$this->redirect(Yii::app()->request->urlReferrer);
			}
			if(!$data['created']){
				$data['created'] = date('Y-m-d H:i:s');
			}
			if($data['flag']){
				$data['flag'] = implode(',',$data['flag']);
			}
			if(!$data['writer']){
				$data['writer'] = Yii::app()->user->name;
			}
			$data['updated'] = date('Y-m-d H:i:s');

			$file = CUploadedFile::getInstance($model, 'litpic');
			if(is_object($file) && get_class($file) === 'CUploadedFile'){
				$ext = $file->getExtensionName();
				if(!in_array('.'.$ext, $allowedPic)){
					$this->_setErrorFlash('修改文章信息失败，不允许图片类型！');
					return $this->redirect(Yii::app()->request->getUrl());
				}

				$filePath = $articleModel->litpicUpload($file);
				if($filePath !== false){
					$data['litpic'] = $filePath;
				}
			}else{
				$data['litpic'] = $oldLitpic;
			}

			if($articleModel->articleUpdate($aid, $data, $error)){
				$digitalData['aid'] = $aid;
				$digitalData['updated'] = date('Y-m-d H:i:s');
				if(in_array($data['cid'],$resourceIds)){
					$digitalData['cid'] = $data['cid'];
					$digitalData['status'] = 1;
					if($digitalResourcesModel){
						if(!$digitalResourcesModel->updateByAid($aid,$digitalData,$errors)){
							$this->_setErrorFlash('文章修改失败！');
						}
						$this->_setSuccessFlash('文章修改成功！');
					}else{
						$digitalData['created'] = $data['created'];
						if(!DigitalResourcesModel::model()->add($digitalData,$errors)){
							$this->_setErrorFlash('文章修改失败！');
						}
						$this->_setSuccessFlash('文章修改成功！');
					}
				}else{
					if($digitalResourcesModel){
						$digitalResourcesModel->deleteByAid($aid,$errors);
					}
					$this->_setSuccessFlash('文章修改成功！');
				}
				$this->redirect(array('/admin/ArticleContent/Index'));
			}else{
				$this->_setErrorFlash($error);
				$this->redirect(array('/admin/ArticleContent/Index'));
			}
		}

		$this->render('article_update',array(
			'model'=>$model,
			'channelList' => $channelList,
			'flag' => $flag,
			'digitalResourcesModel' => $digitalResourcesModel,
		));
	}

	/**
	 * @file ArticleContentController.php
	 * @ 删除文章  
	 * @date 2015-02-13
	 */
	public function actionDelete()
	{
		$aid = Yii::app()->request->getParam('id');
		$result = ArticleContentModel::model()->articleDelete($aid, $error);
		$digitalResourcesModel = DigitalResourcesModel::model()->getByAid($aid);
		if($result){
			if($digitalResourcesModel){
				$digitalResourcesModel->deleteByAid($aid,$errors);
			}
			$this->_setSuccessFlash('文章删除成功！');
		}
		else $this->_setErrorFlash($error);
		$this->redirect(array('/admin/ArticleContent/Index'));
	}

	/**
	* @file ArticleContentController.php
	* @ 批量删除文章  Ajax
	* @date 2015-02-12
	*/
	public function actionBatchDelete(){
		$idArr = Yii::app()->request->getParam('idArr');
		$result = ArticleContentModel::model()->articleDelete($idArr, $error);
		if($result){
			DigitalResourcesModel::model()->batchDelete($idArr,$errors);
			$this->ajaxMessage(1,'文章删除成功！');
		}
		else $this->ajaxMessage(0,'文章删除失败！');
	}

	/**
	 * 文章列表
	 */
	public function actionIndex()
	{
		/*$dataProvider=new CActiveDataProvider('ArticleContentModel');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		/*$criteria = new CDbCriteria();
		$criteria -> order = 'aid desc';
		$criteria -> addCondition('stauts=1');      //根据条件查询
		$criteria -> select = 'aid, title, flag, ismake, litpic, body, click, created';
		$criteria -> with = 'name';

		$count = ArticleContentModel::model()->count($criteria);
		$pager = new CPagination($count);
		$pager -> pageSize=25;
		$pager -> applyLimit($criteria); 
		$list = ArticleContentModel::model()->findAll($criteria);
		
		$this -> render('index',array(
			'list' => $list,
            'pager' => $pager,
		));*/

		$model = new ArticleContentModel('search');
		$channelModel = new ArticleChannelModel;
		$channelModel -> getMenuAllArray();
		$channelList = ArticleChannelModel::$ListArr;
		unset($channelList[0]);
		$i=0;
		foreach($channelList as $key=>$channel){
			$channelData[$i]['id'] = $key;
			$channelData[$i]['name'] = $channel;
			$i++;
		}
		$model->unsetAttributes();
		$ismake = array(
			array(
				'ismake' => 1,
				'status_mark' => '未审核'
			),
			array(
				'ismake' => 2,
				'status_mark' => '已审核'	
			)
		);

		if(isset($_GET['ArticleContentModel'])){
			$model->attributes = $_GET['ArticleContentModel'];
		}
		
		$this->render('index',array(
			'model' => $model,
			'ismake' => $ismake,
			'channelList' => $channelData,
		));
	}

	/**
	* @file ArticleContentController.php
	* @ 批量审核文章   Ajax
	* @date 2015-02-04
	*/
	public function actionArticleIsmake(){
		$idArr = Yii::app()->request->getParam('idArr');
		$attributes = array('ismake' => 2);
		if(ArticleContentModel::model()->articleBatchCheck($idArr))
		$this->ajaxMessage(1,'文章审核成功！');
		else $this->ajaxMessage(0,'文章审核成功！');
	}

	public function actionTest(){
		echo "<pre>";
		$aid = 1;
		$DigitalResourcesModel = new DigitalResourcesModel();
		echo $DigitalResourcesModel->updateByAid($aid,array('url'=>'http://www.lonlife.cn'),$error);
	}
}
