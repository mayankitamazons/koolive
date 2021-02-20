<?php

class Fpx {
  public $merchant_id;
  public $prefix;
  public $order_number;
  public $user_email;
  public $description;
  public $amount;
  public $exchange_id;
  public $seller_id;
  public $signature;
  public $prefix_number;

  public $private_key;

  public $request_keys = [
    'fpx_buyerAccNo'      => '',
    'fpx_buyerBankBranch' => '',
    'fpx_buyerBankId'     => '',
    'fpx_buyerEmail'      => '',
    'fpx_buyerIban'       => '',
    'fpx_buyerId'         => '',
    'fpx_buyerName'       => '',
    'fpx_makerName'       => '',
    'fpx_msgToken'        => '01',
    'fpx_msgType'         => 'AR',
    'fpx_productDesc'     => '',
    'fpx_sellerBankCode'  => '01',
    'fpx_sellerExId'      => '',
    'fpx_sellerExOrderNo' => '',
    'fpx_sellerId'        => '',
    'fpx_sellerOrderNo'   => '',
    'fpx_sellerTxnTime'   => '',
    'fpx_txnAmount'       => '',
    'fpx_txnCurrency'     => 'MYR',
    'fpx_version'         => '5.0'
  ];

  public function __construct($attributes)
  {
    foreach($attributes as $key => $value)
    {
      $this->{$key} = $value;
    }

    $merchant_id = explode('|', $attributes['merchant_id']);
    $this->exchange_id    = trim($merchant_id[0]);
    $this->seller_id      = trim($merchant_id[1]);
    $this->prefix_number  = $this->prefix . $this->order_number;

    $this->request_keys['fpx_sellerExId']     = $this->exchange_id;
    $this->request_keys['fpx_sellerOrderNo']  = $this->request_keys['fpx_sellerExOrderNo'] = $this->prefix_number;
    $this->request_keys['fpx_sellerTxnTime']  = date('YmdHis');
    $this->request_keys['fpx_sellerId']       = $this->seller_id;
    $this->request_keys['fpx_txnAmount']      = $this->amount;
    $this->request_keys['fpx_buyerEmail']     = $this->user_email;
    $this->request_keys['fpx_productDesc']    = $this->description;

    $this->private_key  = base_path() . '/fpx/' . $this->exchange_id . '.key';
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
  }

  public function __set($property, $value) {
    if($property == 'amount') {
      $this->amount = sprintf('%2.f', $value);
    }
    if (property_exists($this, $property)) {
      $this->$property = $value;
    }

    return $this;
  }

  public function sign() {
    ksort($this->request_keys);
    $string = implode('|', array_values($this->request_keys));
    $file = fopen($this->private_key, 'r');
    $private_key = fread($file, 8192);
    fclose($file);
    $key = openssl_get_privatekey($private_key);
    openssl_sign($string, $signature, $key, OPENSSL_ALGO_SHA1);
    $this->signature = $this->request_keys['fpx_checkSum'] = strtoupper(bin2hex($signature));
    ksort($this->request_keys);
  }
}
