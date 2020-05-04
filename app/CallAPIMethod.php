<?php

namespace App\Component;

use Arr;

class CallAPIMethod
{
    public static function get($address, $params = null){
        $cURL = curl_init();

        if (! empty($params)) {
            $address = $address . '?' . \http_build_query($params);
        }
        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS').$address,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
            )
        ];
        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);

        curl_close($cURL);
        return json_decode($result, 1);
    }

    public static function getPhoto($address){
        header("Content-Type: image/jpeg");
        $cURL = curl_init();

        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS').$address,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
            )
        ];
        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);

        curl_close($cURL);
        return ($result);
    }
    public static function post($data, $address){
        $cURL = curl_init();
        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS'). $address,
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=> $data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
                'Content-Type: application/json',
            ),
            CURLOPT_RETURNTRANSFER=>true,
        ];
        // dd($options);
        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);
        curl_close($cURL);
        return json_decode($result, 1);
    }

    public static function delete($address){
        $cURL = curl_init();
        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS'). $address,
            CURLOPT_CUSTOMREQUEST=>'DELETE',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
                'Content-Type: application/json',
            ),
            CURLOPT_RETURNTRANSFER=>true,
        ];
        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);
        // dd($result);
        curl_close($cURL);
        if (json_decode($result) != null) {
            return json_decode($result, 1);
        } else {
            throw new \Exception('Cannot delete person: API Call error');
        }

    }

    public static function patch($data, $address) {
        $cURL = curl_init();
        if (! empty($data)) {
            $address = $address . '?' . \http_build_query($data);
        }
        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS'). $address,
            CURLOPT_CUSTOMREQUEST=>'PATCH',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
                'Content-Type: application/json',
            ),
            CURLOPT_RETURNTRANSFER=>true,
        ];

        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);

        curl_close($cURL);
        return json_decode($result, 1);
    }

    public static function put($data, $address){
        $cURL = curl_init();
        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS'). $address,
            CURLOPT_CUSTOMREQUEST=>'PUT',
            CURLOPT_POSTFIELDS=> $data,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
                'Content-Type: application/json',
            ),
            CURLOPT_RETURNTRANSFER=>true,
        ];

        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);

        curl_close($cURL);
        return json_decode($result, 1);
    }

    public static function upload($data, $address) {
        $cURL = curl_init();
        $file = $data['photo'];
        $file = str_replace('\\', '/', $file);
        $data['photo'] = new \CURLFILE($file, mime_content_type($file), pathinfo($file)['basename']);

        $options =[
            CURLOPT_URL=>env('API_BASE_ADDRESS'). $address,
            CURLOPT_POST=>true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER=>array(
                'Origin: ' . env('APP_URL'),
                'Authorization: Bearer '.session()->get('access_token'),
                'Content-Type: multipart/form-data',
            ),
            CURLOPT_RETURNTRANSFER=>true,
        ];
        curl_setopt_array($cURL, $options);
        $result=curl_exec($cURL);
        curl_close($cURL);
        return json_decode($result, 1);
    }
}
