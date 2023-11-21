<?php

namespace backend\controllers;

use Imagine\Image\Box;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\imagine\Image;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * AccountController implements the CRUD actions for User model.
 */
class ImageController extends Controller
{

    public $path;
    public $url;
    public $uploadParam = 'file';
    public $maxSize = 2097152;
    public $extensions = 'jpeg, jpg, png, gif';
    public $width = 200;
    public $height = 200;
    public $jpegQuality = 100;
    public $pngCompressionLevel = 1;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['upload'],
                'rules' => [
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
        ];
    }

    public function actionUpload($s3_folder)
    {
        $this->path = Yii::$app->basePath . '/../img_assets/temp/';
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($this->uploadParam);
            $model = new DynamicModel(compact($this->uploadParam));
            $model->addRule($this->uploadParam, 'image', [
                'maxSize' => $this->maxSize,
                'tooBig' => Yii::t('cropper', 'TOO_BIG_ERROR', ['size' => $this->maxSize / (1024 * 1024)]),
                'extensions' => explode(', ', $this->extensions),
                'wrongExtension' => Yii::t('cropper', 'EXTENSION_ERROR', ['formats' => $this->extensions]),
            ])->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError($this->uploadParam),
                ];
            } else {
                $model->{$this->uploadParam}->name = uniqid() . '.' . $model->{$this->uploadParam}->extension;
                $request = Yii::$app->request;

                $width = $request->post('width', $this->width);
                $height = $request->post('height', $this->height);

                $image = Image::crop(
                    $file->tempName . $request->post('filename'),
                    intval($request->post('w')),
                    intval($request->post('h')),
                    [$request->post('x'), $request->post('y')]
                )->resize(
                    new Box($width, $height)
                );

                if (!file_exists($this->path) || !is_dir($this->path)) {
                    $result = [
                        'error' => Yii::t('cropper', 'ERROR_NO_SAVE_DIR')]
                    ;
                } else {
                    $saveOptions = ['jpeg_quality' => $this->jpegQuality, 'png_compression_level' => $this->pngCompressionLevel];
                    if ($image->save($this->path . $model->{$this->uploadParam}->name, $saveOptions)) {

                        $filename = $model->{$this->uploadParam}->name;
                        $storage = Yii::$app->get('storage');
                        if (empty($s3_folder)) {
                            $result = $storage->upload($filename, $this->path . $model->{$this->uploadParam}->name);
                        } else {
                            $result = $storage->upload($s3_folder . $model->{$this->uploadParam}->name, $this->path . $model->{$this->uploadParam}->name);
                        }

                        $filepath = $this->path . $model->{$this->uploadParam}->name;
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                        $result = [
                            'filelink' => $storage->getUrl($s3_folder . $filename),
                        ];
                    } else {
                        $result = [
                            'error' => Yii::t('cropper', 'ERROR_CAN_NOT_UPLOAD_FILE'),
                        ];
                    }
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            throw new BadRequestHttpException(Yii::t('cropper', 'ONLY_POST_REQUEST'));
        }
    }
}