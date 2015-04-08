<?php

/**
 * Tool
 *
 *     作者: 李晓 (kisa77.lee@gmail.com)
 * 创建时间: 2014-02-10 12:50:06
 * 修改记录:
 *
 * $Id$
 */

class Tool {


    /**
     * formatError
     * 格式化错误信息
     *  * (数组 => 格式化文本)
     *
     * @param  mixed $errorArray
     * @return void
     */
    public static function formatError($errorArray, $attributeLabels = null) {
        $result = array();
        foreach ((array) $errorArray as $attribute => $value) {

            if($attributeLabels){
                $result[] = $attributeLabels[$attribute] . ':';
            }else{
                $result[] = $attribute . ':';
            }
            foreach ($value as $item) {
                $result[] = "&nbsp;&nbsp;" . $item;
            }
        }

        return (string) implode("<br>", $result);
    }

    /**
     * Number of days added to date
     * 天数添加到日期
     * 将单个天数添加到Y-m-d H:i:s格式日期
     * @usage Tool::daysAddToDate('2014-01-18', 6);
     * @return string 成功添加后的日期字符串
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function daysAddToDate($date, $number_day)
    {
        if ( $number_day > 0 ) {
            $should_be_add_date = $number_day * 24 * 60 * 60; //剩余天数转换成时间戳
            $date = strtotime($date) + $should_be_add_date; //剩余天数时间戳加给到期时间
            return date('Y-m-d H:i:s', $date);
        } else {
            $date = strtotime($date);
            return date('Y-m-d H:i:s', $date);
        }
    }

    /**
     * 更新游戏输出版本号文件
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function updateClientVersion($author)
    {
        $hash = md5($author . time());
        $output = '{"version": "' . "$hash" .'"}';
        if (!file_put_contents(Yii::app()->basePath . '/../request/game_version.txt', $output)) {
            $this->_setSuccessFlash('JSON更新失败');
            return $this->redirect(array('/admin/clientGame/new_clientGame'));
        }
    }

    /**
     * 用户上报输出到JSON文件
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function reportOutputJson(
        $uid, $mac, $mode, $server, $game,
        $gameid, $process, $proxy, $ver, $ip, $type, $client)
    {
        $json = "{\"uid\": \"$uid\", \"mac\": \"$mac\", \"mode\": \"$mode\"," .
            "\"server\": \"$server\", \"game\": \"$game\", \"gameid\": \"$gameid\"," .
            "\"process\": \"$process\", \"proxy\": \"$proxy\"," .
            "\"ver\": \"$ver\", \"ip\": \"$ip\", \"Type\": \"$type\", \"client\": \"$client\"}";
        file_put_contents(Yii::app()->basePath . '/../request/report.json', "$json\n", FILE_APPEND);
    }

    /**
     * json_encode2
     * 使用urlencode避免中文被转换成unicode
     *
     * @param  mixed $value
     * @return void
     */
    public static function json_encode2($value) {

        if (is_object($value)) {
            $value = get_object_vars($value);
        }

        $value = self::_urlencode($value);
        $json = json_encode($value);
        return urldecode($json);
    }

    /**
     * _urlencode
     *
     * @param  mixed $value
     * @return void
     */
    public static function _urlencode($value) {

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = self::_urlencode($v);
            }
        } else if (is_string($value)) {
            $value = urlencode(str_replace(array("\r\n", "\r", "\n", "\"", "\/", "\t"),
                                           array('\\n', '\\n', '\\n', '\\"', '\\/', '\\t'),
                                           $value));
        }

        return $value;
    }

    /**
     * json_decode2
     *
     * @param  mixed $json
     * @param  mixed $assoc
     * @param  mixed $depth
     * @return void
     */
    public static function json_decode2($json, $assoc = null, $depth = null) {

        if ($depth !== null) {
            return json_decode($json, $assoc, $depth);
        } else {
            return json_decode($json, $assoc);
        }
    }

    /**
     * 本地系统订单号
     * @author zhanghaolei@lonlife.net
     */
    public static function createOrderNumber(){
        $rand_number = self::_randStr(8);
        return date('Ymd', time()) . "-" . $rand_number . "-" . date('His', time());
    }

    /**
     * 支付宝退款批次号
     * @author zhanghaolei@lonlife.net
     */
    public static function alipayRefundBatchNo(){
        $rand_number = self::_randStr(8);
        return date('Ymd', time()) . $rand_number . date('His', time());
    }

    /**
     * 订单号随机数
     * @param 随机数长度 $len
     */
    private static function _randStr($len) {
        $chars = '0123456789';
        $string = '';
        for(; $len >= 1; $len --) {
            $position = rand () % strlen ( $chars );
            $string .= substr ( $chars, $position, 1 );
        }
        return $string;
    }

    /**
     * createCardPassword
     * 生成剔除易混淆字母,数字的随机密码
     *
     * @param int $number 决定生成的密码位数,默认12位数字,字母组合
     * @author zhanghaolei@lonlife.net
     * @create 2014-03-06 14:58
     */
    public static function createCardPassword($number = 12){
        $chars = array('2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $password = "";
        for ($i = 0; $i < $number; $i++){
            $password .= $chars[rand(0,31)];
        }
        return $password;
    }

    /**
     * 获取客户端ip
     * @author zhanghaolei@lonlife.net
     * @create 2014-03-06 16:40
     */
    public static function getClientIp(){
        $ip = "unknown";
        /*
         * 访问时用localhost访问的，读出来的是“::1”是正常情况。
         * ：：1说明开启了ipv6支持,这是ipv6下的本地回环地址的表示。
         * 使用ip地址访问或者关闭ipv6支持都可以不显示这个。
         * */
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_ip"])) {
                $ip = $_SERVER["HTTP_CLIENT_ip"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_ip')) {
                $ip = getenv('HTTP_CLIENT_ip');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        if(trim($ip) == "::1"){
            $ip="127.0.0.1";
        }
        return $ip;
    }

    /**
     * 二维数组中特定元素第一次出现的位置
     *
     * @param $array 传入的二维数组
     * @param $key 判断第二维数组中存在的哪个key
     * @param $value 判断第二维数组中存在的哪个key的具体值
     * @usage $array = array(
     *           array('a' => 'cccww', 'b' => 'ddd'),
     *           array('a' => 'CCCsss', 'b' => 'DDD'),
     *           array('a' => 'ccc', 'b' => 'QQQ'),
     *           array('a' => 'ccc', 'b' => 'ddQQQ'),
     *       );
     *       $res = Tool::arrayValueFirstPostion($array, 'a', 'ccc');
     *       // return 2
     * @author zhanghaolei@lonlife.net
     * @create 2014-03-17 11:50
     */
    public static function arrayValueFirstPostion($array, $key, $value){
        if(is_array($array)){
            foreach ($array as $k => $v) {
                if(is_array($v)){
                    if(!empty($v[$key])){
                        if($value == $v[$key]){
                            return $k;
                        }
                    }
                }
            }
        }else{
            return false;
        }
    }
    /**
    *   @auto john
    *   time 3-18
    *   短信验证码
    **/
    public static function rand_verifycode($len = 6){
        return  self::_randStr($len);
    }

    /**
     * formatTime
     * 把时间转换为 *天*小时*分*秒的格式
     *
     * @param  mixed $seconds
     * @return void
     */
    public static function formatTime($seconds) {
        $seconds = intval($seconds);
        $d = floor($seconds / 86400);
        $left = $seconds % 86400;
        $h = floor($left / 3600);
        $left = $left % 3600;
        $m = floor($left / 60);
        $s = $left % 60;

        $map = array(
                     '天'   => $d,
                     '小时' => $h,
                     '分'   => $m,
                     //'秒'   => $s,
                     );
        $result = '';
        foreach ($map as $k => $v) {
            if ($v) {
                $result .= $v . $k;
            }
        }
        return $result;
    }

    /**
     * checkCookie
     * 判断cookie是否存在,如果存在,是否为已知的数据
     *
     * @author zhanghaolei@lonlife.net
     * @create 2014-03-25 18:29
     * @return str cookieValue
     */
    public static function checkCookie(){
        $cookieName = Yii::app()->params['cookie_theme_name'];
        $cookieValue = @$_COOKIE[$cookieName];
        $cookieTheme = Yii::app()->params['cookie_theme'];
        if(empty($cookieValue)){
            $random = mt_rand(0, 100);
            if(0 == ($random % 2)){
                $cookieValue = $cookieTheme[0];
            }else{
                $cookieValue = $cookieTheme[2];
            }
            //    $cookieValue = $cookieTheme[2];
            setcookie($cookieName, $cookieValue, strtotime('+ 1 year'), '/');
            return $cookieValue;
        }else{
            if(in_array($cookieValue, $cookieTheme)){
                return $cookieValue;
            }else{
                return false;
            }
        }
    }
    /**
     * 根据汉字得到汉字的首字母
     * @auth john
     * @param  [type] $s0 [description]
     * @return [type]     [description]
     */
    static public function getfirstchar($s0){

        if($s0 >= ord("a") and $s0 <= ord("Z") ){
                return strtoupper($s0{0});
            }
        $s=iconv("UTF-8","gb2312", $s0);
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319 and $asc<=-20284)return "A";
        if($asc>=-20283 and $asc<=-19776)return "B";
        if($asc>=-19775 and $asc<=-19219)return "C";
        if($asc>=-19218 and $asc<=-18711)return "D";
        if($asc>=-18710 and $asc<=-18527)return "E";
        if($asc>=-18526 and $asc<=-18240)return "F";
        if($asc>=-18239 and $asc<=-17923)return "G";
        if($asc>=-17922 and $asc<=-17418)return "I";
        if($asc>=-17417 and $asc<=-16475)return "J";
        if($asc>=-16474 and $asc<=-16213)return "K";
        if($asc>=-16212 and $asc<=-15641)return "L";
        if($asc>=-15640 and $asc<=-15166)return "M";
        if($asc>=-15165 and $asc<=-14923)return "N";
        if($asc>=-14922 and $asc<=-14915)return "O";
        if($asc>=-14914 and $asc<=-14631)return "P";
        if($asc>=-14630 and $asc<=-14150)return "Q";
        if($asc>=-14149 and $asc<=-14091)return "R";
        if($asc>=-14090 and $asc<=-13319)return "S";
        if($asc>=-13318 and $asc<=-12839)return "T";
        if($asc>=-12838 and $asc<=-12557)return "W";
        if($asc>=-12556 and $asc<=-11848)return "X";
        if($asc>=-11847 and $asc<=-11056)return "Y";
        if($asc>=-11055 and $asc<=-10247)return "Z";
        return null;
    }
    /**
     * 二位数组排序 20140324 主题使用
     * @param  [type] $multi_array [要排序的数组]
     * @param  [type] $sort_key    [排序依据的键值]
     * @param  [type] $sort        [排序的升降]
     * @return [array]             [排序好的数组]
     */
    static public function multi_array_sort($multi_array , $sort_key , $sort = SORT_ASC){
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
            $key_array[] = $row_array[$sort_key];
            } else {
            return false;
            }
        }
    }else{
        return false;
    }
    // array_multisort($key_array,$sort,$multi_array);
    return array_multisort($key_array,$sort,$multi_array);
    }
    /**
     *
     *
     */
    static function array_sort($arr,$keys,$type='desc'){
        $keysvalue = $new_array = array();
        foreach ($arr as $k=>$v){
            $keysvalue[$k] = $v[$keys];
        }
        if($type == 'asc'){
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k=>$v){
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
    /**
     * 根据条件查找二位数组的一维数组 20140324主题使用
     * @param  [type] $array [description]
     * @param  [type] $where [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    static public function findArrayWhere($array,$where,$value){
        $count = count($array);
        $return = array();
        for ($i=0; $i < $count; $i++) {
            if ($array[$i]["$where"] == $value) {
                $return[] = $array[$i];
            }
        }
        return $return;
    }
    /**
     * 格式化百分比
     * @author john john_zxw@gmail.com
     * @param  [type] $Numerator   [分子]
     * @param  [type] $Denominator [分母]
     */
    static function formatPercentage($Numerator ,$Denominator){
        if ($Denominator == 0) {
            return '0.0%';
        } else {
            $result = ($Numerator / $Denominator) * 100;
            $res = number_format($result,1,'.','');
            return $res.'%';
        }
    }

    /**
     * buildXML
     *
     * @param  array $data
     * @return void
     */
    public static function buildXML(array $data, $rootNode = 'xml', $charset = 'UTF-8') {
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', $charset);
        $xml->setIndent(true);
        $xml->startElement($rootNode);
        self::_buildXML($xml, '', $data);
        $xml->endElement();

        return $xml->outputMemory();
    }

    /**
     * _buildXML
     *
     * @param  mixed $xmlObj
     * @param  mixed $name
     * @param  mixed $value
     * @return void
     */
    public static function _buildXML($xmlObj, $name, $value) {
        if (is_array($value)) {
            $name = preg_replace('/-_.*_-/i', '', $name);
            $name && $xmlObj->startElement($name);
            foreach ($value as $childK => $childV) {
                self::_buildXML($xmlObj, $childK, $childV);
            }
            $name && $xmlObj->endElement();
        } else {
            $name = preg_replace('/-_.*_-/i', '', $name);
            $xmlObj->startElement($name);
            $xmlObj->text($value);
            $xmlObj->endElement();
        }

        return $xmlObj;
    }

    /**
     * parseXML
     *
     * @param  mixed $strXML
     * @return void
     */
    public static function parseXML($strXML) {

        try {
            $xmlObject = new SimpleXMLElement($strXML);
            $data = self::_parseXML($xmlObject);
        } catch (Exception $e) {
            $data = array();
        }

        return $data;
    }

    /**
     * _parseXML
     *
     * @param  SimpleXMLElement $xml
     * @return void
     */
    public static function _parseXML(SimpleXMLElement $xml) {
        $result = array();
        foreach ($xml->children() as $child) {
            if (count($xml->{$child->getName()}->children())) {
                $result[$child->getName()] = self::_parseXML($xml->{$child->getName()});
            } else {
                $result[$child->getName()] = (string)$child;
            }
        }

        return $result;
    }

    /**
     * writeln
     * 写文件(自动创建目录)
     *
     * @param  mixed $fpath
     * @param  mixed $message
     * @return void
     */
    public static function writeln($fpath, $message) {

        $dir = $fpath;

        // 自动创建不存在的目录
        $directory = dirname($dir);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        @chmod($directory, 0777);

        if (file_exists($dir)) {
            @chmod($dir, 0777);
        }

        $fd = fopen($dir, "w");
        if (!$fd) {
            error_log("Cannot open file ($dir)");
            return false;
        }

        fwrite($fd, $message . PHP_EOL);
        fclose($fd);
    }

    /**
     * diffFileByPath
     *
     * @param  mixed $file1
     * @param  mixed $file2
     * @param  mixed $result
     * @return void
     */
    public static function diffFileByPath($file1, $file2) {
        $result = '';
        @exec("diff {$file1} {$file2}", $result);

        return $result;
    }

    /**
     * secondsToYmdHis
     * 秒转换为x年x月x日x时x分x秒
     *
     * @author zhanghaolei@lonlife.net
     * @create 2014-04-03 20:56
     */
    public static function secondsToYmdHis($seconds, $formateTime = ''){
        if(!is_numeric($seconds) || ($seconds < 0)){
            return false;
        }
        if(0 != $seconds){
            if($seconds < 60){
                $formateTime .= $seconds . '秒';
                $seconds = 0;
            }elseif($seconds >= 60 && $seconds < 60 * 60){
                $formateTime .= floor($seconds / 60) . '分钟';
                $seconds %= 60;
            }elseif($seconds >= 60 * 60 && $seconds < 24 * 60 * 60){
                $formateTime .= floor($seconds / (60 * 60)) . '小时';
                $seconds %= (60 * 60);
            }elseif($seconds >= 24 * 60 * 60 && $seconds < 7 * 24 * 60 * 60){
                $formateTime .= floor($seconds / (24 * 60 * 60)) . '天';
                $seconds %= (24 * 60 * 60);
            }elseif($seconds >= 7 * 24 * 60 * 60 && $seconds < 365 * 24 * 60 * 60){
                $formateTime .= floor($seconds / (7 * 24 * 60 * 60)) . '年';
                $seconds %= (7 * 24 * 60 * 60);
            }
            return self::secondsToYmdHis($seconds, $formateTime);
        }else{
            return $formateTime;
        }
    }

    /**
     * letterAssign
     * 26个大写字母4个一组进行分组
     *
     * @author zhanghaolei@lonlife.net
     * @create 2014-04-09 10:03
     */
    public static function letterAssign(){
        $letterArr = array();
        for($i = 65; $i < 91; $i++){
            $letterArr[] = chr($i);
        }

        $result = array();
        $j = 0;
        while($j < 26){
            $tmp = array_slice($letterArr, $j, 4);
            $result[] = $tmp;
            $j += 4;
        }
        return $result;
    }

    /**
     * debugLog
     * 调试bug,写日志使用此方法
     *
     * @param $file str 文件路径
     * @param $message mix
     * @author zhanghaolei@lonlife.net
     * @create 2014-04-15 15:06
     */
    public static function debugLog($file, $message){
        $message = date('Y-m-d H:i:s', time()) . " " . $message . "\n";
        file_put_contents($file, $message, FILE_APPEND);
    }

    /**
     * setIdAsArrayKey
     *
     * @param  mixed $data
     * @param  string $key
     * @return void
     */
    public static function setIdAsArrayKey($data, $key = 'id') {
        $result = array();
        foreach ($data as $item) {
            $result[$item[$key]] = $item;
        }
        return $result;
    }

    /**
     * 小时卡,剩余秒数显示为x小时x分形式
     *
     * @param $seconds
     * @create 2014-06-04 15:02
     */
    public static function formateHourCard($seconds){
        $seconds = intval($seconds);
        $h = floor($seconds / 3600);
        $left = $seconds % 3600;
        $m = floor($left / 60);
        $s = $left % 60;

        $map = array(
                     '小时' => $h,
                     '分'   => $m,
                     '秒'   => $s,
                     );
        $result = '';
        foreach ($map as $k => $v) {
            if ($v) {
                $result .= $v . $k;
            }
        }
        return $result;
    }

    /**
     * 格式化秒数,后台修改用
     *
     * @create 2014-06-07 18:32
     */
    public static function formateHourCardForAdmin($seconds){
        $seconds = intval($seconds);
        $h = floor($seconds / 3600);
        $left = $seconds % 3600;
        $m = floor($left / 60);
        $s = $left % 60;

        $map = array(
                    'h' => $h,
                    'm' => $m,
                    's' => $s,
                     );
        $result = '';
        foreach ($map as $k => $v) {
            if(('h' == $k) &&(0 == $h)){
                $result .= '00:';
            }

            if(('m' == $k) &&(0 == $m)){
                $result .= '00:';
            }

            if(('s' == $k) &&(0 == $s)){
                $result .= '00';
            }

            if ($v) {
                if(strlen($v) == 1){
                    $v = '0' . $v;
                }
                $result .= $v . ":";
            }
        }
        $result = trim($result, ':');
        return $result;
    }

    /**
     * getDiffData
     * 根据map得到新旧数组变动信息
     *
     * @param  mixed $new
     * @param  mixed $old
     * @param  mixed $map
     * @return void
     */
    public static function getDiffData($new, $old, $map) {
        $diffInfo = array();
        foreach ($map as $f) {
            if (($new[$f] || !is_null($new[$f])) && $new[$f] != $old[$f]) {
                $diffInfo[] = "$f: {$old[$f]} => {$new[$f]}";
            }
        }

        return join("<br> ", $diffInfo);
    }

    /**
     *后台调查表专用
     */
    public static function getGameName($id){
     $game_type = array(
       '1' => '国服',
       '2' => '国际',
       '3' => '美服',
       // '3' => '全部',
        );
     return $game_type[$id];
    }

    public static function getWeekDay($date='',$weekday=1,$format='Y-m-d') {//根据特定日期获取周一周日
        $time = strtotime($date);
        $time = ($time=='') ? time() : $time;
        return date($format, $time-86400*(date('N',$time)-$weekday));
    }

    public static function getMonthDay($date,$type){//根据特定日期获取月初月末 TYPE参数为1则为月初
        if ($type==1){
            $unix = strtotime($date);
            $date1 = date('Y-m',$unix);
            $unix1 = strtotime($date1);
            return date('Y-m-d',$unix1);
        }else{
            return $end_date = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($date))+1, 00));
        }
    }

    /**
     * getFirstCharter
     * 获取中文拼音首字母
     *
     * @param  mixed $str
     * @return void
     */
    public static function getFirstCharter($str) {
        if(!$str){
            return '';
        }
        $fchar = ord($str{0});
        if($fchar>=ord('A') && $fchar<=ord('z') ){
            return strtoupper($str{0});
        }
        $s1 = iconv('UTF-8','gb2312',$str);
        $s2 = iconv('gb2312','UTF-8',$s1);
        $s = $s2 == $str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return null;
    }
    /*
     * 获取IP所在地
     * http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=1.192.56.172
     * http://ip.taobao.com/service/getIpInfo.php?ip=1.192.56.172
     *
     * @param $ip
     * @param $sina bool default to true search from sina and false search from taobao
     * @author zhanghaolei@lonlife.net
     * @create 2014-06-25 10:32
     */
    public static function whereTheIpIs($ip, $sina = true){
        // sleep(1);
        usleep(150000);
        $tail = '';
        $isIpHasPortArr = explode(':', $ip);
        if($isIpHasPortArr[1]){
            $tail = ':' . $isIpHasPortArr[1];
            $ip = $isIpHasPortArr[0];
        }
        if($sina){
            $sinaApi = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
            $jsonInfo = file_get_contents($sinaApi . $ip);
            $jsonInfoArr = json_decode($jsonInfo, TRUE);
            $municipality = array('北京', '上海', '天津', '重庆');
            $country = $jsonInfoArr['country'];
            $province = $jsonInfoArr['province'];
            $city = $jsonInfoArr['city'];
            $isp = $jsonInfoArr['isp'];
            if(in_array($province, $municipality)){
                $return = $province . '市' . $isp;
            }elseif(!$country && !$province && !$city && !$isp){
                $return = '保留地址';
            }else{
                $return = $province . '省' . $city . '市' . $isp;
            }
        }else{
            $taobaoApi = 'http://ip.taobao.com/service/getIpInfo.php?ip=';
            $jsonInfo = file_get_contents($taobaoApi . $ip);
            $jsonInfoArr = json_decode($jsonInfo, TRUE);
            $municipality = array('北京市', '上海市', '天津市', '重庆市');
            if(0 == $jsonInfoArr['code']){
                if('cn' == strtolower($jsonInfoArr['data']['country_id'])){
                    $common = $jsonInfoArr['data']['city'] . $jsonInfoArr['data']['county'] . $jsonInfoArr['data']['isp'];
                    if(in_array($jsonInfoArr['data']['region'], $municipality)){
                        $return = $common;
                    }else{
                        $return = $jsonInfoArr['data']['region'] . $common;
                    }
                }else{
                    $return = $jsonInfoArr['data']['country'];
                }
            }else{
                $return = '';
            }
        }
        return '<a href="http://www.ip138.com/ips138.asp?ip=' . $ip . '&action=2" target="_blank">' . $ip . '</a>' . $tail . '<br />' . strval($return);
    }

    public static function linkToIp138($ip){
        if(strval($ip)){
            return '<a href="http://www.ip138.com/ips138.asp?ip=' . $ip . '&action=2" target="_blank">' . $ip . '</a>';
        }
    }

    /**
     * 老客户端根据后台存储的游戏类型 得到游戏名字
     * @param  [type] $game [description]
     * @return [type]       [description]
     */
     public static function getgamename_ ($game){
        $game_array = array(
            '1' => '暗黑破坏神三',
            '2' => '英雄联盟',
            '3' => '战争雷霆',
            '4' => '激战二',
            '5' => '魔兽世界',
            );
        if ($game_array[$game]) {
            return $game_array[$game];
        } else {
            return $game;
        }
    }
    /**
     * 得到满意度
     * @param  [type] $tmp [description]
     * @return [type]      [description]
     */
    public static function getmanyi ($tmp){
        $result = array(
            '0' => '满意',
            '1' => '一般',
            '2' => '不满意',
            );
        return $result[$tmp];
    }
        /**
     * 得到满意度
     * @param  [type] $tmp [description]
     * @return [type]      [description]
     */
    public static function getmanyis ($tmp){
        $result = array(
            '1' => '满意',
            '2' => '一般',
            '3' => '不满意',
            );
        return $result[$tmp];
    }
    /**
     * 得到推荐值
     * @param  [type] $tuijian [description]
     * @return [type]          [description]
     */
    static public function gettuijian($tuijian){
        $result = array(
            '0' => '会',
            '1' => '可能会',
            '2' => '不会',
            );
        return $result[$tuijian];
    }
    /**
     * 得到推荐值
     * @param  [type] $tuijian [description]
     * @return [type]          [description]
     */
    static public function gettuijians($tuijian){
        $result = array(
            '2' => '会',
            '3' => '可能会',
            '1' => '不会',
            );
        return $result[$tuijian];
    }
    /**
     *
     * @param  [type] $game_type [description]
     * @return [type]          [description]
     */
    static public function getAreaByGametype($game_type){
        $array = array(
            '1' => '台服',
            '2' => '美服',
            '3' => '俄服',
            '4' => '欧服',
            '5' => '韩服',
            );
        if ($array[$game_type]) {
            return $array[$game_type];
        } else {
            return $game_type;
        }
    }
    /**
    * john
    *
    */
    static public  function formatData($data){
        $array = explode('/', $data);
        return $array[0].'<br>'.$array[1];
    }

    static public function u2b($instr) {
        $fp = fopen( APP_PATH .'/protected/vendors/convert/unicode-big5.tab', 'r' );
        $len = strlen($instr);
        $outstr = '';
        for( $i = $x = 0 ; $i < $len ; $i++ ) {
            $b1 = ord($instr[$i]);
            if( $b1 < 0x80 ) {
                $outstr[$x++] = chr($b1);
//                printf( "[%02X]", $b1);
            }
            elseif( $b1 >= 224 ) {	# 3 bytes UTF-8
                $b1 -= 224;
                $b2 = ord($instr[$i+1]) - 128;
                $b3 = ord($instr[$i+2]) - 128;
                $i += 2;
                $uc = $b1 * 4096 + $b2 * 64 + $b3 ;
                fseek( $fp, $uc * 2 );
                $bg = fread( $fp, 2 );
                $outstr[$x++] = $bg[0];
                $outstr[$x++] = $bg[1];
//                printf( "[%02X%02X]", ord($bg[0]), ord($bg[1]));
            }
            elseif( $b1 >= 192 ) {	# 2 bytes UTF-8
                printf( "[%02X%02X]", $b1, ord($instr[$i+1]) );
                $b1 -= 192;
                $b2 = ord($instr[$i]) - 128;
                $i++;
                $uc = $b1 * 64 + $b2 ;
                fseek( $fp, $uc * 2 );
                $bg = fread( $fp, 2 );
                $outstr[$x++] = $bg[0];
                $outstr[$x++] = $bg[1];
//                printf( "[%02X%02X]", ord($bg[0]), ord($bg[1]));
            }
        }
        fclose($fp);
        if( $instr != '' ) {
//            echo '##' . $instr . " becomes " . join( '', $outstr) . "<br>\n";
            return join( '', $outstr);
        }
    }
    /**
     * convert2Array
     * 对象数组 => 属性数组
     *
     * @param  mixed $objects
     * @return void
     */
    public static function convert2Array($objects) {
            if (!is_array($objects) || !is_object(current($objects))) {
                    return $objects;
            }

            $result = array();
            foreach ($objects as $obj) {
                    $result[] = $obj->attributes;
            }

            return $result;
    }

}
