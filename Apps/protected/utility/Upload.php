<?php
/**
 * 类说明: 转移上传图片,不更改图片大小,只是转移位置
 *
 * @author zhanghaolei@lonlife.net
 * @param $name 原图片名称
 * @param $type 判断mime值
 * @param $postion 上传的文件默认保存位置
 * @param $tmp_name 临时位置,转换目录使用
 * @return string path 存放路径
 */
class Upload
{   
    // 仅生成存放名称,不转移图片,避免因其他字段出错,而重复有多张图片存在
    public static function createImageSaveName($name, $type){
        $imageMime = array(
            'image/gif',
            'image/jpeg',
            'image/bmp',
            'image/png',
            //需要时添加...
        );
        if(!in_array($type, $imageMime)){
            // return false;
            return array(
                'code' => 0,
                'msg' => '图片mime类型不存在!',
            );
        }else{
            //获取图片后缀
            $arr_tmp_ext = explode('.', $name);
            $imageExt = end($arr_tmp_ext);
            //以当前时间命名此图片，避免重复
            $name = time() . uniqid() . '.' . $imageExt;

            return $name;
        }
    }
    // 转移图片
    // $custom 是否自定义路径, true 自定义   默认 false, 使用固定好的
    public static function moveImageToTargetPath($name, $tmp_name, $postion = 'default_files_path', $custom = false){
        //创建存放路径
        $path = Yii::app()->request->baseUrl . 'images/' . $postion . '/' . $name;
        if($custom){
            $path = Yii::app()->request->baseUrl . $postion . '/' . $name;
        }
        // 如果路径不存在, 则自动创建
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        //图片处理
        // $image = Yii::app()->image->load($tmp_name);
        // $image->resize($width, $height)->rotate(0)->quality(100)->sharpen(20);
        // $image->save($path);
        $result = move_uploaded_file($tmp_name, $path);

        if($result){
            return $path;
        }else{
            return false;
        }
    }

}
