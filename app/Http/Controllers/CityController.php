<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with('latestOfferCount')->get();
        return view('index', compact('cities'));
    }

    public function offers(Request $request)
    {
        $city = City::find($request->get('city_id'));
        return json_encode($city->allOfferCount()->get());
    }
}
