<?php

namespace frontend\modules\v1\controllers;

use frontend\filters\auth\HttpBearerAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class PaymentController extends ActiveController
{
    public $modelClass = 'common\models\Card';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                //register you api action and intialize method
                'card-list' => ['get'],
                'add-card' => ['post'],
                'delete-card' => ['post'],
                'make-payment' => ['post'],
                'purchase-level' => ['post'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            //enter action where no authentication required
            'option',
        ];
        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['card-list', 'create-card', 'delete-card', 'make-payment', 'purchase-level'], //only be applied to
            'rules' => [
                [
                    'actions' => ['card-list', 'create-card', 'delete-card', 'make-payment', 'purchase-level'],
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionCardList()
    {
        $customer_id = \Yii::$app->stripe->update_stripe_token();
        $cards = \Yii::$app->stripe->getAllCards($customer_id);
        return [
            'status' => true,
            'data' => [
                'cards' => $cards,
            ],
        ];
    }

    public function actionCreateCard($token)
    {
        if ($token) {
            $customer_id = \Yii::$app->stripe->update_stripe_token();
            $cards = \Yii::$app->stripe->addCard($customer_id, $token);
            return [
                'status' => true,
            ];
        } else {
            return array('status' => false, 'message' => \Yii::t('app', 'Manca il token'));
        }
    }

    public function actionDeleteCard($token)
    {
        $customer_id = \Yii::$app->stripe->update_stripe_token();
        $data = \Yii::$app->stripe->deleteCard($customer_id, $token);
        return [
            'status' => true,
            'data' => $data,
            'message' => \Yii::t('app', 'Card Deleted'),
        ];
    }
    public function actionMakePayment($plan_id, $card_token)
    {
        $tokeninfo = Yii::$app->token->getTokenInfo($plan_id);

        $customer_id = \Yii::$app->stripe->update_stripe_token();
        $data = \Yii::$app->stripe->createCharge($tokeninfo->price, $card_token, $customer_id, $currency = "EUR", $metadata = []);

        if ($data['status'] == 'succeeded') {
            $payment_transaction_status = 10;
        } else {
            $payment_transaction_status = 20;
        }

        $user_payment = new \common\models\UserPaymentTransaction();
        $user_payment->user_id = Yii::$app->user->id;
        $user_payment->status = $payment_transaction_status;
        $user_payment->amount = $tokeninfo->price;
        $user_payment->description = 'Spend ' . $tokeninfo->price . ' on ' . $tokeninfo->token . ' token';
        $user_payment->created_by = Yii::$app->user->id;
        if ($user_payment->save()) {
            if ($payment_transaction_status == 10) {
                $model = new \common\models\UserTokenTransaction();
                $model->user_id = Yii::$app->user->id;
                $model->transaction_type = 10;
                $model->token = $tokeninfo->token;
                $model->created_by = Yii::$app->user->id;
                $model->remark = 'Hai comprato ' . $tokeninfo->token . ' Fan Coins';
                if ($model->save()) {
                    $user = Yii::$app->user->identity;
                    $user->userData->token = $user->userData->token + $model->token;
                    $user->userData->save(false);
                }
                return ['status' => true, 'message' => \Yii::t('app', 'Your account has been credited with {token}', ['token' => $model->token])];
            } else {
                return ['status' => false, 'message' => \Yii::t('app', 'Payment process has been failed.')];
            }
        } else {
            return ['status' => false, 'message' => Yii::$app->general->error($user_payment->errors)];
        }
    }
    public function actionPurchaseLevelByToken($level_id)
    {
        $level = Yii::$app->userData->getLevelPrice($level_id);
        if ($level && $level->level_price < \Yii::$app->user->identity->userData->token) {

            $assign_user_token = new \common\models\UserTokenTransaction();
            $assign_user_token->user_id = \Yii::$app->user->identity->id;
            $assign_user_token->transaction_type = 20;
            $assign_user_token->token = $level->level_price;
            $assign_user_token->created_by = \Yii::$app->user->identity->id;
            $assign_user_token->remark = 'Raise your level';
            if ($assign_user_token->save()) {
                if (Yii::$app->token->deductUserToken($assign_user_token->user_id, $assign_user_token->token)) {
                    $user = Yii::$app->user->identity;
                    $next_level_point = \Yii::$app->userData->getUserLevelPoint($level_id);
                    $point_to_add = $next_level_point - $user->userData->point;
                    $user->userData->point = $user->userData->point + $point_to_add + 1;
                    $user_level_id = \Yii::$app->userData->getLevelbyPoint($user->userData->point);
                    $user->userData->level_id = $user_level_id;
                    $user->userData->save(false);
                    return ['status' => true, 'message' => \Yii::t('app', 'Your level has been upgraded. ')];
                }
            }

        } else {
            return ['status' => true, 'message' => \Yii::t('app', 'Insufficient token. ')];
        }
    }
    public function actionPurchaseLevel($level_id, $card_token)
    {
        $level = Yii::$app->userData->getLevelPrice($level_id);

        if ($level) {
            $customer_id = \Yii::$app->stripe->update_stripe_token();
            $data = \Yii::$app->stripe->createCharge($level->level_price, $card_token, $customer_id, $currency = "EUR", $metadata = []);

            if ($data['status'] == 'succeeded') {
                $payment_transaction_status = 10;
            } else {
                $payment_transaction_status = 20;
            }

            if ($payment_transaction_status == 10) {
                $user_payment = new \common\models\UserPaymentTransaction();
                $user_payment->user_id = Yii::$app->user->id;
                $user_payment->status = $payment_transaction_status;
                $user_payment->amount = $level->level_price;
                $user_payment->description = 'Spend ' . $level->level_price . ' to upgrade a level';
                $user_payment->created_by = Yii::$app->user->id;

                if ($user_payment->save()) {
                    $user = Yii::$app->user->identity;
                    $next_level_point = \Yii::$app->userData->getUserLevelPoint($level_id);
                    $point_to_add = $next_level_point - $user->userData->point;
                    $user->userData->point = $user->userData->point + $point_to_add + 1;
                    $user_level_id = \Yii::$app->userData->getLevelbyPoint($user->userData->point);
                    $user->userData->level_id = $user_level_id;
                    $user->userData->save(false);
                    return ['status' => true, 'message' => \Yii::t('app', 'Your level has been upgraded. ')];
                } else {
                    return ['status' => false, 'message' => Yii::$app->general->error($user_payment->errors)];
                }
            } else {
                return ['status' => false, 'message' => \Yii::t('app', 'Payment process has been failed.')];
            }
        } else {
            return ['status' => false, 'message' => \Yii::t('app', 'Invalid level or price is not found.')];
        }
    }
}