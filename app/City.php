<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['region_id', 'name'];
    private $client;

    private function loadClient()
    {
        $this->client = new N1ApiClient();
    }

    public function loadCities()
    {
        $this->loadClient();
        $regionIDList =  $this->client->getRegionIDList();

        foreach($regionIDList as $regionID) {

            if($name =  $this->client->getCityName($regionID)) {
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
