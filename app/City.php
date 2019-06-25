<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['region_id', 'name'];

    public static function loadCities()
    {

        $externalApiRequest = new ExternalApiRequest();
        $regionIDList = $externalApiRequest->getRegionIDList();

        foreach($regionIDList as $regionID)
        {
            if($name = $externalApiRequest->getCityName($regionID))
            {
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
