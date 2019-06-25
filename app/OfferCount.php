<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;

class OfferCount extends Model
{
    protected $fillable = ['count', 'city_id'];

    public static function updateOfferCount()
    {
        $externalApiRequest = new ExternalApiRequest();
        $regionIDList = $externalApiRequest->getRegionIDList();

        foreach($regionIDList as $regionID)
        {
            $city = City::where('region_id', $regionID)->first();
            if($city)
            {
                $cityID = $city->getAttribute('id');
                if ($count = $externalApiRequest->getCityOfferCount($regionID))
                {
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
