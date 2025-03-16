<?php
namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::all();
        return view('prices.index', compact('prices'));
    }

    public function create()
    {
        return view('prices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bike_group' => 'required|in:mechanical,electric',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        Price::create($request->all());
        return redirect()->route('prices.index')->with('success', 'Price created successfully.');
    }

    public function show(Price $price)
    {
        return view('prices.show', compact('price'));
    }

    public function edit(Price $price)
    {
        return view('prices.edit', compact('price'));
    }

    public function update(Request $request, Price $price)
    {
        $request->validate([
            'bike_group' => 'required|in:mechanical,electric',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        $price->update($request->all());
        return redirect()->route('prices.index')->with('success', 'Price updated successfully.');
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return redirect()->route('prices.index')->with('success', 'Price deleted successfully.');
    }
    public function calculateRental(Request $request)
    {
        $request->validate([
            'mechanical_bikes' => 'required|integer|min:0',
            'electric_bikes' => 'required|integer|min:0',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
        ]);

        $calculator = new BikeRentalCalculator();
        
        $totalPrice = $calculator->calculateTotalPrice(
            $request->mechanical_bikes,
            $request->electric_bikes,
            new DateTime($request->start_time),
            new DateTime($request->end_time)
        );

        return response()->json([
            'success' => true,
            'total_price' => $totalPrice,
            'formatted_price' => number_format($totalPrice, 2),
        ]);
    }

}