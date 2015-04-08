<?php
class IndexController extends AdminBaseController{
	public function actionIndex(){
		$this->render('index');
	}

	public function actionBumen(){
		$this->render('bumen');
	}

	public function actionGuancang(){
		$this->render('guancang');
	}

	public function actionKaifang(){
		$this->render('kaifang');
	}

	public function actionZhidu(){
		$this->render('zhidu');
	}
}