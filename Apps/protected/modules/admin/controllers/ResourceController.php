<?php
/**
 *  资源文章排序管理
 *  @author  jiangkun  <jackun@lonlife.cn> 
 */
class ResourceController extends AdminBaseController{
	
	/**
	 * 列出某一类资源
	 */
	public function actionIndex(){
		$ResourceArr = array(
			'24' => '中文资源',
			'25' => '外文资源',
			'26' => '其他资源',
			'27' => '试用资源',
		);

		$ArticleContentModel = new ArticleContentModel('search');
		$ArticleContentModel->unsetAttributes();
		if(isset($_GET['ArticleContentModel'])){
			$ArticleContentModel->attributes = $_GET['ArticleContentModel'];
		}

		$this->render('index',array(
			'model' => $ArticleContentModel,
			'ResourceArr' => $ResourceArr,
		));

	}

	/**
	 * 更新资源文章权重
	 */
	public function actionUpdateSort(){
		$ArticleContentModel = new ArticleContentModel();
		if(Yii::app()->request->isAjaxRequest){
			$aids  = Yii::app()->request->getParam('aids');
			$sort = Yii::app()->request->getParam('sort');
			if($ArticleContentModel->updateSort($aids,$sort)){
				return $this->ajaxMessage(0,'更新文章权重成功');
			}else{
				return $this->ajaxMessage(1,'更新文章权重失败');
			}
		}
	}

}