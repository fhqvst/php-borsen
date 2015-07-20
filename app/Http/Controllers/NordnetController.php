<?php

namespace App\Http\Controllers;

use App\Services\Nordnet;
use App\Instrument;
use App\Events\InstrumentUpdated;
use Event;

class NordnetController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getSynchronize()
    {
        $nordnet = new Nordnet('fhqvst', 'ib2KRor4');
        $results = json_decode($nordnet->getInstrumentList(16314763));

        foreach($results as $result) {
            print_r($result);
            if(!Instrument::firstOrCreate([
                'symbol' => $result->symbol,
                'name' => $result->name,
                'label' => $result->name,
                'nordnet_id' => $result->instrument_id,
                'market_id' => property_exists($result, 'tradables') ? $result->tradables[0]->market_id : 0,
                'sector' => property_exists($result, 'sector') ? $result->sector : '',
                'isin_code' => property_exists($result, 'isin_code') ? $result->isin_code : ''
            ])) {
                return "An error occured";
            };
        }
        return "OK";

    }

    public function getUpdate($instrument_id) {
        $nordnet = new Nordnet('fhqvst', 'ib2KRor4');

        $instrument = Instrument::where('nordnet_id', $instrument_id)->first();
        print_r($instrument);
        $updated = $nordnet->getInstrument($instrument_id);

        if(is_array($updated)) {
            $updated = $updated[0];
        }

        if(isset($instrument) && $instrument->update(array(
                'symbol' => $updated->symbol,
                'name' => $updated->name,
                'label' => $updated->name,
                'nordnet_id' => $updated->instrument_id,
                'market_id' => property_exists($updated, 'tradables') ? $updated->tradables[0]->market_id : 0,
                'sector' => property_exists($updated, 'sector') ? $updated->sector : '',
                'isin_code' => property_exists($updated, 'isin_code') ? $updated->isin_code : ''
            )
        )) {
            $instrument = Instrument::where('nordnet_id', $instrument_id)->first();
            return json_encode($instrument);
        }
        return "{}";
    }

}