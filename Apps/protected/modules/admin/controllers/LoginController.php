<?php
/**
 * Lonlife后台登录页面
 * @author Howe Isamu <margina757@gmail.com>
 */
class LoginController extends CController
{
    // 指定布局文件取自模块
    public $layout='/layouts/login';

    public function actionIndex()
    {
        $model=new LoginFormModel;
		if(isset($_POST['username'], $_POST['password']))
        {
            $model->username   = $_POST['username'];
            $model->password   = $_POST['password'];
            if($model->validate())
            {
                Yii::app()->session['username']=$_POST['username'];
                $this->redirect('/admin/index');
            }
	 }
        $this->render('index',array('model'=>$model));
    }
  
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('/admin');
    }
}
               
