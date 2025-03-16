<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BikeRentalCalculator;
use Carbon\Carbon;

class RentalCalculatorController extends Controller
{
    protected $calculator;

    public function __construct(BikeRentalCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'bike_group' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

        $price = $this->calculator->calculatePrice(
            $request->bike_group,
            Carbon::parse($request->start_time),
            Carbon::parse($request->end_time)
        );

        return response()->json(['price' => $price]);
    }
}