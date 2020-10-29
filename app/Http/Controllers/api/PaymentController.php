<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\ExpressCheckout;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Braintree;
use DB;

 class PaymentController extends Controller{   
 	  private $environment = 'sandbox';
 	  private $merchantId  = '9z8qnsjxsr8ywfrh';
 	  private $publicKey   = 'ckr9kgsbzyr24h3p';
 	  private $privateKey  = 'f8e4aa9daf362987f04a2fbb4be48e78';
 	  private $gateway     = null;

      public function __construct(){
        /*echo "Ganesh";
        die;*/
       $this->gateway = new Braintree\Gateway([
        'environment'  => $this->environment,
        'merchantId'   => $this->merchantId,
        'publicKey'    => $this->publicKey,
        'privateKey'   => $this->privateKey,
      ]);
     }

     public function cardPayment($data = array()){
        $transaction = $this->gateway->transaction()->sale([
          'amount'             => $data['amount'],
          'paymentMethodNonce' => $data['nonce'],
          'options'            => [ 'submitForSettlement' => true ]
        ]);

        if($transaction->success){
          $transaction = $this->gateway->transaction()->find($transaction->transaction->id);
          $trans['id'] = $transaction->id;
          $trans['currency_iso_Code'] = $transaction->currencyIsoCode;
          $trans['card_type']  = $transaction->creditCard['cardType'];
          $trans['created_at'] = $transaction->createdAt;
          $trans['updated_at'] = $transaction->updatedAt ;
          $trans['amount']     = $transaction->amount;

          return array('status'=> true , 'message' => 'Transaction successfully', 'data' => $trans);
        }else{
          return array('status' => false , 'message' => 'Transaction failed');
      }
     } 

     public function getBraintreeToken(){
  	   return $this->gateway->ClientToken()->generate();
     }

    
      public function paypal(Array $payDataInfo) {
          $data = [];
          $data['items'] = array();
          $data['invoice_id'] = 1;
          $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
          $data['return_url']          = url('api/user/paypal_success?data=&user_id='.$payDataInfo['user_id'].'&payment_for='.$payDataInfo['payment_for'].'&item_id='.$payDataInfo['item_id']);
          $data['cancel_url']          = url('api/user/paypal_cancel');
          $data['total']               = $payDataInfo['amount'];
          $data['shipping_discount']   = 0;
          $provider = new ExpressCheckout;
          $response = $provider->setExpressCheckout($data);
         return redirect($response['paypal_link']);
    }

    public function paypalSuccess(Array $data = array()){
        $input    = $_GET;
        $token    = $input['token'];
        $provider = new ExpressCheckout;  
        $response = $provider->getExpressCheckoutDetails($token);
        //print_r($response);
        $response = array_merge($input,$response);
        if($response['ACK'] == 'Success'){
           $insertData['transaction_id']  = $response['PAYERID'];
           $insertData['user_id']         = $response['user_id'];
           $insertData['payment_mode']    = 'paypal';
           $insertData['currency']        = $response['CURRENCYCODE'];
           $insertData['amount']             = $response['AMT'];
           $insertData['transaction_status'] = '1';
           $insertData['transaction_for']    = $response['payment_for'];
           $insertData['item_id']            = $response['course_id'] ?? null;
           print_r($insertData);
           /*$transaction_id = DB::table('transactions')->insertGetId($insertData);
          if(isset($input['colos']) && $input['payment_for'] == '2' && !empty($input['colos'])){
             $userData = DB::table('users')->select('colos')->where('id',$input['user_id'])->first();
             $colos = $userData->colos + $input['colos'];
             DB::table('users')->where('id',$input['user_id'])->update(['colos'=> $colos ]);
          }*/
           return view('paypal.success');
        }
           return view('paypal.cancel');
    }

     public function paypalCancel(Array $data = array()){
        return ['status'=>false, 'message' => 'cancel'];
     }

 }