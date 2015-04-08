<?php
class NewsCenterController extends AdminBaseController{
	public function actionNews(){
		$this->render('news');
	}

	public function actionGongGao(){
		$this->render('gonggao');
	}

	public function actionZiYuan(){
		$this->render('ziyuan');
	}
}

?>