<?php
/**
 * Lonlife后台父控制器
 * @author Howe Isamu <margina757@gmail.com>
 * 视图部分使用了Yiistrap扩展
 * 语法请查看 @usage http://www.getyiistrap.com/site/basics
 */
class AdminController extends Controller
{
    public $layout='/layouts/dashboard';
    // 添加页面标题 
    // add by zhanghaolei@lonlife.net at 2014-06-10 10:27
    public $title = '管理首页 - ctbss - 后台管理';
    public $titleTail = "玲珑科技 - 后台管理";

    // const FIRE_SET = 1;

    /**
     * 判断是否登录帐号
     * 判断Role返回授权
     * @return array access control rules
     * @author Howe Isamu <margina757@gmail.com>
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
           // 'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        if (!Yii::app()->user->isGuest) {
            if (Yii::app()->user->role==0) {
                return array(
                             array('allow',
                                   //'actions'=>array('index','view','create','update','delete',
                                   //'admin','new','edit', 'listServer'),
                                   'users'=>array('@'),
                                  ),
                             array('deny',
                                   'users'=>array('*'),
                                  ),
                            );
            } else {
                return array(
//                             array('deny',
//                                   'users'=>array('*'),
//                                  ),
                            );
            }
        } else {
            return array(
                array('allow',
                'actions'=>array('error'),
                'users'=>array('*'),
            ),

                array('deny',
                    'users'=>array('*'),
                ),
            );
        }
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}
