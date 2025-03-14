<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Bike;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class OrderController extends Controller
{
public function index()
{
    // Get today's date
    $today = now()->toDateString();

    // Fetch orders for today and sort by start time
    $orders = Order::with('client', 'bikes')
        ->whereDate('start_time', $today) // Assuming 'start_time' is the column for the order start time
        ->orderBy('start_time', 'desc')
        ->get();

    // Recalculate price for active or completed orders
    foreach ($orders as $order) {
        if ($order->status === 'active' || $order->status === 'completed') {
            $order->calculatePrice();
        }
    }

    return view('orders.index', compact('orders'));
}

    public function create()
    {
        $clients = Client::all();
        $bikes = Bike::all();
        return view('orders.create', compact('clients', 'bikes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:255',
            'start_time' => 'required|date',
            'bike_ids' => 'required|array',
            'bike_ids.*' => 'exists:bikes,id',
            'status' => 'required|in:pending,active,completed,cancelled',
            'acceptor' => 'nullable|in:cash,cardR,cardM',
        ]);
    
        // Find or create client based on phone
        $client = Client::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->client_name ?: null]
        );
    
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => $request->start_time,
            'status' => $request->status,
            'acceptor' => $request->acceptor,
        ]);
        $order->bikes()->attach($request->bike_ids);
    
        if ($order->status === 'active' || $order->status === 'completed') {
            $order->calculatePrice();
        }
        if ($order->status === 'completed' && $order->end_time) {
            Finance::updateDailyFinance($order->end_time);
        }
    
        return redirect()->route('orders.index')->with('success', 'Заказ создан успешно.');
    }
    
    public function update(Request $request, Order $order)
    {
        // ... (previous code remains the same)
        $order->update($request->only(['client_id', 'start_time', 'end_time', 'status', 'acceptor']));
        $order->bikes()->sync($request->bike_ids);
    
        if ($order->status === 'active' || $order->status === 'completed') {
            $order->calculatePrice();
        }
        if ($order->status === 'completed' && $order->end_time) {
            Finance::updateDailyFinance($order->end_time);
        }
    
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function show(Order $order)
    {
        $order->load('client', 'bikes');
        $order->calculatePrice(); // Ensure price is up-to-date
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $clients = Client::all();
        $bikes = Bike::all();
        $order->load('bikes'); // Ensure bikes are loaded
        $totalPrice = $order->total_price ?? $order->calculatePrice(); // Use existing or recalculate
    
        return view('orders.edit', compact('order', 'clients', 'bikes', 'totalPrice'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
    public function searchClients(Request $request)
    {
        $query = $request->input('query');
        Log::info('Search clients request', ['query' => $query, 'url' => $request->fullUrl()]);

        $clients = Client::where('phone', 'LIKE', "%$query%")
            ->select('id', 'phone', 'name')
            ->limit(10)
            ->get();

        return response()->json($clients);
    }
}