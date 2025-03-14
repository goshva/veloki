<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Verify secret token
        $secret = "your-22secret-t0ken";
        if ($request->input('secret') !== $secret) {
            return response()->json(['error' => 'Invalid secret token'], 403);
        }

        // Log the webhook event
        $webhookLog = WebhookLog::create([
            'sheet_name' => $request->input('sheetName'),
            'range' => $request->input('range'),
            'values' => $request->input('values'),
            'timestamp' => $request->input('timestamp'),
            'user' => $request->input('user'),
            'secret' => $request->input('secret'),
        ]);

        // Parse and store rental data
        $this->processWebhookData($request->input('values'), $request->input('sheetName'));

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }

    private function processWebhookData($values, $sheetName)
    {
        // Assuming the first row is headers and subsequent rows are data
        foreach ($values as $index => $row) {
            if ($index === 0) continue; // Skip header row

            // Map the row data to the Rental model
            $rentalData = [
                'name' => $row[0] ?? null,
                'phone' => $row[1] ?? null,
                'start_time' => $this->parseTime($row[2] ?? null),
                'end_time' => $this->parseTime($row[3] ?? null),
                'amount' => $this->parseDecimal($row[4] ?? null),
                'payment_method' => $row[5] ?? null,
                'net_profit' => $this->parseDecimal($row[6] ?? null),
                'cash' => $this->parseDecimal($row[7] ?? null),
                'card_sasha' => $this->parseDecimal($row[8] ?? null),
                'card_misha' => $this->parseDecimal($row[9] ?? null),
                'card_roma' => $this->parseDecimal($row[10] ?? null),
                'entry_date' => $this->parseDateFromSheetName($sheetName),
            ];

            // Create or update rental entry
            Rental::updateOrCreate(
                ['phone' => $rentalData['phone'], 'start_time' => $rentalData['start_time']],
                $rentalData
            );
        }
    }

    private function parseTime($time)
    {
        return $time ? \Carbon\Carbon::parse($time)->format('H:i:s') : null;
    }

    private function parseDecimal($value)
    {
        return is_numeric($value) ? floatval($value) : null;
    }

    private function parseDateFromSheetName($sheetName)
    {
        // Extract date from sheet name if applicable (e.g., "2024-06-01")
        if (preg_match('/(\d{4}-\d{2}-\d{2})/', $sheetName, $matches)) {
            return $matches[1];
        }
        return now()->toDateString(); // Default to today if no date found
    }
}