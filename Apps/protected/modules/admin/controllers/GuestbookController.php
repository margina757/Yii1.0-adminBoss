<?php
/**
* @ 后台留言板控制器
* @date 2015-02-11
**/
class GuestbookController extends AdminBaseController{
	/**
	* @file GuestbookController.php
	* @ 留言列表
	* @date 2015-02-11
	*/
	public function actionList(){
		$model = new GuestbookModel('search');
		$model->unsetAttributes();
		if(isset($_GET['GuestbookModel'])){
			$model->attributes = $_GET['GuestbookModel'];
		}
		$isCheck = array(
			array(
				'status' => 0,
				'status_check' => '已审核'
			),
			array(
				'status' => 1,
				'status_check' => '未审核'
			)
		);

		$data = $model->search();
		$this->render('list', array(
			'model' => $model,
			'data' => $data,
			'isCheck' => $isCheck,
		));
	}

	/**
	* @file GuestbookController.php
	* @ 回复留言
	* @date 2015-02-12
	*/
	public function actionReplay(){
		$id = Yii::app()->request->getParam('id');
		$model = GuestbookModel::model()->getDataByPk($id, $error);
		$oldMsg = $model->msg;
		if($model){
			if(Yii::app()->request->isPostRequest){
				$data['posttime'] = time();
				$data['ischeck'] = 0;
				$data['msg'] = $oldMsg.'<div><font color="red">'.'管理员回复：'.$_POST['replay'].'</font></div>';
				if($model->replayGuestbook($id, $data, $error))
				$this->_setSuccessFlash('管理员回复留言成功！');
				else $this->_setErrorFlash($error);
				$this->redirect(array('/admin/Guestbook/List'));
			}else{
				$this->render('replay', array(
					'model' => $model,
				));
			}
		}else{
			$this->_setErrorFlash('不存在此记录！');
			$this->redirect(array('/admin/Guestbook/List'));
		}
	}

	/**
	* @file GuestbookController.php
	* @ 删除留言
	* @date 2015-02-12
	*/ 
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
		$result = GuestbookModel::model()->deleteGuestbook($id, $error);
		if($result) $this->_setSuccessFlash('留言删除成功！');
		else $this->_setErrorFlash($error);
		$this->redirect(array('/admin/Guestbook/List'));
	}

	/**
	 * @file GuestbookController.php
	 * @ 批量审核留言
	 * @date 2015-03-05  11:25:29
	 */
	public function actionMutiIsCheck(){
		$ids = YIi::app()->request->getParam('idArr');
		
		$attributes = array('ischeck' => 0);
		if(GuestbookModel::model()->updateByPk($ids,$attributes)){
			$this->ajaxMessage(0,'审核成功！');
		}else{
			$this->ajaxMessage(1,'审核失败！');
		}
	}

	/**
	 * @file GuestbookController.php
	 * @ 批量删除留言
	 * @date 2015-03-05  11:29:29
	 */
	public function actionMutiDelete(){
		$ids = YIi::app()->request->getParam('idArr');
		if(GuestbookModel::model()->deleteByPk($ids)){
			$this->ajaxMessage(0,'删除留言成功！');
		}else{
			$this->ajaxMessage(2,'删除留言失败！');
		}
	}

}


?>