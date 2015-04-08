<?php
/**
* @file FeedbackController.php
* @ 评论管理控制器
* @date 2015-02-05
*/
class FeedbackController extends AdminBaseController{
	/**
	* @file FeedbackController.php
	* @ 评论列表
	* @date 2015-02-05
	*/
	public function actionList(){
		$model = new FeedbackModel('search');
		$model->unsetAttributes();
		//var_dump($model);die;
		$ischeck = array(
			array(
				'isCheck' =>0,
				'ischeck_status' => '未审核'
			),
			array(
				'isCheck' => 1,
				'ischeck_status' => '已审核'
			),
		);

		if(isset($_GET['FeedbackModel'])){
			$model -> attributes = $_GET['FeedbackModel'];
		}
		$this->render('list', array(
			'model' => $model,
			'ischeck' => $ischeck,
		));
	}

	/**
	* @file FeedbackController.php
	* @ 删除评论
	* @date 2015-02-06
	*/
	public function actionDelete(){
		$feedbackModel = new FeedbackModel;
		if(Yii::app()->request->isAjaxRequest){
			$idArr = Yii::app()->request->getParam('idArr');
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $idArr);
			if(FeedbackModel::model()->deleteAll($criteria)){
				echo true;
			}else{
				echo false;
			}
		}else{
			$id = Yii::app()->request->getParam('id');
			if($feedbackModel->feedbackDelete($id, $error)){
				$this->_setSuccessFlash('评论删除成功');
			}else{
				$this->_setErrorFlash($error);
			}
			$this->redirect(array('/admin/Feedback/List'));
		}
	}

	/**
	* @file FeedbackController.php
	* @ 审核评论   ajax
	* @date 2015-02-06
	*/
	public function actionFeedbackIscheck(){
		$idArr = Yii::app()->request->getParam('idArr');
		$attributes = array('isCheck' => 1);
		if(FeedbackModel::model()->updateByPk($idArr, $attributes)){
			echo true;
		}else{
			echo false;
		}
	}

	public function actionReply(){
		$id = Yii::app()->request->getParam('id');
		$feedbackModel = FeedbackModel::model()->findByPk($id);
		$aid = $feedbackModel->aid;
		$articleContentModel = ArticleContentModel::model()->getById($aid);
		if(Yii::app()->request->isPostRequest){
			$reply = trim($_POST['reply']);
			if($reply){
				$reply = '<font color="red">管理员回复：'.$reply.'</font>';
				$oldMsg = $feedbackModel->msg;
				$msg = $oldMsg.'<br>'.$reply;
				$feedbackModel->msg = $msg;
				$feedbackModel->isCheck = 1;
				if($feedbackModel->save()){
					$this->_setSuccessFlash('回复评论成功！');
					return $this->redirect('/admin/Feedback/List');
				}else{
					$this->_setErrorFlash('回复评论失败！');
				}
			}else{
				$this->_setErrorFlash('回复内容不能为空！');
			}
		}
		$this->render('reply',array(
			'model' => $feedbackModel,
			'articleTitle' => $articleContentModel->title,
		));
	}

}