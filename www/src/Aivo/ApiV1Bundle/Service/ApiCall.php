<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2021.
//

namespace Aivo\ApiV1Bundle\Service;
use Symfony\Component\Config\Definition\Exception\Exception;

use Doctrine\ORM\EntityManager;

/**
 * Class ApiCall
 *
 * @package Aivo\ApiV1Bundle\Service
 */
class ApiCall
{
    protected $authorization='';

    public function __construct($client_id,$client_secret){
        $data=[
            'grant_type'=>'client_credentials',
            'client_id'=>$client_id,
            'client_secret'=>$client_secret
        ];
        $ch = curl_init("https://accounts.spotify.com/api/token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch))
            throw new Exception("Error",$httpcode);
        curl_close($ch);
        $response = ($result)?json_decode($result,TRUE):[];
        $this->authorization="Authorization: ".$response["token_type"] ." ".$response["access_token"];
        return true;
    }

    /**
     * @param String $q
     */

    public function getAlbums($q=null){
       return $this->searchAlbumsByArtist($this->searchArtist($q));
    }

    private function searchArtist($q=null){
        $ch = curl_init("https://api.spotify.com/v1/search?type=artist&limit=1&q=".$q);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',$this->authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch))
            throw new Exception("Error",$httpcode);
        curl_close($ch);
        if($httpcode!=200)
            throw new Exception("Error",$httpcode);
        $response = ($result)?json_decode($result,TRUE):[];
        if(count(@$response["artists"]["items"])>0)
            return @$response["artists"]["items"][0]["id"];
        else throw new Exception("No se encontró el artista",200); 
        
    }

    /**
     * @param String $artistId
     */

    private function searchAlbumsByArtist($artistId=null){
        $ch = curl_init("https://api.spotify.com/v1/artists/".$artistId."/albums?limit=50");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',$this->authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch))
            throw new Exception("Error",$httpcode);
        curl_close($ch);
        if($httpcode!=200)
            throw new Exception("Error",$httpcode);
        $response = ($result)?json_decode($result,TRUE):[];
        $results=[];
        foreach(@$response["items"] as $item){
            $results[]=[
                "name"=>$item["name"],
                "released"=>$item["release_date"],
                "tracks"=>$item["total_tracks"],
                "cover"=>$item["images"][0],
            ];
        }
        return $results;
    }
    
}