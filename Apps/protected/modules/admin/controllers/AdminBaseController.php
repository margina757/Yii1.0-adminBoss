<?php
/**
 * AdminBaseController
 *
 *     作者: 李晓 (kisa77.lee@gmail.com)
 * 创建时间: 2014-02-10 09:40:12
 * 修改记录:
 *
 * $Id$
 */
class AdminBaseController extends AdminController {
    /**
     * 在动作执行前验证是否通行
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    protected function beforeAction($action)
    {
        $userInfo = AdminUserModel::model()->getAdminUserId(Yii::app()->user->uid);
        if ( $userInfo->weak == 0 && $this->getAction()->id != 'repassword') {
            $this->_setErrorFlash('系统检测到您的密码为弱密码，请修改后再进行操作');
            return $this->redirect(array('/admin/auth/repassword'));
        } elseif( $this->verifyAuth() ){
            return true;
        } else {
            return $this->errorTip('访问未授权: ' . $this->getId() . '/' . $this->getAction()->id);
        }
    }

    /**
     * 根据资源授权表数据验证授权
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    protected function verifyAuth()
    {
        // 超级管理员不验证权限
        if (Yii::app()->user->id == 1 || Yii::app()->user->id == 3) {
            return true;
        }
        $actionId     = strtolower(self::getAction()->id);
        $controllerId = strtolower(self::getId());
        if(substr($controllerId,0,3)=='api') return true;
        $condition = 'status=:status AND uid = :uid';
        $params = array(
                        ':status' => 1,
                        ':uid' => Yii::app()->user->role,
                        );
        $resources_auth = ResourcesAuthModel::model()->listAllResourceAuth($condition, $params);
        foreach ($resources_auth as $auth) {
            if ( $auth['uid'] == Yii::app()->user->role &&
                $auth['controller'] == $controllerId &&
                $auth['action'] == $actionId ) {
                return true;
            }
        }
    }

    /**
     * 验证当前用户控制器与动作授权
     * 修改验证算法,修正效率问题
     *
     * @return boolean
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    protected function verifyDisplay(&$actionList) {
        // 管理员不验证
        if (Yii::app()->user->id == 1 || Yii::app()->user->id == 3) {
            foreach ($actionList as $k => $v) {
                if (!is_array($v) || !array_key_exists('visible', $v) || !is_array($v['visible'])) {
                    continue;
                }
                $actionList[$k]['visible'] = true;
            }
            return $actionList;
        }

        $condition = 'status = :status AND uid = :uid';
        $params = array(
                        ':status' => 1 ,
                        ':uid' => Yii::app()->user->role,
                        );
        $resourceAuthInfo = ResourcesAuthModel::model()->listAllResourceAuth($condition, $params);
        foreach ($actionList as $k => $v) {
            if (!is_array($v) || !array_key_exists('visible', $v) || !is_array($v['visible'])) {
                continue;
            }
            $controller = $action = '';
            $controller = $v['visible'][0];
            $action = $v['visible'][1];
            $actionList[$k]['visible'] = false;
            foreach ($resourceAuthInfo as $auth) {
                if ( $auth['uid'] == Yii::app()->user->role && $auth['controller'] == strtolower($controller) &&
                     $auth['action'] == strtolower($action) ) {

                    $actionList[$k]['visible'] = true;
                    break;
                }
            }

        }

        return $actionList;
    }
              
    /**
     * 验证当前用户控制器与动作授权
     *
     * @param $uid
     * @param $controller
     * @param $action
     * @return boolean
     * @author margina757@gmail.om
     * @create 2014-03-24 14:45
     */
    protected function verifyAuthority($uid){
        $resourcesAuth = ResourcesAuthModel::model()->listAllResourceAuth(' status = 1 and uid = :uid ',
            array('uid' => $uid ));
        $returnData = array();
        foreach ($resourcesAuth as $key => $value) {
            $controller = $value['controller'];
            $action = $value['action'];
            $returnData[] = $controller . '-' . $action;
        }
        return $returnData;
    }

    public function errorTip($msg, $url = '') {
        return $this->render('/index/error', array(
                                                   'message' => $msg,
                                                   ));
    }

    public function init() {
    }
    /**
     * _popSuccessFlash
     * 弹出一条flash成功信息
     *
     * @return void
     */
    protected function _popSuccessFlash() {

        $result = '';
        if (Yii::app()->session['success_flash']) {
            $result = Yii::app()->session['success_flash'];
            unset(Yii::app()->session['success_flash']);
        }

        return $result;
    }

    /**
     * _popErrorFlash
     * 弹出一条flash警告信息
     *
     * @return void
     */
    protected function _popErrorFlash() {

        $result = '';
        if (Yii::app()->session['error_flash']) {
            $result = Yii::app()->session['error_flash'];
            unset(Yii::app()->session['error_flash']);
        }

        return $result;
    }

    /**
     * _setSuccessFlash
     * 设置flash消息
     *
     * @param  mixed $msg
     * @return void
     */
    protected function _setSuccessFlash($msg) {
        return Yii::app()->session['success_flash'] = $msg;
    }

    /**
     * _setErrorFlash
     * 设置flash消息
     *
     * @param  mixed $msg
     * @return void
     */
    protected function _setErrorFlash($msg) {
        return Yii::app()->session['error_flash'] = $msg;
    }


    /**
     * popActiveItems
     * 把当前选中的功能组放在最上面
     *
     * @return void
     */
    protected function popActiveItems($items) {
        // 当前选中功能放在menubar顶部
        $popGroup = $tmpArrary = array();
        $activeIndex = 0;
        $index = 1;
        foreach ($items as $k => $v) {
            if (is_string($v)) {
                $popGroup ? $tmpArrary[$index] = $popGroup : '';
                $index++;
                $popGroup = array();
            } else {
                $popGroup[$k] = $v;
            }
            // 找到active功能组
            if (is_array($v) && array_key_exists('active', $v) && $v['active']) {
                $activeIndex = $index;
            }
            // 末尾数组没有写进tmpArrary的问题
            if ($k == count($items)-1) {
                $popGroup ? $tmpArrary[$index] = $popGroup : '';
            }
        }
        $tmpArrary[0] = $tmpArrary[$activeIndex];
        unset($tmpArrary[$activeIndex]);
        ksort($tmpArrary);
        $result = array();
        foreach ($tmpArrary as $value) {
            if ($result) {
                $result[] = TbHtml::menuDivider();
            }
            foreach ($value as $v) {
                $result[] = $v;
            }
        }

        return $result;
    }


    /**
     * ajaxMessage
     *
     * @param  mixed $code  0:正常 大于0 都是非正常
     * @param  mixed $msg
     * @param  array $result
     * @return void
     */
    public function ajaxMessage($code = 0, $msg = 'success', $result = array()) {

        // todo 跨域检测

        echo json_encode(array(
                               'code' => $code,
                               'msg' => $msg,
                               'result' => $result
                               ));
    }

    /**
     * addVariable
     *
     * @author margina757@gmail.om
     */
    public function addVariable($data, &$errors) {

        $variableModel = new VariableModel();
        $variableModel->attributes = $data;
        if ($variableModel->save()) {
            // TODO cache
            return true;
        }

        $errors = $variableModel->getErrors();
        return false;
    }

    /**
     * variableGet
     *
     * @param $name
     * @author margina757@gmail.om
     */
    public function variableGet($name){
        // todo cache
        $criteria = new CDbCriteria;
        $criteria->condition = 'name = :name';
        $criteria->params = array(':name' => $name);

        $result = VariableModel::model()->find($criteria);

        if ($result) {
            // return $result->attributes;
            $value = $result->attributes;
            $jsonData = $value['value'];
            $arrData = json_decode($jsonData, TRUE);
            return $arrData;
        }

        return array();
    }

    /**
     * variableSet
     *
     * @author margina757@gmail.om
     * @create 2014-03-06 17:17
     */
    public function variableSet($name, $value){

        $variableModel = VariableModel::model()->findByAttributes(array('name' => $name));
        // 去除yii附加的yt0参数
        // if(isset($value['yt0'])) unset($value['yt0']);
        $value = Tool::json_encode2($value);
        if(!$variableModel){
            $data = array();
            $data = array(
                'name' => $name,
                'value' => $value,
            );
            return $this->addVariable($data, $error);
        }else{
            $variableModel->value = $value;
            if($variableModel->save()){
                return true;
            }else{
                return false;
            }
        }
    }

    
    /**
     * 批量设置 
     * variable SET
     * param array  $key=>$value (variable : name=>value)
     * return boolean 
     */
    protected function batchSet($data,&$errors){
        if(is_array($data)){
            foreach ($data as $key => $value) {
                if(!$this->variableSet($key,$value)){
                    $errors = "保存全局{$key}设置出错";
                    return false;
                }
            }
            return true;
        }
        $errors = '传入参数不是有效数组数据';
        return false;
    }


    /**
     * 批量获取 
     * variable GET
     * param array  $key=>'' (variable : name=>value)
     * return array $key=>$value (variable : name=>value)
     */
    protected function batchGet($data,&$errors){
        if(is_array($data)){
            foreach ($data as $key => $value) {
                $data[$key] = $this->variableGet($key);
            }
            return $data;
        }
        $errors = '传入参数不是有效数组数据';
        return false;
    }
    
    /**
     *根据ip地址获取所属组信息,type指定返回值类型
     *@author bangbang@lonlife.net
     */
     public function getGroupinfo($ipaddress,$type=''){
        $IpDetial = new FwIpDetialModel;
        $criteria = new CDbCriteria;
        $criteria->select='*';
        $criteria->addCondition("host = :host");
        $criteria->params['host'] = $ipaddress;
        $result = $IpDetial->with('group')->find($criteria);
        $group['name'] = $result['group']['name'];
        $group['des'] = $result['group']['des'];
        if($type=='name'){
            return $group['name'];
        }else{
            return $group;
        }
    }

}
