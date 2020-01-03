<?php

namespace App\Libs;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

class Image
{
	public $timestamps = false;
	
	public function resize($image_path, $image_name){
		if (!file_exists($image_path.'small')) {
			mkdir($image_path.'small/', 0777, true);
		}
		if (!file_exists($image_path.'medium')) {
			mkdir($image_path.'medium/', 0777, true);
		}
		if (!file_exists($image_path.'large')) {
			mkdir($image_path.'large/', 0777, true);
		}
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(300, 0);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'small/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(800, 0);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'medium/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(1024, 0);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'large/'.$image_name);
	}
	
	public function fixedResize($image_path, $image_name){
		if (!file_exists($image_path.'small')) {
			mkdir($image_path.'small/', 0777, true);
		}
		if (!file_exists($image_path.'medium')) {
			mkdir($image_path.'medium/', 0777, true);
		}
		if (!file_exists($image_path.'large')) {
			mkdir($image_path.'large/', 0777, true);
		}
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(314, 235);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'small/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(640, 480);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'medium/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(1024, 768);
		//$image->resizeImage(1024, 768, \Imagick::FILTER_POINT, 1);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'large/'.$image_name);
	}
	
	public function avatarResize($image_path, $image_name){
		if (!file_exists($image_path.'small')) {
			mkdir($image_path.'small/', 0777, true);
		}
		if (!file_exists($image_path.'medium')) {
			mkdir($image_path.'medium/', 0777, true);
		}
		if (!file_exists($image_path.'large')) {
			mkdir($image_path.'large/', 0777, true);
		}
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(64, 64);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'small/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(150, 150);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'medium/'.$image_name);
		$image = new \Imagick($image_path.$image_name);
		$image->setFormat('jpeg');
		$image->scaleImage(512, 512);
		$image->setImageCompression(\Imagick::COMPRESSION_JPEG);
		$image->setImageCompressionQuality(80);
		$image->writeImage($image_path.'large/'.$image_name);
	}
	
	public function delete($image_path, $image_name){
		foreach(['small', 'medium', 'large'] as $value){
			//Storage::delete($image_path."{$value}/{$image_name}");
			if (file_exists($image_path."{$value}/{$image_name}") && is_file($image_path."{$value}/{$image_name}")) {
				unlink($image_path."{$value}/{$image_name}");
			}
		}
		//Storage::delete($image_path."{$image_name}");
		if (file_exists($image_path."{$image_name}") && is_file($image_path."{$image_name}")) {
			unlink($image_path."{$image_name}");
		}
	}
}
