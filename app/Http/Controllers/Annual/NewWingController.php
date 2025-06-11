<?php

namespace App\Http\Controllers\Annual;

use App\Http\Controllers\Controller; // Ensure this is correct
use Illuminate\Http\Request;
use App\Models\MedicalProcedureNW;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NewWingController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $procedures = MedicalProcedureNW::where('date', $date)->first();

        return view('annual.new-wing', compact('procedures', 'date'));
    }

    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $procedures = MedicalProcedureNW::where('date', $date)->first();

        return view('annual.new-wing-daily', compact('procedures', 'date'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'injection_vaccine' => 'required|integer|min:0',
            'iv_medication' => 'required|integer|min:0',
            'urea_blood_test' => 'required|integer|min:0',
            'venepuncture' => 'required|integer|min:0',
            'iv_cannulation' => 'required|integer|min:0',
            'swab_cs_nose_oral' => 'required|integer|min:0',
            'dressing' => 'required|integer|min:0',
            'ecg_12_led' => 'required|integer|min:0',
            'urinary_catheterization' => 'required|integer|min:0',
            'ng_tube_insertion' => 'required|integer|min:0',
            'nebulization' => 'required|integer|min:0',
        ]);

        try {
            // Log the incoming date for debugging
            Log::info('Incoming date from form: ' . $validated['date']);

            // Create or update the record with the selected date
            $procedures = new MedicalProcedureNW();
            $procedures->date = $validated['date'];
            $procedures->injection_vaccine = $validated['injection_vaccine'];
            $procedures->iv_medication = $validated['iv_medication'];
            $procedures->urea_blood_test = $validated['urea_blood_test'];
            $procedures->venepuncture = $validated['venepuncture'];
            $procedures->iv_cannulation = $validated['iv_cannulation'];
            $procedures->swab_cs_nose_oral = $validated['swab_cs_nose_oral'];
            $procedures->dressing = $validated['dressing'];
            $procedures->ecg_12_led = $validated['ecg_12_led'];
            $procedures->urinary_catheterization = $validated['urinary_catheterization'];
            $procedures->ng_tube_insertion = $validated['ng_tube_insertion'];
            $procedures->nebulization = $validated['nebulization'];
            $procedures->user_name = Auth::user()->name;
            $procedures->save();

            return redirect()->route('annual.new-wing.index', ['date' => $validated['date']])
                            ->with('success', 'Medical procedures saved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to save medical procedures: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Failed to save medical procedures. Please try again.');
        }
    }

    public function monthly(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $endDate->day;

        // Get daily records
        $dailyRecords = MedicalProcedureNW::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->keyBy(function ($record) {
                return $record->date->format('Y-m-d');
            });

        // Calculate totals
        $totals = MedicalProcedureNW::whereBetween('date', [$startDate, $endDate])
            ->select([
                DB::raw('SUM(injection_vaccine) as total_injection_vaccine'),
                DB::raw('SUM(iv_medication) as total_iv_medication'),
                DB::raw('SUM(urea_blood_test) as total_urea_blood_test'),
                DB::raw('SUM(venepuncture) as total_venepuncture'),
                DB::raw('SUM(iv_cannulation) as total_iv_cannulation'),
                DB::raw('SUM(swab_cs_nose_oral) as total_swab_cs_nose_oral'),
                DB::raw('SUM(dressing) as total_dressing'),
                DB::raw('SUM(ecg_12_led) as total_ecg_12_led'),
                DB::raw('SUM(urinary_catheterization) as total_urinary_catheterization'),
                DB::raw('SUM(ng_tube_insertion) as total_ng_tube_insertion'),
                DB::raw('SUM(nebulization) as total_nebulization'),
            ])
            ->first();

        // Create array of all days in month
        $daysInMonthArray = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $daysInMonthArray[$dateStr] = $dailyRecords[$dateStr] ?? null;
            $currentDate->addDay();
        }

        return view('annual.new-wing-monthly', compact('daysInMonthArray', 'daysInMonth', 'selectedMonth', 'selectedYear', 'totals'));
    }

    public function export(Request $request)
    {
        try {
            $selectedMonth = $request->input('month', Carbon::now()->month);
            $selectedYear = $request->input('year', Carbon::now()->year);

            $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $daysInMonth = $endDate->day;

            // Get daily records
            $dailyRecords = MedicalProcedureNW::whereBetween('date', [$startDate, $endDate])
                ->get()
                ->keyBy(function ($record) {
                    return $record->date->format('Y-m-d');
                });

            // Calculate totals for the monthly statistics section
            $totals = MedicalProcedureNW::whereBetween('date', [$startDate, $endDate])
                ->select([
                    DB::raw('SUM(injection_vaccine) as total_injection_vaccine'),
                    DB::raw('SUM(iv_medication) as total_iv_medication'),
                    DB::raw('SUM(urea_blood_test) as total_urea_blood_test'),
                    DB::raw('SUM(venepuncture) as total_venepuncture'),
                    DB::raw('SUM(iv_cannulation) as total_iv_cannulation'),
                    DB::raw('SUM(swab_cs_nose_oral) as total_swab_cs_nose_oral'),
                    DB::raw('SUM(dressing) as total_dressing'),
                    DB::raw('SUM(ecg_12_led) as total_ecg_12_led'),
                    DB::raw('SUM(urinary_catheterization) as total_urinary_catheterization'),
                    DB::raw('SUM(ng_tube_insertion) as total_ng_tube_insertion'),
                    DB::raw('SUM(nebulization) as total_nebulization'),
                ])
                ->first();

            // Create new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Define procedures with their display names and corresponding database fields
            $procedures = [
                'INJECTION VACCINE' => 'injection_vaccine',
                'IV MEDICATION' => 'iv_medication',
                'UREA BLOOD TEST (UBT)' => 'urea_blood_test',
                'VENEPUNCTURE' => 'venepuncture',
                'IV CANNULATION' => 'iv_cannulation',
                'SWAB C&S / NOSE / ORAL' => 'swab_cs_nose_oral',
                'DRESSING' => 'dressing',
                '12 LED ECG' => 'ecg_12_led',
                'URINARY CATHETERIZATION' => 'urinary_catheterization',
                'NG TUBE INSERTION' => 'ng_tube_insertion',
                'NEBULIZATION' => 'nebulization'
            ];

            // Calculate the last column for merging
            $lastColumnIndex = 1 + $daysInMonth + 1;
            $lastColumnLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);

            // Set title
            $sheet->setCellValue('A1', 'New Wing Monthly Medical Procedures - ' . date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)));
            $sheet->mergeCells("A1:{$lastColumnLetter}1");
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Set headers (Row 3)
            $sheet->setCellValue('A3', 'PROCEDURE');
            $sheet->getStyle('A3')->getFont()->setBold(true);

            // Set days in row 3
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $columnForDay = Coordinate::stringFromColumnIndex($day + 1);
                $sheet->setCellValue($columnForDay . '3', $day);
                $sheet->getStyle($columnForDay . '3')->getFont()->setBold(true);
                $sheet->getStyle($columnForDay . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Add Total column
            $totalColLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);
            $sheet->setCellValue($totalColLetter . '3', 'Total');
            $sheet->getStyle($totalColLetter . '3')->getFont()->setBold(true);
            $sheet->getStyle($totalColLetter . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Add data
            $row = 4;
            foreach ($procedures as $procedureName => $procedureField) {
                $sheet->setCellValue('A' . $row, $procedureName);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);

                $procedureTotal = 0;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($selectedYear, $selectedMonth, $day)->format('Y-m-d');
                    $recordForThisDay = $dailyRecords[$date] ?? null;
                    $value = $recordForThisDay ? $recordForThisDay->$procedureField : 0;

                    $columnForValue = Coordinate::stringFromColumnIndex($day + 1);
                    $sheet->setCellValue($columnForValue . $row, $value);
                    $sheet->getStyle($columnForValue . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $procedureTotal += $value;
                }

                // Add total for this procedure
                $sheet->setCellValue($totalColLetter . $row, $procedureTotal);
                $sheet->getStyle($totalColLetter . $row)->getFont()->setBold(true);
                $sheet->getStyle($totalColLetter . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row++;
            }

            // Add monthly statistics section
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Monthly Statistics');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
            $row += 2;

            // Add total procedures
            $sheet->setCellValue('A' . $row, 'Total Procedures');
            $sheet->setCellValue('B' . $row, $totals->total_injection_vaccine + $totals->total_iv_medication +
                $totals->total_urea_blood_test + $totals->total_venepuncture + $totals->total_iv_cannulation +
                $totals->total_swab_cs_nose_oral + $totals->total_dressing + $totals->total_ecg_12_led +
                $totals->total_urinary_catheterization + $totals->total_ng_tube_insertion + $totals->total_nebulization);
            $row += 2;

            // Add breakdown by category
            $categories = [
                'Injection & Medication' => [
                    'Injection Vaccine' => $totals->total_injection_vaccine,
                    'IV Medication' => $totals->total_iv_medication,
                    'Nebulization' => $totals->total_nebulization
                ],
                'Tests & Monitoring' => [
                    'Urea Blood Test (UBT)' => $totals->total_urea_blood_test,
                    'Venepuncture' => $totals->total_venepuncture,
                    '12 LED ECG' => $totals->total_ecg_12_led
                ],
                'Procedures & Care' => [
                    'IV Cannulation' => $totals->total_iv_cannulation,
                    'SWAB C&S / NOSE / ORAL' => $totals->total_swab_cs_nose_oral,
                    'Dressing' => $totals->total_dressing
                ],
                'Tube Insertions' => [
                    'Urinary Catheterization' => $totals->total_urinary_catheterization,
                    'NG Tube Insertion' => $totals->total_ng_tube_insertion
                ]
            ];

            foreach ($categories as $category => $proceduresInCat) {
                $sheet->setCellValue('A' . $row, $category);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;

                foreach ($proceduresInCat as $procedure => $total) {
                    $sheet->setCellValue('A' . $row, $procedure);
                    $sheet->setCellValue('B' . $row, $total);
                    $row++;
                }
                $row++;
            }

            // Auto-size columns
            for ($i = 1; $i <= $lastColumnIndex; $i++) {
                $columnLetter = Coordinate::stringFromColumnIndex($i);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            // Create the Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = 'new_wing_monthly_procedures_' . date('F_Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) . '.xlsx';

            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Save to output
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export data. Please try again.');
        }
    }

    public function edit($date)
    {
        $procedures = MedicalProcedureNW::where('date', $date)->firstOrFail();
        return view('annual.new-wing-edit', compact('procedures', 'date'));
    }

    public function update(Request $request, $date)
    {
        $validated = $request->validate([
            'injection_vaccine' => 'required|integer|min:0',
            'iv_medication' => 'required|integer|min:0',
            'urea_blood_test' => 'required|integer|min:0',
            'venepuncture' => 'required|integer|min:0',
            'iv_cannulation' => 'required|integer|min:0',
            'swab_cs_nose_oral' => 'required|integer|min:0',
            'dressing' => 'required|integer|min:0',
            'ecg_12_led' => 'required|integer|min:0',
            'urinary_catheterization' => 'required|integer|min:0',
            'ng_tube_insertion' => 'required|integer|min:0',
            'nebulization' => 'required|integer|min:0',
        ]);

        try {
            $procedures = MedicalProcedureNW::where('date', $date)->firstOrFail();

            $procedures->injection_vaccine = $validated['injection_vaccine'];
            $procedures->iv_medication = $validated['iv_medication'];
            $procedures->urea_blood_test = $validated['urea_blood_test'];
            $procedures->venepuncture = $validated['venepuncture'];
            $procedures->iv_cannulation = $validated['iv_cannulation'];
            $procedures->swab_cs_nose_oral = $validated['swab_cs_nose_oral'];
            $procedures->dressing = $validated['dressing'];
            $procedures->ecg_12_led = $validated['ecg_12_led'];
            $procedures->urinary_catheterization = $validated['urinary_catheterization'];
            $procedures->ng_tube_insertion = $validated['ng_tube_insertion'];
            $procedures->nebulization = $validated['nebulization'];
            $procedures->user_name = Auth::user()->name;
            $procedures->save();

            return redirect()->route('annual.new-wing.daily', ['date' => $date])
                            ->with('success', 'Medical procedures updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update medical procedures: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Failed to update medical procedures. Please try again.');
        }
    }
}

