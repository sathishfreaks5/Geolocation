<?php
namespace geolocation;

use GuzzleHttp\Client;

trait Geolocation
{  
	/**
     * Google API Key. This Api key should be free for limited access. 
	 * For uninterrupt access should be get paid version apikey.
     *
     * @return String.
     */
    protected function googleKey(){
        return '<your_api_key>';
    }
	
	
	/**
     * find latitude and longitude by given zipcode using google service provider. 
     *
	 * @Params: Zipcode. String or Number.
	 *
     * @return Array.
	 *	Array's First Element: Status of the function.
	 *	Array's Second Element: Success then Latitude, Failure then status err message
	 *	Array's Third Element: Success then longitude, Failure then none.
	 *
     */
    public function getLatLonByZipcode($zipcode){   
        $json = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$zipcode.'&key='.$this->googleKey();
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $json);
        $res->getStatusCode(); 
        $res->getHeaderLine('content-type'); 
        $output = $res->getBody();  
        $result = json_decode($output, true); 
        if(array_key_exists("status", $result)){
            if($result["status"] == 'OK'){
                $array[0] = 'success';
                $array[1] = $result['results'][0]['geometry']['lat'];
                $array[2] = $result['results'][0]['geometry']['lng']; 
            }else{
                $array[0] = 'error';
                if(array_key_exists("error_message", $result)){
                    $array[1] = $result['error_message'];
                }else{
                    $array[1] = 'UNKNOWN ERROR';
                }  
            }
        }  
        return $array;
    }
	
	/**
     * find Zipcode by given latitude and longitude using google service provider. 
     *
	 * @Params: latitude and longitude.
	 *
     * @return Array.
	 *	Array's First Element: Status of the function.
	 *	Array's Second Element: Success then Zipcode, Failure then status err message 
	 *
     */
    public function findZipByLatLon($latitude, $longitude){  
         
         $glink = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&sensor=false&key=".$this->googleKey();//.$googleKey;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $glink);
        $res->getStatusCode(); 
        $res->getHeaderLine('content-type'); 
        $output = $res->getBody();  
        $result = json_decode($output, true);
        if($result['error_message']){
            $array[0] = 'error';
            $array[1] = $result['error_message'];
        }else if(count($result['results']) > 0){
            $array[0] = 'success';
            $array['1'] = $result['results'][0]['address_components']; 
        }else{
            $array[0] = 'error';
            $array[1] = 'Cant fetch zipcode';
        }
        
        return $array; 
         
    }
 

}