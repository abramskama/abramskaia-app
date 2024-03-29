<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class N1ApiClient extends Model
{
    private $base_url = "https://api.n1.ru/api/v1/cached/offers/";
    private $region_id_list = [1054, 1024, 1066, 1074, 1059, 1029, 1063, 1052, 1072, 2222];
    private $get_params = [
        'limit' =>1,
        'offset'=>0,
        'status'=>'published',
        'region_id'=>0,
        'fields'=>'params.region,params.city'
    ];
    private $client;

    function __construct()
    {
        $this->client = new Client();
    }

    private function getResponseArray(int $regionID)
    {
        $this->get_params['region_id'] = $regionID;
        $response = $this->client->request('GET', $this->base_url, ['query' => $this->get_params]);
        $responseArray = json_decode($response->getBody()->getContents(), true);

        return $responseArray;
    }

    public function getCityName(int $regionID)
    {
        $responseArray = $this->getResponseArray($regionID);

        if(isset($responseArray['result'][0]['params']['city']['name_ru'])) {
            return $responseArray['result'][0]['params']['city']['name_ru'];
        }

        return null;
    }

    public function getCityOfferCount(int $regionID)
    {
        $responseArray = $this->getResponseArray($regionID);

        if(isset($responseArray['metadata']['resultset']['count'])) {
            return $responseArray['metadata']['resultset']['count'];
        }

        return null;
    }

    public function getRegionIDList()
    {
        return $this->region_id_list;
    }


}
