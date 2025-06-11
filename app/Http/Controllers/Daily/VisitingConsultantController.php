<?php

namespace App\Http\Controllers\Daily; // Adjust namespace if your controller is not in 'Daily' subfolder

use App\Http\Controllers\Controller; // Don't forget to extend Controller
use Illuminate\Http\Request;
use App\Models\VisitingConsultant; // The model for consultant names and suites
use App\Models\VisitingConsultantLog; // The model for daily log entries
use App\Models\VisitingConsultantLogForeigner; // The model for foreigner details
use App\Models\VisitingConsultantPatient; // The model for patient statistics
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // For handling dates/times
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VisitingConsultantController extends Controller
{

    public function index()
    {
        $selectedDate = request('date', now()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Get all logs for the selected date with relationships
        $loggedEntries = VisitingConsultantLog::with(['patients', 'foreignerPatients'])
            ->whereDate('date', $date)
            ->orderBy('created_at', 'desc')
                                                ->get();

        // Get all consultants
        $allConsultants = VisitingConsultant::all();

        // Find missing consultants
        $loggedConsultantIds = $loggedEntries->pluck('consultant_name')->unique();
        $missingConsultants = $allConsultants->filter(function($consultant) use ($loggedConsultantIds) {
            return !in_array($consultant->name, $loggedConsultantIds->toArray());
        });

        // Calculate daily statistics
        $dailyStats = [
            'total_patients' => 0,
            'chinese_count' => 0,
            'malay_count' => 0,
            'india_count' => 0,
            'kdms_count' => 0,
            'others_count' => 0,
            'male_count' => 0,
            'female_count' => 0,
            'new_male_count_vs' => 0,
            'new_female_count_vs' => 0,
            'foreigner_count' => 0,
            'foreigner_male_count' => 0,
            'foreigner_female_count' => 0
        ];

        foreach ($loggedEntries as $log) {
            // Get the patient record
            $patient = $log->patients->first();
            if ($patient) {
                $dailyStats['chinese_count'] += $patient->chinese_count ?? 0;
                $dailyStats['malay_count'] += $patient->malay_count ?? 0;
                $dailyStats['india_count'] += $patient->india_count ?? 0;
                $dailyStats['kdms_count'] += $patient->kdms_count ?? 0;
                $dailyStats['others_count'] += $patient->others_count ?? 0;
                $dailyStats['male_count'] += $patient->male_count ?? 0;
                $dailyStats['female_count'] += $patient->female_count ?? 0;
                $dailyStats['new_male_count_vs'] += $patient->new_male_count_vs ?? 0;
                $dailyStats['new_female_count_vs'] += $patient->new_female_count_vs ?? 0;
            }

            // Count foreigner patients by gender
            $foreignerMaleCount = 0;
            $foreignerFemaleCount = 0;
            foreach ($log->foreignerPatients as $foreigner) {
                if ($foreigner->gender === 'Male') {
                    $foreignerMaleCount++;
                } else if ($foreigner->gender === 'Female') {
                    $foreignerFemaleCount++;
                }
            }
            $dailyStats['foreigner_male_count'] += $foreignerMaleCount;
            $dailyStats['foreigner_female_count'] += $foreignerFemaleCount;
            $dailyStats['foreigner_count'] += $foreignerMaleCount + $foreignerFemaleCount;
        }

        // Calculate total patients including foreigners
        $dailyStats['total_patients'] = $dailyStats['male_count'] + $dailyStats['female_count'] +
                                      $dailyStats['new_male_count_vs'] + $dailyStats['new_female_count_vs'] +
                                      $dailyStats['foreigner_male_count'] + $dailyStats['foreigner_female_count'];

        return view('daily.visiting-consultant.index', compact('loggedEntries', 'missingConsultants', 'dailyStats', 'selectedDate'));
    }


    public function create()
    {
        $consultants = VisitingConsultant::all();
        $selectedDate = request('date', Carbon::today()->format('Y-m-d'));

        // Get existing logs for the selected date
        $existingLogs = VisitingConsultantLog::whereDate('date', $selectedDate)
            ->pluck('consultant_name')
            ->toArray();

        // Get all consultants with their logged status for the selected date
        $consultantsWithStatus = $consultants->map(function($consultant) use ($existingLogs, $selectedDate) {
            return [
                'id' => $consultant->id,
                'name' => $consultant->name,
                'suite_number' => $consultant->suite_number,
                'is_logged' => in_array($consultant->name, $existingLogs),
                'logged_date' => $selectedDate
            ];
        });

        return view('daily.visiting-consultant.create', compact('consultantsWithStatus', 'selectedDate'));
    }


    public function show(VisitingConsultantLog $entry)
    {
        $entry->load(['foreignerPatients', 'patients']);
        return view('daily.visiting-consultant.show', compact('entry'));
    }


    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'consultant_name'      => ['required', 'string', 'exists:visiting_consultants,name'],
            'no_suite'             => ['required', 'string'],
            'total_patients_count' => ['nullable', 'integer', 'min:0'],
            'time_in'              => ['nullable', 'date_format:H:i'],
            'time_out'             => ['nullable', 'date_format:H:i', 'after_or_equal:time_in'],
            'ref_details'          => ['nullable', 'string', 'max:1000'],
            'remarks'              => ['nullable', 'string', 'max:1000'],
            'date'                 => ['required', 'date'],

            // Validation for patient counts
            'chinese_count'        => ['required', 'integer', 'min:0'],
            'malay_count'          => ['required', 'integer', 'min:0'],
            'india_count'          => ['required', 'integer', 'min:0'],
            'kdms_count'           => ['required', 'integer', 'min:0'],
            'others_count'         => ['required', 'integer', 'min:0'],
            'male_count'           => ['required', 'integer', 'min:0'],
            'female_count'         => ['required', 'integer', 'min:0'],
            'new_male_count_vs'       => ['required', 'integer', 'min:0'],
            'new_female_count_vs'     => ['required', 'integer', 'min:0'],
            'foreigner_male_count' => ['nullable', 'integer', 'min:0'],
            'foreigner_female_count' => ['nullable', 'integer', 'min:0'],

            // Validation for dynamic foreigner fields
            'foreigner_countries'  => ['nullable', 'array'],
            'foreigner_countries.*' => ['required_with:foreigner_genders.*', 'string', 'max:255'],
            'foreigner_genders'    => ['nullable', 'array'],
            'foreigner_genders.*'   => ['required_with:foreigner_countries.*', 'string', Rule::in(['Male', 'Female'])],
        ];

        // Custom error messages
        $messages = [
            'consultant_name.exists' => 'The selected consultant is invalid.',
            'time_out.after_or_equal' => 'Time Out must be after or equal to Time In.',
            'foreigner_countries.*.required_with' => 'Foreigner Country is required if gender is provided.',
            'foreigner_genders.*.required_with' => 'Foreigner Gender is required if country is provided.',
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            // Check if a log already exists for this consultant for the selected date
            $existingLog = VisitingConsultantLog::where('date', $validatedData['date'])
                                                ->where('consultant_name', $validatedData['consultant_name'])
                                                ->first();

            if ($existingLog) {
                return redirect()->route('daily.visiting-consultant.index')
                                 ->with('error', 'A log for this consultant already exists for this date. Please edit the existing entry instead.');
            }

            // Create the log entry with the selected date
            $logEntry = new VisitingConsultantLog();
            $logEntry->no_suite = $validatedData['no_suite'];
            $logEntry->consultant_name = $validatedData['consultant_name'];
            $logEntry->total_patients_count = $validatedData['total_patients_count'] ?? 0;
            $logEntry->time_in = $validatedData['time_in'];
            $logEntry->time_out = $validatedData['time_out'];
            $logEntry->ref_details = $validatedData['ref_details'];
            $logEntry->remarks = $validatedData['remarks'];
            $logEntry->date = $validatedData['date'];
            $logEntry->user_name = Auth::user()->name;
            $logEntry->save();

            // Save patient statistics
            $patientStats = new VisitingConsultantPatient();
            $patientStats->visiting_consultants_log_id = $logEntry->id;
            $patientStats->chinese_count = (int)$validatedData['chinese_count'];
            $patientStats->malay_count = (int)$validatedData['malay_count'];
            $patientStats->india_count = (int)$validatedData['india_count'];
            $patientStats->kdms_count = (int)$validatedData['kdms_count'];
            $patientStats->others_count = (int)$validatedData['others_count'];
            $patientStats->male_count = (int)$validatedData['male_count'];
            $patientStats->female_count = (int)$validatedData['female_count'];
            $patientStats->new_male_count_vs = (int)$validatedData['new_male_count_vs'];
            $patientStats->new_female_count_vs = (int)$validatedData['new_female_count_vs'];
            $patientStats->save();

            // Save foreigner patient details if provided
            if (isset($validatedData['foreigner_countries']) && is_array($validatedData['foreigner_countries'])) {
                foreach ($validatedData['foreigner_countries'] as $key => $country) {
                    if (!empty($country) && isset($validatedData['foreigner_genders'][$key])) {
                        $logEntry->foreignerPatients()->create([
                            'country' => $country,
                            'gender'  => $validatedData['foreigner_genders'][$key],
                        ]);
                    }
                }
            } else if (isset($validatedData['foreigner_male_count']) || isset($validatedData['foreigner_female_count'])) {
                // Handle foreigner counts from the numeric inputs
                $maleCount = (int)($validatedData['foreigner_male_count'] ?? 0);
                $femaleCount = (int)($validatedData['foreigner_female_count'] ?? 0);

                // Create male foreigner entries
                for ($i = 0; $i < $maleCount; $i++) {
                    $logEntry->foreignerPatients()->create([
                        'country' => 'Not Specified',
                        'gender'  => 'Male',
                    ]);
                }

                // Create female foreigner entries
                for ($i = 0; $i < $femaleCount; $i++) {
                    $logEntry->foreignerPatients()->create([
                        'country' => 'Not Specified',
                        'gender'  => 'Female',
                    ]);
                }
            }

            return redirect()->route('daily.visiting-consultant.index')
                            ->with('status', 'Visiting Consultant log saved successfully!');

        } catch (\Exception $e) {
            Log::error('Visiting Consultant log failed: ' . $e->getMessage());
            return redirect()->route('daily.visiting-consultant.index')
                            ->with('error', 'Failed to save log. Please try again.');
        }
    }


    public function monthly(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->month);
        $selectedYear = $request->get('year', Carbon::now()->year);

        $consultants = VisitingConsultant::all();
        $daysInMonth = Carbon::create($selectedYear, $selectedMonth, 1)->daysInMonth;

        // Get all logs for the selected month with eager loading
        $logs = VisitingConsultantLog::with(['patients', 'foreignerPatients'])
                                    ->whereYear('date', $selectedYear)
                                    ->whereMonth('date', $selectedMonth)
                                    ->get();

        // Organize logs by consultant and day
        $monthlyData = [];
        $monthlyStats = [
            'total_patients' => 0,
            'chinese_count' => 0,
            'malay_count' => 0,
            'india_count' => 0,
            'kdms_count' => 0,
            'others_count' => 0,
            'male_count' => 0,
            'female_count' => 0,
            'foreigner_count' => 0,
            'existing_patients_count' => 0,
            'new_patients_count' => 0,
            'new_male_count_vs' => 0,
            'new_female_count_vs' => 0,
            'foreigner_male_count' => 0,
            'foreigner_female_count' => 0
        ];

        foreach ($consultants as $consultant) {
            $monthlyData[$consultant->name] = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($selectedYear, $selectedMonth, $day);
                $log = $logs->first(function ($log) use ($consultant, $date) {
                    return $log->consultant_name === $consultant->name &&
                           \Carbon\Carbon::parse($log->date)->format('Y-m-d') === $date->format('Y-m-d');
                });

                $monthlyData[$consultant->name][$day] = $log;

                // Add to monthly statistics if log exists
                if ($log) {
                    $monthlyStats['total_patients'] += $log->total_patients_count ?? 0;

                    // Get the patient record
                    $patient = $log->patients->first();
                    if ($patient) {
                        $monthlyStats['chinese_count'] += $patient->chinese_count ?? 0;
                        $monthlyStats['malay_count'] += $patient->malay_count ?? 0;
                        $monthlyStats['india_count'] += $patient->india_count ?? 0;
                        $monthlyStats['kdms_count'] += $patient->kdms_count ?? 0;
                        $monthlyStats['others_count'] += $patient->others_count ?? 0;
                        $monthlyStats['male_count'] += $patient->male_count ?? 0;
                        $monthlyStats['female_count'] += $patient->female_count ?? 0;

                        // Calculate existing patients (regular patients)
                        $monthlyStats['existing_patients_count'] += ($patient->male_count ?? 0) + ($patient->female_count ?? 0);

                        // Calculate new patients
                        $monthlyStats['new_male_count_vs'] += $patient->new_male_count_vs ?? 0;
                        $monthlyStats['new_female_count_vs'] += $patient->new_female_count_vs ?? 0;
                        $monthlyStats['new_patients_count'] += ($patient->new_male_count_vs ?? 0) + ($patient->new_female_count_vs ?? 0);
                    }

                    // Count foreigner patients and their genders
                    $foreignerPatients = $log->foreignerPatients;
                    $monthlyStats['foreigner_count'] += $foreignerPatients->count();
                    $monthlyStats['foreigner_male_count'] += $foreignerPatients->where('gender', 'Male')->count();
                    $monthlyStats['foreigner_female_count'] += $foreignerPatients->where('gender', 'Female')->count();
                }
            }
        }

        return view('daily.visiting-consultant.monthly', compact(
            'consultants',
            'monthlyData',
            'monthlyStats',
            'selectedMonth',
            'selectedYear',
            'daysInMonth'
        ));
    }

    public function edit(VisitingConsultantLog $entry)
    {
        try {
            $entry->load(['patients', 'foreignerPatients']);

            // Ensure we have a patient record
            if ($entry->patients->isEmpty()) {
                $entry->patients()->create([
                    'chinese_count' => 0,
                    'malay_count' => 0,
                    'india_count' => 0,
                    'kdms_count' => 0,
                    'others_count' => 0,
                    'male_count' => 0,
                    'female_count' => 0,
                ]);
                $entry->load('patients'); // Reload the relationship
            }

            return view('daily.visiting-consultant.edit', compact('entry'));
        } catch (\Exception $e) {
            \Log::error('Error in VisitingConsultantController@edit: ' . $e->getMessage());
            return redirect()->route('daily.visiting-consultant.index')
                ->with('error', 'An error occurred while loading the edit form. Please try again.');
        }
    }

    public function update(Request $request, VisitingConsultantLog $entry)
    {
        $validated = $request->validate([
            'consultant_name' => 'required|string|max:255',
            'no_suite' => 'required|string|max:255',
            'date' => 'required|date',
            'total_patients_count' => 'nullable|integer|min:0',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'chinese_count' => 'required|integer|min:0',
            'malay_count' => 'required|integer|min:0',
            'india_count' => 'required|integer|min:0',
            'kdms_count' => 'required|integer|min:0',
            'others_count' => 'required|integer|min:0',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'new_male_count_vs' => 'required|integer|min:0',
            'new_female_count_vs' => 'required|integer|min:0',
            'foreigner_countries' => 'nullable|array',
            'foreigner_countries.*' => 'nullable|string|max:255',
            'foreigner_genders' => 'nullable|array',
            'foreigner_genders.*' => 'nullable|in:Male,Female',
            'ref_details' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $entry) {
                // Update basic information
                $entry->update([
                    'consultant_name' => $validated['consultant_name'],
                    'no_suite' => $validated['no_suite'],
                    'date' => $validated['date'],
                    'total_patients_count' => $validated['total_patients_count'],
                    'time_in' => $validated['time_in'],
                    'time_out' => $validated['time_out'],
                    'ref_details' => $validated['ref_details'],
                    'remarks' => $validated['remarks'],
                ]);

                // Update patient statistics
                $entry->patients()->update([
                    'chinese_count' => $validated['chinese_count'],
                    'malay_count' => $validated['malay_count'],
                    'india_count' => $validated['india_count'],
                    'kdms_count' => $validated['kdms_count'],
                    'others_count' => $validated['others_count'],
                    'male_count' => $validated['male_count'],
                    'female_count' => $validated['female_count'],
                    'new_male_count_vs' => $validated['new_male_count_vs'],
                    'new_female_count_vs' => $validated['new_female_count_vs'],
                ]);

                // Update foreigner patients
                $entry->foreignerPatients()->delete();
                if (!empty($validated['foreigner_countries']) && !empty($validated['foreigner_genders'])) {
                    foreach ($validated['foreigner_countries'] as $index => $country) {
                        if (!empty($country) && isset($validated['foreigner_genders'][$index])) {
                            $entry->foreignerPatients()->create([
                                'country' => $country,
                                'gender' => $validated['foreigner_genders'][$index],
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('daily.visiting-consultant.index')
                ->with('status', 'Visiting consultant log updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating visiting consultant log: ' . $e->getMessage());
            return redirect()->route('daily.visiting-consultant.index')
                ->with('error', 'Failed to update log. Please try again.');
        }
    }

    public function export(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Create start and end dates for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Fetch records between start and end dates with relationships
        $records = VisitingConsultantLog::with(['patients', 'foreignerPatients'])
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date')
            ->get();

        // Get all consultants
        $consultants = VisitingConsultant::all();

        // Calculate monthly statistics first
        $monthlyStats = [
            'total_patients' => 0,
            'chinese_count' => 0,
            'malay_count' => 0,
            'india_count' => 0,
            'kdms_count' => 0,
            'others_count' => 0,
            'male_count' => 0,
            'female_count' => 0,
            'foreigner_count' => 0,
            'existing_patients_count' => 0,
            'new_patients_count' => 0,
            'new_male_count_vs' => 0,
            'new_female_count_vs' => 0
        ];

        foreach ($records as $record) {
            $monthlyStats['total_patients'] += $record->total_patients_count ?? 0;
            $patient = $record->patients->first();
            if ($patient) {
                $monthlyStats['chinese_count'] += $patient->chinese_count ?? 0;
                $monthlyStats['malay_count'] += $patient->malay_count ?? 0;
                $monthlyStats['india_count'] += $patient->india_count ?? 0;
                $monthlyStats['kdms_count'] += $patient->kdms_count ?? 0;
                $monthlyStats['others_count'] += $patient->others_count ?? 0;
                $monthlyStats['male_count'] += $patient->male_count ?? 0;
                $monthlyStats['female_count'] += $patient->female_count ?? 0;

                // Calculate existing patients (regular patients)
                $monthlyStats['existing_patients_count'] += ($patient->male_count ?? 0) + ($patient->female_count ?? 0);

                // Calculate new patients
                $monthlyStats['new_male_count_vs'] += $patient->new_male_count_vs ?? 0;
                $monthlyStats['new_female_count_vs'] += $patient->new_female_count_vs ?? 0;
                $monthlyStats['new_patients_count'] += ($patient->new_male_count_vs ?? 0) + ($patient->new_female_count_vs ?? 0);
            }
            $monthlyStats['foreigner_count'] += $record->foreignerPatients->count();
        }

        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Visiting Consultant Monthly Report - ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)));
        $sheet->setCellValue('A3', 'Consultant');
        $sheet->setCellValue('B3', 'Suite');

        // Add day headers
        $lastColumn = 'B';
        for ($day = 1; $day <= $endDate->daysInMonth; $day++) {
            $lastColumn++;
            $sheet->setCellValue($lastColumn . '3', $day);
        }

        // Add data
        $row = 4;
        foreach ($consultants as $consultant) {
            $sheet->setCellValue('A' . $row, $consultant->name);
            $sheet->setCellValue('B' . $row, $consultant->suite_number);

            $col = 'C';
            for ($day = 1; $day <= $endDate->daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $log = $records->first(function($record) use ($date, $consultant) {
                    return Carbon::parse($record->date)->format('Y-m-d') === $date->format('Y-m-d')
                        && $record->consultant_name === $consultant->name;
                });

                $sheet->setCellValue($col . $row, $log ? $log->total_patients_count : '-');
                $col++;
            }
            $row++;
        }

        // Add total patients
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Total Patients');
        $sheet->setCellValue('B' . $row, $monthlyStats['total_patients']);

        // Add existing vs new patients
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Total Existing Patients');
        $sheet->setCellValue('B' . $row, $monthlyStats['existing_patients_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Total New Patients');
        $sheet->setCellValue('B' . $row, $monthlyStats['new_patients_count']);
        $row++;

        // Add regular patients by gender
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Regular Patients by Gender');
        $row++;
        $sheet->setCellValue('A' . $row, 'Male');
        $sheet->setCellValue('B' . $row, $monthlyStats['male_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Female');
        $sheet->setCellValue('B' . $row, $monthlyStats['female_count']);
        $row++;

        // Add new patients by gender
        $row += 1;
        $sheet->setCellValue('A' . $row, 'New Patients by Gender');
        $row++;
        $sheet->setCellValue('A' . $row, 'Male');
        $sheet->setCellValue('B' . $row, $monthlyStats['new_male_count_vs']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Female');
        $sheet->setCellValue('B' . $row, $monthlyStats['new_female_count_vs']);
        $row++;

        // Add race statistics
        $row += 1;
        $sheet->setCellValue('A' . $row, 'By Race');
        $row++;
        $sheet->setCellValue('A' . $row, 'Chinese');
        $sheet->setCellValue('B' . $row, $monthlyStats['chinese_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Malay');
        $sheet->setCellValue('B' . $row, $monthlyStats['malay_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Indian');
        $sheet->setCellValue('B' . $row, $monthlyStats['india_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'KDMS');
        $sheet->setCellValue('B' . $row, $monthlyStats['kdms_count']);
        $row++;
        $sheet->setCellValue('A' . $row, 'Others');
        $sheet->setCellValue('B' . $row, $monthlyStats['others_count']);

        // Add total foreigners
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Total Foreigners');
        $sheet->setCellValue('B' . $row, $monthlyStats['foreigner_count']);

        // Style the header
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFont()->setBold(true);

        // Auto-size columns
        $columnIterator = $sheet->getColumnIterator();
        foreach ($columnIterator as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Visiting_Consultant_Report_' . date('F_Y', mktime(0, 0, 0, $month, 1, $year)) . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Save file to PHP output
        $writer->save('php://output');
        exit;
    }
}
