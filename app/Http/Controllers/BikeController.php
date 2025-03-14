<?php
namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

class BikeController extends Controller
{
    public function index()
    {
        $bikes = Bike::all();
        return view('bikes.index', compact('bikes'));
    }

    public function create()
    {
        return view('bikes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bikes,name',
            'description' => 'nullable|string',
            'group' => 'required|in:mechanical,electric',
        ]);

        Bike::create($request->all());
        return redirect()->route('bikes.index')->with('success', 'Bike created successfully.');
    }

    public function show(Bike $bike)
    {
        return view('bikes.show', compact('bike'));
    }

    public function edit(Bike $bike)
    {
        return view('bikes.edit', compact('bike'));
    }

    public function update(Request $request, Bike $bike)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bikes,name,' . $bike->id,
            'description' => 'nullable|string',
            'group' => 'required|in:mechanical,electric',
        ]);

        $bike->update($request->all());
        return redirect()->route('bikes.index')->with('success', 'Bike updated successfully.');
    }

    public function destroy(Bike $bike)
    {
        $bike->delete();
        return redirect()->route('bikes.index')->with('success', 'Bike deleted successfully.');
    }
}