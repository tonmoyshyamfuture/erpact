<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
include_once 'ewaybill_constants.php';

class Ewaybill {
    
    public function getAccessToken() {
        //fetch GSP user data
        $aspUserInfo = unserialize(accessTokenInfo);
        $aspUserData['username'] = $aspUserInfo['username'];
        $aspUserData['password'] = $aspUserInfo['password'];
        $aspUserData['client_id'] = $aspUserInfo['client_id'];
        $aspUserData['client_secret'] = $aspUserInfo['client_secret'];
        $aspUserData['grant_type'] = $aspUserInfo['grant_type'];
        $JsonAspUser = json_encode($aspUserData);
            $GSPApiUrl = unserialize(requestUrl);
            $url = $GSPApiUrl['access_token'];
            $result = $this->sendGSPRequest($url, $JsonAspUser, 'POST');
            if (isset($result) && isset($result->access_token)) {
                $response['error'] = false;
                $response['access_token'] = $result->access_token;
                $response['expire'] = $result->expires_in;
                $response['token_type'] = $result->token_type;
            } else{
                if (isset($result->error)) {
                    if (isset($result->error_description)) {
                        $msg = $result->error_description;
                    }elseif(isset($result->error_description)) {
                        $msg = $result->error_description;
                    } else {
                        $msg = $result->error_description;
                    }
                }else{
                    $msg = "Service not available. Please, try after sometime";
                }
                $response['error'] = true;
                $response['message'] = $msg;
            }

        return $response;
    }
  
    
    /**
     * send request
     */
    function sendGSPRequest($url, $data = null, $method = null, $other_detail_json = null) {
        $HeaderOption = array('Content-Type: application/json');
        if ($other_detail_json != null) {
            $other_detail = json_decode($other_detail_json, true);
            foreach ($other_detail as $key => $value) {
                array_push($HeaderOption, $key . ':' . $value);
            }
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $HeaderOption);
        
        if ($method == 'POST' || $method == 'PUT') {
            if ($method == 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_VERBOSE, true);
//        curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
//        
        // Execute post
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err = curl_error($ch);
        curl_close($ch);
        $result2 = json_decode($result);

        return $result2;
    }
    
    
    function getRequest($url){
//          $shopify_url = 'https://'.$shopify_api_details->shopify_api_key.':'.$shopify_api_details->shopify_api_password.'@'.$shopify_api_details->shopify_store_name.'.myshopify.com/admin/products.json';
//
//          echo $shopify_url;exit;

          // Initiate cURL.
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);

          // Tell cURL that we want to send a GET request.
          curl_setopt($ch, CURLOPT_HTTPGET, 1);

          // Set the content type to application/json
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

          // Tell cURL that we want to receive the actual raw return value
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          // Execute the request
          $result = curl_exec($ch);

          // close cURL resource, and free up system resources
          curl_close($ch);
          $result2 = json_decode($result);
          return $result2;
    }
    

  

}


