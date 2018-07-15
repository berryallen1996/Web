<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;

class CountryController extends Controller
{
    public function getStates(Country $country)
    {
        return $country->states()->select('id', 'states')->get();
    }
}