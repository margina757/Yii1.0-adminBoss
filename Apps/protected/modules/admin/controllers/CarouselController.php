<?php
/**
 * @file CarouselController.php
 * @sinopsis 首页轮播图展示控制
 * @author <jackun@lonlife.net>
 * @created  2015-02-02 17:34:10
 * @modified 
 */
class CarouselController extends AdminBaseController{

	public function actionIndex(){
		$variableModel = new VariableModel();
		if(Yii::app()->request->isPostRequest){
			//导入图片上传类
			Yii::import('application.extensions.image.Image');

			$formData = array();
			$oldPic = $_POST['oldPic'];
			$address = $_POST['address'];
			$alt = $_POST['alt'];
			$files = $_FILES['pic'];

			foreach ($files['error'] as $k => $v) {
				// $formData[$k]['id'] = $k+1;
				if($v != 0){
					$formData[$k]['pic'] = $oldPic[$k];
				}else{
					//文件相关路径及名称(无后缀)
					$dirArr = UploadFiles::getCarouselDir();
					//文件后缀名
					$ext = UploadFiles::getExtName($files['name'][$k])['ext'];
					$allowedPic = Yii::app()->params['allowedPic'];

					//图片格式验证(允许格式：jpeg  | jpg  | png | gif | bmp)
					if(!in_array($ext,$allowedPic)){
						$this->_setErrorFlash('更新首页轮播图失败，不允许的图片格式！');
						return $this->redirect('/admin/Carousel');
					}
					//数据库内存储的路径及文件名
					$dbUrl = $dirArr['dbUrl'].'_small'.$ext;
					//文件上传的真实路径及文件名
					$trueUrl = $dirArr['trueUrl'].$ext;
					//文件上传缩略图的真实路径及文件名
					$trueSmallUrl = $dirArr['trueUrl'].'_small'.$ext;

					if(!move_uploaded_file($files['tmp_name'][$k],$trueUrl)){
						$formData[$k]['pic'] = $oldPic[$k];
					}else{
						//生成缩略图,图片名称例如:14228578606602_small.jpg
						$image = new Image($trueUrl);

						//生成固定大小的图片
						$image->resize(386, 237, Image::NONE);
						$image->save($trueSmallUrl);
						$formData[$k]['pic'] = $dbUrl;
					}

					if(file_exists($trueUrl)){
						//存在删除原图
						@unlink($trueUrl);
					}

				}

				$formData[$k]['address'] = $address[$k];
				$formData[$k]['alt'] = $alt[$k];
			}

			//格式化数据，将数组转变成JSON
			$value = $this->formatCarousel($formData);

			if($variableModel->variableSet('Carousel',$value)){
				$this->_setSuccessFlash('更新首页轮播图成功！');
				$this->refresh();
			}else{
				$this->_setErrorFlash('更新首页轮播图失败！');
			}
		}

		$CarouselData = $this->_getCarousel();

		$this->render('carousel',array(
			'carousel' => $CarouselData,
		));
	}

	/**	
	 * 转换数组数据为JSON格式
	 */
	public function formatCarousel($data=array()){
		if($data){
			$value = '[';
			$i = 1;
			$len = count($data);

			foreach ($data as $k => $v) {
				if($i <= $len-1){
					$value .= Tool::json_encode2($v).',';
				}else{
					$value .= Tool::json_encode2($v);
				}
				$i++;
			}
			$value .= ']';

			return $value;
		}
		return '';
	}

	/**
	 * 格式化JSON为数组
	 */
	public function _getCarousel(){
		$variableModel = new VariableModel();
		$CarouselData = $variableModel->variableGet('Carousel');
		$CarouselData = json_decode($CarouselData);
		$newData = array();

		if($CarouselData){
			foreach ($CarouselData as $key => $value) {
				$newData[$key] = (array)$value;
			}
		}

		return $newData;
	}

	/**
	 * 前台轮播图调用接口,json数据
	 */
	public function actionCarouselJson(){
		$variableModel = new VariableModel();
		$CarouselData = $variableModel->variableGet('Carousel');
		print $CarouselData;
	}

}