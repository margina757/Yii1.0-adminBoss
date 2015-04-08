<?php
/**
 * 文章栏目分类
 * @author <jackun@lonlife.net>
 */
class ArticleChannelController extends AdminBaseController{

	//添加栏目分类 
	public function actionChannelAdd(){
		$ArticleChannelModel = new ArticleChannelModel();
		$id = Yii::app()->request->getQuery('id');
		if($id == 0){
			$topid = 0;
			$reid = 0;
		}else{
			$reid = $id;
			$topid = $ArticleChannelModel->getTopidById($id)['topid'];
		}
		
		if(Yii::app()->request->isPostRequest){

			$data = Yii::app()->request->getParam('ArticleChannelModel');
			$data['created'] = date('Y-m-d H:i:s');
			$data['updated'] = date('Y-m-d H:i:s');
			$data['status'] = 1;
			
			if($ArticleChannelModel->addChannel($data,$error)){
				$this->_setSuccessFlash('添加栏目成功！！！');
				return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
			}else{
				$this->_setErrorFlash(Tool::formatError($error));
			}
		}
			
		$this->render('/article_channel/channel_add',array(
			'model' => $ArticleChannelModel,
			'topid' => $topid,
			'reid' => $reid,
		));
	}

	//列出所有栏目分类
	public function actionChannelList(){
		
		$ArticleChannelModel = new ArticleChannelModel();

		$ChannelData = $ArticleChannelModel->getTreeChildrens(0);
		$this->render('/article_channel/channel_list',array(
			'model' => $ArticleChannelModel,
			'channelData' => $ChannelData,
		));
	}

	//删除栏目
	public function actionChannelDelete($id){
		$ArticleChannelModel = new ArticleChannelModel();
		if($ArticleChannelModel->delChannel($id,$error)){
			$this->_setSuccessFlash('删除栏目成功！！！');
			return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
		}else
			$this->_setSuccessFlash('删除栏目失败！！！');

		return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
	}

	//修改栏目
	public function actionChannelUpdate(){

		$ArticleChannelModel = new ArticleChannelModel();
		$ListChannel =  '<option value="empty" selected="selected">请选择栏目分类</option>
		<option value="0" style="color:red;">移动为顶级栏目</option>'.$ArticleChannelModel->getMenuAllList();

		$id = Yii::app()->request->getQuery('id');
		//获取来访路径
		$request_uri = Yii::app()->request->getUrl();

		$channelInfo = $ArticleChannelModel->getChannelModelById($id);
		if(!$channelInfo){
			$this->_setSuccessFlash('该栏目不存在，或已经被删除！！！');
			return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
		}
		$channelAttr = $channelInfo->attributes;

		//需要修改栏目的顶级栏目或顶级栏目的下一级栏目的id,reid,toid
		$channelIds = $ArticleChannelModel->getTopidById($id);
		$ArticleChannelModel::$arrayLevel = array();

		if(Yii::app()->request->isPostRequest){

			$data = Yii::app()->request->getParam('ArticleChannelModel');
			$data['updated'] = date('Y-m-d H:i:s');

			if($data['reid'] !== 'empty') {

				//需要移动到的栏目的顶级栏目或顶级栏目的下一级栏目的id,reid,toid
				$tmpArr = $ArticleChannelModel->getTopidById($data['reid']);
				$tmpInfo = $ArticleChannelModel->getChannelModelById($data['reid'])->attributes;

				if($id === $data['reid']){

					$this->_setErrorFlash('修改失败,不允许将栏目自己移动到自己');
					return $this->redirect(array($request_uri));

				}else if($data['reid'] == 0){

					$topid = 0;

				}else{
					if($channelIds['topid'] == $tmpArr['topid']){

						if($channelIds['reid'] == $tmpArr['reid']){

							if($channelIds['reid'] != 0){

								if($channelAttr['reid'] < $tmpInfo['reid']){
									$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
									return $this->redirect(array($request_uri));
								}
							}
						}else{

							if($channelAttr['id'] == $tmpInfo['reid'] || $channelAttr['id'] == $tmpArr['reid']){
								$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
								return $this->redirect(array($request_uri));
							}
						}

						$topid = $channelIds['topid'];
					}else{

						if($channelIds['reid'] == $tmpArr['reid']){

							if($channelIds['reid'] != 0){

								if($channelAttr['reid'] < $tmpInfo['reid']){
									$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
									return $this->redirect(array($request_uri));
								}
							}
						}else{

							if($channelAttr['id'] == $tmpInfo['reid'] || $channelAttr['id'] == $tmpArr['reid']){
								$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
								return $this->redirect(array($request_uri));
							}
						}

						$topid = $tmpInfo['topid'];
						if($tmpInfo['topid'] == 0){
							$topid = $tmpInfo['id'];
						}
					}
				}
				$data['topid'] = $topid;
			}else{
				unset($data['reid']);
			}

			if($channelInfo->updateChannel($id,$data,$error)){
				$this->_setSuccessFlash('修改栏目成功！！！');
				return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
			}else{
				$this->_setErrorFlash(Tool::formatError($error));
			}
		}

		$this->render('/article_channel/channel_update',array(
			'model' => $channelInfo,
			'listChannel' => $ListChannel,

		));
	}

	//移动栏目
	public function actionChannelMove(){
		$ArticleChannelModel = new ArticleChannelModel();
		$menuList = '<option value="0" style="color:red;">移动为顶级栏目</option>'.$ArticleChannelModel->getMenuAllList();

		//获取需要移动的栏目ID
		$id = Yii::app()->request->getQuery('id');
		//获取来访路径
		$request_uri = Yii::app()->request->getUrl();
		$channelInfo = $ArticleChannelModel->getChannelModelById($id);

		if(!$channelInfo){
			$this->_setErrorFlash('该栏目不存在，或已经被删除！！！');
			return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
		}
		$channelAttr = $channelInfo->attributes;
		//需要修改栏目的顶级栏目或顶级栏目的下一级栏目的id,reid,toid
		$channelIds = $ArticleChannelModel->getTopidById($id);
		$ArticleChannelModel::$arrayLevel = array();

		if(Yii::app()->request->isPostRequest){

			$postData = Yii::app()->request->getParam('ArticleChannelModel');
			//需要移动到的栏目的顶级栏目或顶级栏目的下一级栏目的id,reid,toid
			$tmpArr = $ArticleChannelModel->getTopidById($postData['id']);
			$tmpInfo = $ArticleChannelModel->getChannelModelById($postData['id'])->attributes;

			if($id == $postData['id']){

				$this->_setErrorFlash('修改失败,不允许将栏目自己移动到自己');
				return $this->redirect(array($request_uri));

			}else if($postData['id'] == 0){

				$topid = 0;

			}else{

				if($channelIds['topid'] == $tmpArr['topid']){

					if($channelIds['reid'] == $tmpArr['reid']){

						if($channelIds['reid'] != 0){

							if($channelAttr['reid'] < $tmpInfo['reid']){
								$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
								return $this->redirect(array($request_uri));
							}
						}
					}else{

						if($channelAttr['id'] == $tmpInfo['reid'] || $channelAttr['id'] == $tmpArr['reid']){
							$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
							return $this->redirect(array($request_uri));
						}
					}

					$topid = $channelIds['topid'];
				}else{

					if($channelIds['reid'] == $tmpArr['reid']){

						if($channelIds['reid'] != 0){

							if($channelAttr['reid'] < $tmpInfo['reid']){
								$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
								return $this->redirect(array($request_uri));
							}
						}
					}else{

						if($channelAttr['id'] == $tmpInfo['reid'] || $channelAttr['id'] == $tmpArr['reid']){
							$this->_setErrorFlash('修改失败,不允许从父级移动到子级目录');
							return $this->redirect(array($request_uri));
						}
					}

					$topid = $tmpInfo['topid'];
					if($tmpInfo['topid'] == 0){
						$topid = $tmpInfo['id'];
					}
				}

			}

			$data['reid'] = $postData['id'];
			$data['topid'] = $topid;
			$data['updated'] = date('Y-m-d H:i:s');

			if($ArticleChannelModel->updateChannel($id,$data,$error)){
				$this->_setSuccessFlash('移动栏目成功！！！');
				return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
			}else{
				$this->_setErrorFlash('移动栏目失败！！！');
			}
		}

		$this->render('/article_channel/channel_move',array(
			'model' => $ArticleChannelModel,
			'channelInfo' => $channelAttr,
			'menuList' => $menuList,
		));
	}

	//更新所有栏目权重
	public function actionUpdateAllSort(){
		$ArticleChannelModel = new ArticleChannelModel();

		if(Yii::app()->request->isPostRequest){
			$ids = $_POST['ids'];
			$sorts = $_POST['sorts'];
			$data = array();
			if(count($ids) != count($sorts)){
				$this->_setErrorFlash('请选中所有栏目!');
				return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
			}

			for ($i=0; $i < count($ids); $i++) { 
				$data[$ids[$i]]['sort'] = $sorts[$i];
			}

			if(!$ArticleChannelModel->updateAllSort($data)){
				$this->_setErrorFlash('更新权重失败');
			}else{
				$this->_setSuccessFlash('更新权重成功');
			}

			return $this->redirect(array('/admin/ArticleChannel/ChannelList'));
		}
	}

}
