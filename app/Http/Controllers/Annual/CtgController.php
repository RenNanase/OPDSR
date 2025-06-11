<?php

namespace App\Http\Controllers\Annual;

use App\Http\Controllers\Controller;
use App\Models\CtgRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CtgController extends Controller
{
    public function index()
    {
        try {
            $date = request('date', Carbon::today()->format('Y-m-d'));
            $record = CtgRecord::whereDate('date', $date)->first();

            // Debug information
            Log::info('CTG Index Query', [
                'date' => $date,
                'record_exists' => $record ? true : false,
                'record' => $record ? $record->toArray() : null
            ]);

            return view('annual.ctg.index', compact('record', 'date'));
        } catch (\Exception $e) {
            Log::error('CTG Index Error: ' . $e->getMessage());
            return redirect()->route('annual.ctg.index')
                ->with('error', 'Failed to load CTG records. Please try again.');
        }
    }

    public function daily()
    {
        try {
            $date = request('date', Carbon::today()->format('Y-m-d'));
            $record = CtgRecord::whereDate('date', $date)->first();

            return view('annual.ctg.daily', compact('record', 'date'));
        } catch (\Exception $e) {
            Log::error('CTG Daily View Error: ' . $e->getMessage());
            return redirect()->route('annual.ctg.index')
                ->with('error', 'Failed to load CTG daily records. Please try again.');
        }
    }

    public function monthly()
    {
        $month = request('month', Carbon::now()->month);
        $year = request('year', Carbon::now()->year);

        // Create start and end dates for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Fetch records between start and end dates
        $records = CtgRecord::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date')
            ->get();

        // Debug information
        \Log::info('CTG Monthly Records Query', [
            'month' => $month,
            'year' => $year,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'recordsCount' => $records->count(),
            'records' => $records->toArray()
        ]);

        $totalGeetha = $records->sum('dr_geetha_count');
        $totalJoseph = $records->sum('dr_joseph_count');
        $totalSutha = $records->sum('dr_sutha_count');
        $totalRamesh = $records->sum('dr_ramesh_count');

        return view('annual.ctg.monthly', compact('records', 'month', 'year', 'totalGeetha', 'totalJoseph', 'totalSutha', 'totalRamesh'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'dr_geetha_count' => 'required|integer|min:0',
                'dr_joseph_count' => 'required|integer|min:0',
                'dr_sutha_count' => 'required|integer|min:0',
                'dr_ramesh_count' => 'required|integer|min:0',
            ]);

            $validated['user_name'] =Auth::user()->name;

            CtgRecord::updateOrCreate(
                ['date' => $validated['date']],
                $validated
            );

            return redirect()->route('annual.ctg.index')
                ->with('status', 'CTG record has been saved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('annual.ctg.index')
                ->with('error', 'Failed to save CTG record. Please try again.');
        }
    }

    public function export(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Create start and end dates for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Fetch records between start and end dates
        $records = CtgRecord::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date')
            ->get();

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'CTG Monthly Report - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)));
        $sheet->setCellValue('A3', 'Date');
        $sheet->setCellValue('B3', 'Dr. Geetha');
        $sheet->setCellValue('C3', 'Dr. Joseph');
        $sheet->setCellValue('D3', 'Dr. Sutha');
        $sheet->setCellValue('E3', 'Dr. Ramesh');
        $sheet->setCellValue('F3', 'Total');

        // Add data
        $row = 4;
        $monthlyTotals = [
            'geetha' => 0,
            'joseph' => 0,
            'sutha' => 0,
            'ramesh' => 0,
            'total' => 0
        ];

        for ($day = 1; $day <= $endDate->daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $record = $records->first(function($record) use ($date) {
                return Carbon::parse($record->date)->format('Y-m-d') === $date->format('Y-m-d');
            });

            $sheet->setCellValue('A' . $row, $day);
            $sheet->setCellValue('B' . $row, $record ? $record->dr_geetha_count : 0);
            $sheet->setCellValue('C' . $row, $record ? $record->dr_joseph_count : 0);
            $sheet->setCellValue('D' . $row, $record ? $record->dr_sutha_count : 0);
            $sheet->setCellValue('E' . $row, $record ? $record->dr_ramesh_count : 0);

            $dayTotal = $record ? ($record->dr_geetha_count + $record->dr_joseph_count + $record->dr_sutha_count + $record->dr_ramesh_count) : 0;
            $sheet->setCellValue('F' . $row, $dayTotal);

            // Update monthly totals
            $monthlyTotals['geetha'] += $record ? $record->dr_geetha_count : 0;
            $monthlyTotals['joseph'] += $record ? $record->dr_joseph_count : 0;
            $monthlyTotals['sutha'] += $record ? $record->dr_sutha_count : 0;
            $monthlyTotals['ramesh'] += $record ? $record->dr_ramesh_count : 0;
            $monthlyTotals['total'] += $dayTotal;

            $row++;
        }

        // Add monthly totals
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Monthly Total');
        $sheet->setCellValue('B' . $row, $monthlyTotals['geetha']);
        $sheet->setCellValue('C' . $row, $monthlyTotals['joseph']);
        $sheet->setCellValue('D' . $row, $monthlyTotals['sutha']);
        $sheet->setCellValue('E' . $row, $monthlyTotals['ramesh']);
        $sheet->setCellValue('F' . $row, $monthlyTotals['total']);

        // Style the header
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'CTG_Report_' . date('F_Y', mktime(0, 0, 0, $month, 1, $year)) . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Save file to PHP output
        $writer->save('php://output');
        exit;
    }

    public function edit(CtgRecord $record)
    {
        return view('annual.ctg.edit', compact('record'));
    }

    public function update(Request $request, CtgRecord $record)
    {
        try {
            $validated = $request->validate([
                'dr_geetha_count' => 'required|integer|min:0',
                'dr_joseph_count' => 'required|integer|min:0',
                'dr_sutha_count' => 'required|integer|min:0',
                'dr_ramesh_count' => 'required|integer|min:0',
            ]);

            $validated['user_name'] = Auth::user()->name;

            $record->update($validated);

            return redirect()->route('annual.ctg.daily', ['date' => $record->date])
                ->with('status', 'CTG record has been updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('annual.ctg.daily', ['date' => $record->date])
                ->with('error', 'Failed to update CTG record. Please try again.');
        }
    }

    public function checkDate($date)
    {
        try {
            $record = CtgRecord::whereDate('date', $date)->first();

            if ($record) {
                return response()->json([
                    'exists' => true,
                    'user_name' => $record->user_name,
                    'updated_at' => $record->updated_at
                ]);
            }

            return response()->json([
                'exists' => false
            ]);
        } catch (\Exception $e) {
            Log::error('CTG Date Check Error: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'error' => 'Failed to check date'
            ], 500);
        }
    }
}
