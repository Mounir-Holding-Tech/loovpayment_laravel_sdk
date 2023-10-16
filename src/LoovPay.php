<?php

namespace Loov\LaravelSdk;
use Exception;
use Illiminate\Support\Facades\Http;

class LoovService {

    public $url ='https://api.secure.payment.loov-solutions.com/v1';
    public $headers =[];
    public $app_key;
    public $merchant_key;

   public function setKeys(string $app_key, string $merchant_key){

   $this->headers = array(
     'Accept' => 'application/json',
     'content-type' => 'application/json',
     'app-key' => $app_key,
     'merchant-key' =>$merchant_key
   );
   return $this;
}
   public function payIn($data){
    $body = [
        'amount' => $data['amount'], 
        'currency' => $data['currency'], 
        'payment_mode' =>$data['payment_mode'],
        'callback_url'=> $data['callback_url'], 
        'return_url'=> $data['return_url'], 
        'cancel_url'=> $data['cancel_url'], 
        'description' => $data['description'], 
        'customer' => [
            'name' => $data['username'],  
            'email' => $data['email'],    
            'phoneNumber' => $data['phoneNumber']  
            ]
        ];
    if (!array_key_exists('amount', $data)) throw new Exception('amount is not define');
    if (!array_key_exists('name', $data)) throw new Exception('name is not define');
    if (!array_key_exists('email', $data)) throw new Exception('email is not define');
    if (!array_key_exists('phoneNumber', $data)) throw new Exception('phoneNumber is not define');
    if(!array_key_exists('callback_url', $data)) throw new Exception("Error: callback url not define");
    if(!array_key_exists('cancel_url', $data)) throw new Exception("Error: cancel_url url not define");
    if(!array_key_exists('return_url', $data)) throw new Exception("Error: return_url url not define");

    $response = Http::withHeaders($this->headers)->post($this->url.'/payment/init', $body);

    return json_decode($response->body());

   }

   public function mobileSoftPay($data){
    $body = [
        "amount" => $data["amount"],    
        "operator" => $data["operator"],    
        "phoneNumber" => $data["phoneNumber"],    
        "customer" => [       
             "name" => $data["username"],       
             "email" => $data["email"],        
             "phoneNumber" => "237".$data["phoneNumber"]    
            ],
        "callback_url" => $data['callback_url']
        ]; 

        if (!array_key_exists('amount', $data)) throw new Exception('amount is not define');
        if (!array_key_exists('name', $data)) throw new Exception('name is not define');
        if (!array_key_exists('operator', $data)) throw new Exception('operator is not define');
        if (!array_key_exists('email', $data)) throw new Exception('email is not define');
        if (!array_key_exists('phoneNumber', $data)) throw new Exception('phoneNumber is not define');
        if(!array_key_exists('callback_url', $data)) throw new Exception("Error: callback url not define");
        if($data['amount'] <= 100) throw new Exception("Error: amount must be greather than 100");
    
        $response = Http::withHeaders($this->headers)->post($this->url.'/payment/payin/mobile', $body);
    
        return json_decode($response->body());

   }

   public function payOut($data){
       $body =[
        "amount" => $data["amount"],    
        "operator" => $data["operator"], 
        "phoneNumber" => "237".$data["phoneNumber"],
        'currency' => $data['currency'] 
       ];
       $response = Http::withHeaders($this->headers)->post($this->url.'/payment/payout/mobile', $body);
    
       return json_decode($response->body());
   }

   public function checkStatus(string $reference){

    $response = Http::withHeaders($this->headers)->post($this->url.'/payment/status/'.$reference);
    
    return json_decode($response->body());
   }

 }


