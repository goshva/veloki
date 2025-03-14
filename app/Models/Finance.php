<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Finance extends Model
{
    protected $fillable = ['date', 'daily_result', 'balance', 'notes'];

    protected $casts = [
        'date' => 'date',
        'daily_result' => 'float',
        'balance' => 'float',
    ];

    public static function updateDailyFinance($date)
    {
        $date = Carbon::parse($date)->startOfDay();

        // Calculate daily revenue from completed orders
        $dailyResult = Order::whereDate('end_time', $date)
            ->where('status', 'completed')
            ->sum('total_price');

        // Find or create the finance record
        $finance = self::firstOrCreate(
            ['date' => $date],
            ['daily_result' => 0, 'balance' => 0, 'notes' => 'No completed orders']
        );

        // Update daily result
        $finance->daily_result = $dailyResult;
        $finance->notes = $dailyResult > 0 ? "Generated from completed orders" : "No completed orders";

        // Recalculate balance
        $previousFinance = self::where('date', '<', $date)
            ->orderBy('date', 'desc')
            ->first();
        $previousBalance = $previousFinance ? $previousFinance->balance : 0;
        $finance->balance = $previousBalance + $dailyResult;
        $finance->save();

        // Recalculate subsequent balances
        $subsequentFinances = self::where('date', '>', $date)->orderBy('date')->get();
        $runningBalance = $finance->balance;
        foreach ($subsequentFinances as $subsequent) {
            $dailyResult = Order::whereDate('end_time', $subsequent->date)
                ->where('status', 'completed')
                ->sum('total_price');
            $subsequent->daily_result = $dailyResult;
            $subsequent->notes = $dailyResult > 0 ? "Generated from completed orders" : "No completed orders";
            $runningBalance += $dailyResult;
            $subsequent->balance = $runningBalance;
            $subsequent->save();
        }

        return $finance;
    }
}