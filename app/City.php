<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class City extends Model
{
    private $base_url = "https://api.n1.ru/api/v1/cached/offers/";
    private $region_id_list = [1054, 1024, 1066, 1074, 1059, 1029, 1063, 1052, 1072];
    private $get_params = [
        'limit' => 1,
        'offset'=>0,
        'status'=>'published',
        'region_id'=>0,
        'fields'=>'params.region,params.city'
    ];

    protected $fillable = ['region_id', 'name'];

    public function ReloadCity()
    {
        //City::truncate();

        $client = new Client();

        foreach($this->region_id_list as $regionID)
        {
            $this->get_params['region_id'] = $regionID;
            $response = $client->request('GET', $this->base_url, ['query' => $this->get_params]);
            $responseArray = json_decode($response->getBody()->getContents(), true);
print_r($responseArray);die();
            if(isset($responseArray['result'][0]['params']['city']['name_ru']))
            {
                $name = $responseArray['result'][0]['params']['city']['name_ru'];
                $city = new City(['region_id' => $regionID, 'name' => $name]);
                $city->save();
            }
        }
    }

    function allOfferCount()
    {
        return $this->hasMany('App\OfferCount', 'city_id');
    }

    function latestOfferCount()
    {
        return $this->hasOne('App\OfferCount', 'city_id')->latest();
    }
}
