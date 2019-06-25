<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;

class OfferCount extends Model
{
    protected $fillable = ['count', 'city_id'];
    private $client;

    private function loadClient()
    {
        $this->client = new N1ApiClient();
    }

    public function updateOfferCount()
    {
        $this->loadClient();
        $regionIDList = $this->client->getRegionIDList();

        foreach($regionIDList as $regionID) {
            $city = City::where('region_id', $regionID)->first();
            if($city) {
                $cityID = $city->getAttribute('id');
                if ($count = $this->client->getCityOfferCount($regionID)) {
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
