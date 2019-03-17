<?php

namespace IFirmaApi;

abstract class IFirmaApi {

    private $apiUrl = 'https://www.ifirma.pl/iapi/';

    protected $apiKeyName;
    private $apiLogin;
    private $apiKey;

    private $curlTimeoutValue = 300;
    private $curlConnectTimeoutValue = 100;

    const REQUEST_TYPE_GET = 1;
    const REQUEST_TYPE_POST = 2;
    const REQUEST_TYPE_PUT = 3;

    const RESPONSE_TYPE_JSON = 'json';
    const RESPONSE_TYPE_PDF = 'pdf';
    const RESPONSE_TYPE_XML = 'xml';

    protected function __construct($login, $key) {

        $this->apiLogin = $login;
        $this->apiKey = $key;

    }

    public static function hmac($key, $data) {

        $blocksize = 64;
        $hashfunc = 'sha1';
        if(strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }
        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));

        return bin2hex($hmac);
    }

    public static function hexToStr($hex) {

        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i+=2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    private function curlInit($type, $url, $headers, $requestContent) {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeoutValue);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeoutValue);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                
        if( $type == self::REQUEST_TYPE_GET ) {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($curl, CURLOPT_HTTPGET, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requestContent);
        }
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        if( $type == self::REQUEST_TYPE_POST ) {
            curl_setopt($curl, CURLOPT_POST, true);            
        } else if( $type == self::REQUEST_TYPE_PUT ) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        return $curl;
    }

    protected function request($path, $type = self::REQUEST_TYPE_GET, $params = '', $responseType = self::RESPONSE_TYPE_JSON ) {

        $url = $this->apiUrl . $path;

        $headers = [];
        $headers[] = 'Accept: application/' . $responseType;
        $headers[] = 'Content-type: application/'. $responseType .'; charset=UTF-8';
        
        if( $responseType == self::RESPONSE_TYPE_XML ) {
            $url .= '.xml';
        } else if( $responseType != self::RESPONSE_TYPE_PDF ) {
            $url .= '.json';            
        }

        if( $type == self::REQUEST_TYPE_GET ) {
            $requestContent = '';
        } else if( is_array($params) ) {
            $requestContent = json_encode($params);
        } else {
            $requestContent = $params;
        }

        $hashContent = $url . $this->apiLogin . $this->apiKeyName . $requestContent;
        $headers[] = 'Authentication: IAPIS user='. $this->apiLogin .', hmac-sha1='. self::hmac(self::hexToStr($this->apiKey), $hashContent );

        if( $type == self::REQUEST_TYPE_GET && is_array($params) ) {
            $url .= '?' . http_build_query($params);
        }

        $curl = $this->curlInit($type, $url, $headers, $requestContent);
        $response = curl_exec($curl);

        if( $responseType != self::RESPONSE_TYPE_JSON ) {
            return $response;
        } else {
            return new \IFirmaApi\Model\Response( $response );
        }

    }

}