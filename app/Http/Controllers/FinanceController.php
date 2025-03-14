<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Finance;
use App\Models\Order;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $finances = Finance::orderBy('date', 'desc')->get();
        if ($finances->isEmpty()) {
            // If no finance records exist, seed from orders
            $this->seedFromOrders();
            $finances = Finance::orderBy('date', 'desc')->get();
        }
        return view('finances.index', compact('finances'));
    }

    public function create()
    {
        return view('finances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:finances,date',
        ]);

        $dailyResult = Order::whereDate('end_time', $request->date)
            ->where('status', 'completed')
            ->sum('total_price');

        $previousFinance = Finance::where('date', '<', $request->date)
            ->orderBy('date', 'desc')
            ->first();
        $previousBalance = $previousFinance ? $previousFinance->balance : 0;

        $finance = Finance::create([
            'date' => $request->date,
            'daily_result' => $dailyResult,
            'balance' => $previousBalance + $dailyResult,
            'notes' => $dailyResult > 0 ? "Generated from completed orders" : "No completed orders",
        ]);

        return redirect()->route('finances.index')->with('success', 'Finance record created successfully.');
    }

    public function show(Finance $finance)
    {
        return view('finances.show', compact('finance'));
    }

    public function edit(Finance $finance)
    {
        return view('finances.edit', compact('finance'));
    }

    public function update(Request $request, Finance $finance)
    {
        $request->validate([
            'date' => 'required|date|unique:finances,date,' . $finance->id,
        ]);

        $dailyResult = Order::whereDate('end_time', $request->date)
            ->where('status', 'completed')
            ->sum('total_price');

        $previousFinance = Finance::where('date', '<', $request->date)
            ->orderBy('date', 'desc')
            ->first();
        $previousBalance = $previousFinance ? $previousFinance->balance : 0;

        $finance->update([
            'date' => $request->date,
            'daily_result' => $dailyResult,
            'balance' => $previousBalance + $dailyResult,
            'notes' => $dailyResult > 0 ? "Generated from completed orders" : "No completed orders",
        ]);

        $this->recalculateBalances($finance->date);

        return redirect()->route('finances.index')->with('success', 'Finance record updated successfully.');
    }

    public function destroy(Finance $finance)
    {
        $nextDate = Finance::where('date', '>', $finance->date)->min('date');
        $finance->delete();
        if ($nextDate) {
            $this->recalculateBalances($nextDate);
        }
        return redirect()->route('finances.index')->with('success', 'Finance record deleted successfully.');
    }

    private function recalculateBalances($startDate)
    {
        $finances = Finance::where('date', '>=', $startDate)
            ->orderBy('date')
            ->get();

        $previousBalance = Finance::where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->first()?->balance ?? 0;

        foreach ($finances as $finance) {
            $dailyResult = Order::whereDate('end_time', $finance->date)
                ->where('status', 'completed')
                ->sum('total_price');
            $finance->daily_result = $dailyResult;
            $finance->notes = $dailyResult > 0 ? "Generated from completed orders" : "No completed orders";
            $previousBalance += $dailyResult;
            $finance->balance = $previousBalance;
            $finance->save();
        }
    }

    private function seedFromOrders()
    {
        $earliestDate = Order::where('status', 'completed')->min('end_time');
        $latestDate = Order::where('status', 'completed')->max('end_time');

        if (!$earliestDate || !$latestDate) {
            return;
        }

        $startDate = Carbon::parse($earliestDate)->startOfDay();
        $endDate = Carbon::parse($latestDate)->startOfDay();
        $currentDate = $startDate->copy();
        $previousBalance = 0;

        while ($currentDate <= $endDate) {
            $dailyResult = Order::whereDate('end_time', $currentDate)
                ->where('status', 'completed')
                ->sum('total_price');

            Finance::create([
                'date' => $currentDate->toDateString(),
                'daily_result' => $dailyResult,
                'balance' => $previousBalance + $dailyResult,
                'notes' => $dailyResult > 0 ? "Generated from completed orders" : "No completed orders",
            ]);

            $previousBalance += $dailyResult;
            $currentDate->addDay();
        }
    }
}