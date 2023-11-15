<?php
  require_once '../vendor/autoload.php';
  MercadoPago\SDK::setAccessToken("TEST-4529679476588620-062212-b6da2a887cfb9be48e20cfedf83075fa-267114465");
  $contents = json_decode(file_get_contents('php://input'), true);
 
  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = $contents['transaction_amount'];
  $payment->token = $contents['token'];
  $payment->installments = $contents['installments'];
  $payment->payment_method_id = $contents['payment_method_id'];
  $payment->issuer_id = $contents['issuer_id'];
  $payer = new MercadoPago\Payer();
  $payer->email = $contents['payer']['email'];
  $payer->identification = array(
     "type" => $contents['payer']['identification']['type'],
     "number" => $contents['payer']['identification']['number']
  );
  $payment->payer = $payer;
  $payment->save();
  $response = array(
     'status' => $payment->status,
     'status_detail' => $payment->status_detail,
     'id' => $payment->id,
     'amount' => $payment->transaction_amount
  );
  echo json_encode($response);
?>
