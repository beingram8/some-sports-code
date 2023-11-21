<?php
namespace common\components;

use Yii;

class Img extends \yii\base\Component
{
    public function upload($instance, $s3_folder = "", $width = 500, $old_photo_url, $type = 'image', $is_thumb = false)
    {
        $storage = Yii::$app->get('storage');
        if ($old_photo_url) {
            $this->unlink($old_photo_url);
        }

        //Unique Filename
        $filename = time() . '-' . $instance->baseName . '.' . $instance->extension;
        $BasePath = Yii::$app->basePath . '/../img_assets/temp/';

        \yii\helpers\FileHelper::createDirectory($BasePath);

        if ($type == 'image') {
            // Get file size for resize
            $imagine = \yii\imagine\Image::getImagine();
            $imagine = $imagine->open($instance->tempName);
            $sizes = getimagesize($instance->tempName);
            $height = round($sizes[1] * $width / $sizes[0]);
            \yii\imagine\Image::resize($instance->tempName, $width, $height)->save(Yii::getAlias($BasePath . $filename), ['quality' => 90]);
        }
        $instance->saveAs($BasePath . $filename);

        if ($is_thumb) {
            \yii\imagine\Image::thumbnail($BasePath . $filename, $width, $height)->save(Yii::getAlias($BasePath . $filename), ['quality' => 80]);
        }

        if (empty($s3_folder)) {
            $result = $storage->upload($filename, $BasePath . $filename);
        } else {
            $result = $storage->upload($s3_folder . '/' . $filename, $BasePath . $filename);
        }

        $filepath = $BasePath . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        return $storage->getUrl($s3_folder . '/' . $filename);

    }
    public function unlink($photo_url)
    {
        $storage = Yii::$app->get('storage');
        if ($photo_url) {
            $data = explode("https://fanrating.fra1.digitaloceanspaces.com/", $photo_url);
            if ($data && !empty($data[1])) {
                $storage->delete($data[1]);
            }
        }
        return true;
    }

    public function showImage($image)
    {
        if (!empty($image)) {
            return '<div class="symbol symbol-50 symbol-lg-75">
                    <img alt="Pic" src="' . $image . '">
                </div>';
        } else {
            return '<div class="symbol symbol-50 symbol-lg-75">
                    <img alt="Pic" src="' . \Yii::$app->general->img_assets('placeholder_for_user.svg') . '">
                </div>';
        }
    }
}
