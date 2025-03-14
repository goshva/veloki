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
}