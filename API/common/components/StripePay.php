<?php

namespace common\components;

use Stripe;
use Yii;
use yii\base\Component;

class StripePay extends Component
{
    public $credential;
    public $client_id;
    public $secret;
    public $mode;

    public function init()
    {
        $this->client_id = Yii::$app->params['stripe_pub_key'];
        $this->secret = Yii::$app->params['stripe_sec_key'];
        parent::init();
    }

    public function addCustomer($email, $userID)
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $data = \Stripe\Customer::create([
                "email" => $email,
                "metadata" => [
                    "user_id" => $userID,
                ],
            ]);
            return $data->id;
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }
    // public function createCharge($amount, $card_token, $customer_id, $currency = "USD", $metadata = [])
    // {
    //     \Stripe\Stripe::setApiKey($this->secret);
    //     try {
    //         $charge = \Stripe\Charge::create([
    //             'amount' => (float) $amount * 100,
    //             'currency' => $currency,
    //             // 'customer'=>$customer_id,
    //             'card' => $card_token,
    //             'metadata' => $metadata,
    //         ]);
    //         return $charge;
    //     } catch (\Exception $e) {
    //         throw new \yii\web\NotFoundHttpException($e->getMessage());
    //     }
    // }

    public function createCharge($amount, $card_token, $customer_id, $currency = "EUR", $metadata = [])
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $charge = \Stripe\Charge::create([
                'amount' => (float) $amount * 100,
                'currency' => $currency,
                'customer' => $customer_id,
                'source' => $card_token,
                'metadata' => $metadata,
                'description' => 'User payment successful.',
                'shipping' => [
                    'name' => 'Jenny Rosen',
                    'address' => [
                        'line1' => 'Via Enrico Fermi 28',
                        'postal_code' => '10021',
                        'city' => 'Torino',
                        'state' => 'CA',
                        'country' => 'IT',
                    ],
                ],
            ]);
            return $charge;
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }
    public function getAllCards($customer_id)
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $allcards = \Stripe\Customer::allSources(
                $customer_id,
                [
                    'object' => 'card',
                ]
            );

            return $allcards;
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }

    public function generateToken($card_number, $exp_month, $exp_year, $cvc)
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $token = \Stripe\Token::create([
                'card' => [
                    'number' => $card_number,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'cvc' => $cvc,
                ],
            ]);

            return $token;
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }

    public function addCard($customer_id, $token)
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $card = \Stripe\Customer::createSource(
                $customer_id,
                [
                    'source' => $token,
                ]
            );
            $allcards = \Stripe\Customer::allSources(
                $customer_id,
                [
                    //   'limit' => 3,
                    'object' => 'card',
                ]
            );
            return $allcards;
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }

    public function deleteCard($customer_id, $card_token)
    {
        \Stripe\Stripe::setApiKey($this->secret);
        try {
            $d = \Stripe\Customer::deleteSource(
                $customer_id,
                $card_token,
                []
            );
        } catch (\Exception $e) {
            throw new \yii\web\NotFoundHttpException($e->getMessage());
        }
    }
    public function makePayment($amount, $card_token, $metadata = [])
    {
        $customer_id = $this->update_stripe_token();
        $data = \Yii::$app->stripe->createCharge($amount, $card_token, $customer_id, $currency = "USD", $metadata = []);
        return [
            'status' => true,
            'data' => $data,
        ];
    }
    public function update_stripe_token()
    {
        $customer_id = !empty(\Yii::$app->user->identity->userData->stripeToken) ? \Yii::$app->user->identity->userData->stripeToken->stripe_customer_token : "";
        if (empty($customer_id)) {
            $customer_id = \Yii::$app->stripe->addCustomer(\Yii::$app->user->identity->email, \Yii::$app->user->identity->id);
            $model = new \common\models\UserStripeToken;
            $model->user_id = \Yii::$app->user->identity->id;
            $model->stripe_customer_token = $customer_id;
            $model->save();
        }
        return $customer_id;
    }
}
