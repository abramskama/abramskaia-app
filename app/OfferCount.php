<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class OfferCount extends Model
{
    private $base_url = "https://api.n1.ru/api/v1/cached/offers/";
    private $region_id_list = [1054, 1024, 1066, 1074, 1059, 1029, 1063, 1052, 1072, 2222];
    private $get_params = [
        'limit' => 1,
        'offset'=>0,
        'status'=>'published',
        'region_id'=>0,
        'fields'=>'params.region,params.city'
    ];

    protected $fillable = ['count', 'city_id'];

    public function UpdateOfferCount()
    {
        $client = new Client();

        foreach($this->region_id_list as $regionID)
        {
            $city = City::where('region_id', $regionID)->first();
            if($city)
            {
                $cityID =$city->getAttribute('id');
                $this->get_params['region_id'] = $regionID;
                $response = $client->request('GET', $this->base_url, ['query' => $this->get_params]);
                $responseArray = json_decode($response->getBody()->getContents(), true);

                if (isset($responseArray['metadata']['resultset']['count']))
                {
                    $count = $responseArray['metadata']['resultset']['count'];
                    $offerCount = new OfferCount(['city_id' => $cityID, 'count' => $count]);
                    $offerCount->save();
                }
            }
        }
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }
}
