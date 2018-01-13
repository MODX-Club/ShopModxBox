<?php

class modWebImagesUploadProcessor extends modObjectProcessor{

	public function initialize(){

		$this->setDefaultProperties(array(
			"images_path"	=> "images/lazy/",
		));

		// print_r($this->properties);

		return parent::initialize();
	}


	public function process(){

		$files = $this->getProperty("file");

		if(!$files){
			return $this->failure("Не был получен файл");
		}

		$result = array();

		if(!is_array($files['name'])){
			return $this->processImage($files);
		}
		else{
			foreach($files['name'] as $index => $filename){
				
				$file_info = array(
					"name" => $files['name'][$index],
					"type" => $files['type'][$index],
					"tmp_name" => $files['tmp_name'][$index],
					"error" => $files['error'][$index],
					"size" => $files['size'][$index],
				);

				$response = $this->processImage($file_info);

				if($response['success']){
					$result[] = $response['object'];
				}
				else{

					return $response;

					// $this->modx->error->reset();
				}
			}
		}

		return $this->success('', $result);
	}


	public function processImage($file_info){

		if(!$file_info){
			return $this->failure("Не был получен файл");
		}

		$allow_extensions = explode(",", $this->modx->getOption('upload_images', null, 'jpg,jpeg,png'));

		$tmp_name = $file_info['tmp_name'];
		$name = $file_info['name'];
		$size = $file_info['size'];
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

		if(!in_array($ext, $allow_extensions)){
			return $this->failure("Файлы с таким расширением не разрешены к загрузке");
		}

		$hash_name = md5($name . $size) .".{$ext}";

		$images_path = $this->getProperty('images_path');

		$image_url = MODX_ASSETS_URL . $images_path . $hash_name;

		$full_path = MODX_BASE_PATH . $image_url;

		if(!move_uploaded_file($tmp_name, $full_path)){
			return $this->failure("Ошибка перемещения временного файла" . $full_path);
		}


		$thumb = $this->modx->runSnippet("imagick", array(
            "input" => $image_url,
            "options"   => "w=600&h=400&zc=T",
        ));

		return $this->success('Изображение успешно загружено', array(
			"url" => $image_url,
			"thumb" => $thumb,
		));
	}

}

return 'modWebImagesUploadProcessor';