<?php

/*
 * Utility class easing PayZen form payments
 *
 * @version 0.3
 *
 */


class payzenFormToolbox {
 public $platForm = [
  'url' => 'https://secure.payzen.eu/vads-payment/' // The URL of the PayZen plat-form
 ];

 // Container for PayZen user's account informations
 public $account;

 // Container for shop user's account informations
 public $shopPlatForm;

 /*
  * Constructor, stores the PayZen user's account informations
  *
  * @param $siteId string, the account site id as provided by Payzen
  * @param $certTest string, certificate, test-version, as provided by PayZen
  * @param $certProd string, certificate, production-version, as provided by PayZen
  * @param $ipnUrl string, the URL PayZen will notify the payments to
  * @param $backUrl string, the URL PayZen will use to send the customer back after payment
  * @param $mode string ("TEST" or "PRODUCTION"), the PayZen mode to operate
  */
 public function __construct($siteId, $certTest, $certProd, $ipnUrl, $backUrl, $ctxMode){
  $this->account = [
     'vadsSiteId' => $siteId 
    ,'cert'   => [
        'TEST'       => $certTest
      , 'PRODUCTION' => $certProd
    ]
    , 'ctxMode'   => $ctxMode
  ];
  $this->shopPlatForm['ipnUrl']  = $ipnUrl;
  $this->shopPlatForm['backUrl'] = $backUrl;
 }



 /**
  * Utility function, returns all the mandatory data needed by a PayZen form payment
  * as an array
  *
  * @param $siteId string, the user site id
  * @param $transId string, the transaction id from user site 
  * @param $amount string, the amount of the payment
  * @param $currency string, the code of the currency to use
  *
  * @return array, the data to use in the fields of HTML payment form
  */
 public function getFormFields($siteId, $transId, $amount, $currency){
  $form_data =  [
      "vads_site_id"         => $siteId
    , "vads_ctx_mode"        => $this->account['ctxMode']
    , "vads_trans_id"        => $transId
    , "vads_trans_date"      => gmdate('YmdHis')
    , "vads_amount"          => $amount
    , "vads_currency"        => $currency
    , "vads_action_mode"     => "INTERACTIVE"
    , "vads_page_action"     => "PAYMENT"
    , "vads_version"         => "V2"
    , "vads_payment_config"  => "SINGLE"
    , "vads_capture_delay"   => "0"
    , "vads_validation_mode" => "0"
  ];

  $form_data['signature'] = $this->sign($form_data);
  return $form_data;
 }


 /**
  * Main fonction, checks the authenticity of the data received
  * during IPN request from PayZen plat-form
  *
  * @param $data Array, the data received from PayZen, usually the _POST
  *        PHP super-global
  * @throws Exception if the authenticity data can't be established
  */
 public function checkIpnRequest($data) {
  $vads_data = $this->filterVadsData($data);
  $signature_check = $this->sign($vads_data);
  if(@$data['signature'] != $signature_check){
   throw new Exception('Signature mismatch');
  }
 }

 /**
  * Utility function, builds and returns the signature string of the data
  *  being transmitted to the PayZen plat-form
  *
  * @param $vads_form Array, array of datat to being signed
  *
  * @return String, the signature
  */
 public function sign(Array $vads_form) {
  // Choice between TEST and PRODUCTION certificates
  $cert = $this->account['cert'][$this->account['ctxMode']];
  ksort($vads_form);           // VADS values sorted by name, ascending order
  return sha1(                 // SHA1 encryption of ...
   implode('+', $vads_form)    // ... VADS array values joined with '+' ...
   . "+$cert"                  // ... concatenated with '+' and the certificate.
   );
 }


 /**
  * Main function, returns an array containing all mandatory
  * information needed to build an HTML form for an createPayment
  * request
  *
  * @param transId string, an external transaction id
  * @param $amount string, the amount of the payment
  * @param $currency string, the code of the currency to use
  *
  * @return array, the form data, as follow:
  *
  *  [form] => Array
  *      (
  *          [action] => https://secure.payzen.eu/vads-payment/
  *          [method] => POST
  *          [accept-charset] => UTF-8
  *          [enctype] => multipart/form-data
  *      )
  *  [fields] => Array
  *      (
  *          [vads_site_id] => 12345678
  *          [vads_ctx_mode] => TEST
  *          [vads_trans_id] => 612435
  *          [vads_trans_date] => 20151116183355
  *          [vads_amount] => 4300
  *          [vads_currency] => 978
  *          [vads_action_mode] => INTERACTIVE
  *          [vads_page_action] => PAYMENT
  *          [vads_version] => V2
  *          [vads_payment_config] => SINGLE
  *          [vads_capture_delay] => 0
  *          [vads_validation_mode] => 0
  *          [signature] => 89d95486ac27addea254cf478fabf1d4a968266a
  *      )
  *
  */
 public function getFormData($transId, $amount, $currency) {
  return [
     "form" => [
	"action"         => $this->platForm['url']
      , "method"         => "POST"
      , "accept-charset" => "UTF-8"
      , "enctype"        => "multipart/form-data"
   ] 
   , "fields" => $this->getFormFields($this->account['vadsSiteId'], $transId, $amount, $currency)
  ];
 }

}
