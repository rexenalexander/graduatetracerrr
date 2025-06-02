<?php

namespace App\Http\Controllers;

use App\Mail\NotifyEmail;
use App\Mail\NotifyEmployer;
use App\Models\Employer;
use App\Models\Graduate;
use App\Models\History;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }
        $year = $request->year ?? date('Y');
        $fromYear = $request->year1 ?? date('Y');
        $toYear = $request->year ?? date('Y');
        if ($fromYear > $toYear) {
            $toYear = $fromYear;
        }

        $totalGraduates = User::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('role', 1)
            ->where('id', '!=', 1)
            ->count();

        $genderCounts = User::when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('role', 1)
            ->where('id', '!=', 1)
            ->selectRaw("gender, COUNT(*) as count")
            ->groupBy('gender')
            ->pluck('count', 'gender'); 

        $maleCount = $genderCounts['male'] ?? 0;
        $femaleCount = $genderCounts['female'] ?? 0;

        $maleRate = $totalGraduates > 0 ? round(($maleCount / $totalGraduates) * 100, 2) : 0;
        $femaleRate = $totalGraduates > 0 ? round(($femaleCount / $totalGraduates) * 100, 2) : 0;

        $totalUntracedGraduates = User::join('graduates', 'users.id', '=', 'graduates.user_id')
            ->when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('users.graduation_year', [$fromYear, $toYear]);
            })
            ->where('users.role', 1)
            ->where('users.id', '!=', 1)
            // ->whereNull('graduates.user_id') // Only users with no matching graduate record
            ->count();
        

        // $totalGraduates = User::leftJoin('graduates', 'users.id', '=', 'graduates.user_id')
        //     ->selectRaw('COALESCE(graduates.graduation_year, 2025) as graduation_year')
        //     ->when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
        //         return $query->whereBetween(DB::raw('COALESCE(graduates.graduation_year, 2025)'), [$fromYear, $toYear]);
        //     })
        //     ->count();

        $employedGraduates = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('employed', 1)
            ->count();

        $untracedRate = $totalGraduates > 0 ?
            round(($totalUntracedGraduates / $totalGraduates) * 100, 1) : 0;

        $employmentRate = $totalGraduates > 0 ?
            round(($employedGraduates / $totalGraduates) * 100, 1) : 0;

        // Using the employed field instead of current_employment
        $employedCount = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('employed', 1)->count();

        // For self-employed and unemployed, we need to determine how they're stored
        // Option 1: If you have a separate field for self-employed
        // $selfEmployedCount = Graduate::where('position', 'like', '%self%')
        //     ->orWhere('company_name', 'like', '%self%')
        //     ->count();
        $selfEmployedCount = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('employed', 2)->count();

        // Option 2: If unemployed is simply not employed
        $unemployedCount = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('employed', 0)->count();

        // Get counts by year
        $totalGraduatesByYear = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->select('graduation_year', DB::raw('count(*) as total'))
            ->groupBy('graduation_year')
            ->pluck('total', 'graduation_year')
            ->toArray();

        $lifelongLearners = 0; // Default value if field doesn't exist

        $employmentByYear = User::leftJoin('graduates', 'users.id', '=', 'graduates.user_id')
            ->when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('users.graduation_year', [$fromYear, $toYear]);
            })
            ->select(
                'users.graduation_year',
                DB::raw('count(*) as total'),
                DB::raw('sum(case when graduates.employed = 0 then 1 else 0 end) as employed'),
                DB::raw('sum(case when graduates.employed = 1 then 1 else 0 end) as unemployed'),
                DB::raw('sum(case when graduates.employed = 2 then 1 else 0 end) as self_employed'),
            )
            ->where('users.role', 1)
            ->where('users.id', '!=', 1)
            ->groupBy('users.graduation_year')
            ->orderBy('users.graduation_year')
            ->get();

        $lifelongLearners = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('lifelong_learner', 1)->count();

        $genderDistribution = User::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->select('gender', DB::raw('count(*) as count'))
            ->where('role', 1)
            ->where('id', '!=', 1)
            ->groupBy('gender')
            ->get();

        // Add industry sector distribution
        $industrySectors = Graduate::select('industry_sector', DB::raw('count(*) as count'))
            ->whereNotNull('industry_sector')
            ->groupBy('industry_sector')
            ->get();

        // Add CPE-related work statistics
        $cpeRelatedWork = Graduate::
            when($fromYear && $toYear, function ($query) use ($fromYear, $toYear) {
                return $query->whereBetween('graduation_year', [$fromYear, $toYear]);
            })
            ->where('is_cpe_related', true)->count();
        $cpeRelatedPercentage = $totalGraduates > 0 ?
            round(($cpeRelatedWork / $totalGraduates) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalGraduates',
            'employedGraduates',
            'employmentRate',
            'employmentByYear',
            'genderDistribution',
            'industrySectors',
            'cpeRelatedWork',
            'cpeRelatedPercentage',
            'employedCount',
            'selfEmployedCount',
            'unemployedCount',
            'totalGraduatesByYear',
            'lifelongLearners',
            'maleRate',
            'femaleRate',
            'maleCount',
            'femaleCount',
            'totalUntracedGraduates',
            'untracedRate'
        ));
    }

    public function graduates(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        $query = Graduate::leftJoin('users', 'graduates.user_id', '=', 'users.id')
            ->select('graduates.*', 'users.firstname', 'users.graduation_year as grad_year', 'users.gender as grad_gender');

        // Filter by graduation year if selected
        if ($request->has('year') && $request->year) {
            $query->where('graduates.graduation_year', $request->year);
        }

        // Filter by graduation year if selected
        if ($request->employment || $request->employment == '0') {
            $query->where('graduates.employed', $request->employment);
        }

        // Filter by graduation year if selected
        if ($request->has('searchtext') && $request->searchtext) {
            $query->where(function ($q) use ($request) {
                $q->where('users.firstname', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('users.middlename', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('users.lastname', 'LIKE', '%'.$request->searchtext.'%');
            });
        }

        $graduates = $query->latest()->paginate(10);

        // Keep pagination working with the filter
        if ($request->has('year')) {
            $graduates->appends(['year' => $request->year]);
        }
        if ($request->has('searchtext')) {
            $graduates->appends(['searchtext' => $request->searchtext]);
        }

        if ($request->has('employment')) {
            $graduates->appends(['employment' => $request->employment]);
        }

        return view('admin.graduates', compact('graduates'))
            ->with('backUrl', route('admin.dashboard')); // Add back URL context
    }

    public function employers(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        $query = Survey::
            leftJoin('users as emp', 'surveys.user_id', '=', 'emp.id')
            ->leftJoin('users as grad', 'surveys.graduate_id', '=', 'grad.id')
            ->select('surveys.*', 
            'emp.firstname', 
            'emp.middlename', 
            'emp.lastname', 
            'emp.email',
            'grad.firstname as fname',
            'grad.middlename as mname',
            'grad.lastname as lname',
        );

        // Filter by graduation year if selected
        if ($request->has('searchtext') && $request->searchtext) {
            $query->where(function ($q) use ($request) {
                $q->where('emp.firstname', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('emp.middlename', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('emp.lastname', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('grad.firstname', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('grad.middlename', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('grad.lastname', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('emp.email', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('surveys.position', 'LIKE', '%'.$request->searchtext.'%')
                  ->orWhere('surveys.company_name', 'LIKE', '%'.$request->searchtext.'%')
                  ;
            });
        }

        $employers = $query->latest()->paginate(10);

        return view('admin.employers', compact('employers'))
            ->with('backUrl', route('admin.dashboard')); // Add back URL context
    }

    public function notify(Request $request)
    {

        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        $graduate = false;

        if($request->graduate == "true"){
            $graduate = true;
        }


        // Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('excel_file')->store('uploads');
        $fullPath = storage_path('app/' . $path);
        $reader = ReaderEntityFactory::createReaderFromFile(storage_path('app/' . $path));
        $reader->open(storage_path('app/' . $path));

        // Load the Excel file
        DB::beginTransaction(); // Begin a transaction
        $firstRow = true;

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $rowNumber = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($firstRow) {
                        $firstRow = false; // Skip the first row (header)
                        continue;
                    }
                    $rowNumber++; // Increment row number for each data row

                    $cells = $row->getCells();
                                    
                    if (!empty($cells[0]) && !empty($cells[1])) {
                        $name = isset($cells[0]) ? $cells[0]->getValue() : 'Graduate';
                        $email = isset($cells[1]) ? $cells[1]->getValue() : '';

                        $content = [
                            'message_body' => $request->message_body ?? "",
                            'message_footer' => $request->message_footer ?? "",
                            'name' => $name ?? "",
                        ];


                        if($graduate) {
                            Mail::to($email)->send(new NotifyEmail((object)$content));
                        } else {
                            Mail::to($email)->send(new NotifyEmployer((object)$content));
                        }

                    }
                }
            }
            
            DB::commit(); // Commit transaction if all rows pass validation
            $reader->close();

            sleep(1);
            
            // Try deleting with Storage, and if it fails, use unlink
            try {
                Storage::delete($path) || unlink($fullPath);
            } catch (\Exception $e) {
                if($graduate) {
                    return redirect()->route('admin.notifypage')->with('error', 'There is a problem with your excel file. Please re-check.' . $e->getMessage());
                } else {
                    return redirect()->route('admin.employernotifypage')->with('error', 'There is a problem with your excel file. Please re-check' . $e->getMessage());
                }
                return response()->json(['status' => 500, 'message' => 'Failed to delete uploaded file: ' . $e->getMessage()]);
            }

            if($graduate) {
                return redirect()->route('admin.graduates')->with('success', 'Graduate notified successfully');
            } else {
                return redirect()->route('admin.employers')->with('success', 'Employer notified successfully');
            }


        }
        catch (\Exception $e) {
            // return back()->withErrors(['excel_file' => 'Failed to read Excel file. Error: ' . $e->getMessage()]);
            if($graduate) {
                return redirect()->route('admin.notifypage')->with('error', 'There is a problem with your excel file. Please re-check.'. $e->getMessage());
            } else {
                return redirect()->route('admin.employernotifypage')->with('error', 'There is a problem with your excel file. Please re-check'. $e->getMessage());
            }
        }

    }

    public function notifygraduate(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }
        // Check if there are existing queued jobs of type NotifyEmail
        $hasPendingJobs = DB::table('jobs')
            ->where('payload', 'like', '%NotifyEmail%')
            ->exists();

        if ($hasPendingJobs) {
            return redirect()->route('admin.graduates')
                ->with('error', 'There are still pending email notifications in the queue. Please wait for them to finish.');
        }

        $graduateList = User::select('firstname', 'default_password', 'email')
            ->where('role', 1)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get()
            ->filter(function ($user) {
                return filter_var($user->email, FILTER_VALIDATE_EMAIL);
            });

        if ($graduateList->isEmpty()) {
            return redirect()->route('admin.graduates')->with('error', 'Empty or Invalid Graduate Email List!');
        }

        try {
        foreach ($graduateList as $grad) {
            if ($grad->firstname && $grad->default_password && $grad->email) {
                $email = $grad->email;
                $name = $grad->firstname;
                $password = $grad->default_password;

                $content = [
                    'message_body' => $request->message_body ?? "",
                    'message_footer' => $request->message_footer ?? "",
                    'name' => $name ?? "",
                    'password' => $password ?? "",
                ];

                Mail::to($email)->queue(new NotifyEmail((object)$content));
            }
        }
        return redirect()->route('admin.graduates')->with('success', 'Graduate notifications queued successfully.');
        }
        catch (\Exception $e) {
                return redirect()->route('admin.graduates')->with('error', 'Something went wrong'. $e->getMessage());
        }
    }

    public function clearjobs(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }
        // Check if there are existing queued jobs of type NotifyEmail
        DB::table('jobs')
            ->where('payload', 'like', '%NotifyEmail%')
            ->delete();

        DB::table('failed_jobs')
            ->where('payload', 'like', '%NotifyEmail%')
            ->delete();

        return redirect()->route('admin.notifypage')->with('success', 'Pending sending email cleared successfully.');
    }

    public function importlist(Request $request)
    {
        ini_set('max_execution_time', 1200); // 10 mins
        ini_set('memory_limit', '512M');



        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        // Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('excel_file')->store('uploads');
        $fullPath = storage_path('app/' . $path);
        $reader = ReaderEntityFactory::createReaderFromFile(storage_path('app/' . $path));
        $reader->open(storage_path('app/' . $path));

        // Load the Excel file
        DB::beginTransaction(); // Begin a transaction

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $rowNumber = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($rowNumber < 5) {
                        $rowNumber++; 
                        continue;
                    }
                    $rowNumber++; // Increment row number for each data row

                    $cells = $row->getCells();
                                    
                    if (!empty($cells[1]) && !empty($cells[2]) && !empty($cells[3]) && !empty($cells[4]
                            && $cells[1]->getValue() != "1"
                        )) {
                        $graduation_year = isset($cells[0]) ? $cells[0]->getValue() : date('Y');
                        $user_id = isset($cells[1]) ? $cells[1]->getValue() : 1;
                        $lastname = isset($cells[2]) ? $cells[2]->getValue() : '';
                        $firstname = isset($cells[3]) ? $cells[3]->getValue() : '';
                        $middlename = isset($cells[4]) ? $cells[4]->getValue() : '';

                        $checkEmployer = User::where('role', 2)->where('id', $user_id)->exists();
                        if($checkEmployer) {
                            throw new \Exception("Row $rowNumber: ID/No. already exist in employer for Graduate $user_id");
                        }

                        $gender = '';
                        if (isset($cells[5])) {
                            $value = $cells[5]->getValue();
                            if ($value == "1") {
                                $gender = 'male';
                            } else {
                                $gender = 'female';
                            }
                        }


                        $email = isset($cells[6]) ? $cells[6]->getValue() : '';
                        $facebook = isset($cells[7]) ? $cells[7]->getValue() : '';
                        $phone_number = isset($cells[8]) ? $cells[8]->getValue() : '';

                        $employed = 0;
                        $notemployed = 0;
                        if (isset($cells[9])) {
                            $value = $cells[9]->getValue();
                            if ($value === 'Employed') {
                                $employed = 1;
                            } 
                            else if ($value === 'Self-Employed') {
                                $employed = 2;
                            }
                            else if ($value === 'Unemployed') {
                                $notemployed = 1;
                            }
                        }

                        $position = isset($cells[10]) ? $cells[10]->getValue() : '';
                        $company_name = isset($cells[11]) ? $cells[11]->getValue() : '';
                        $company_address = isset($cells[12]) ? $cells[12]->getValue() : '';

                        $industry_sector = isset($cells[13]) ? $cells[13]->getValue() : '';

                        $is_cpe_related = 0;
                        if (isset($cells[14])) {
                            $value = $cells[14]->getValue();
                            if ($value === 'Yes') {
                                $is_cpe_related = 1;
                            } 
                        }

                        $has_awards = 0;
                        $awards_details = '';
                        if (isset($cells[15])) {
                            $value = $cells[15]->getValue();
                            if ($value !== 'None' && $value != "") {
                                $has_awards = 1;        
                                $awards_details = $value;
                            }
                        }

                        $is_involved_organizations = 0;
                        $org_details = '';
                        if (isset($cells[16])) {
                            $value = $cells[16]->getValue();
                            if ($value != 'None' && $value != "") {
                                $org_details = $value;
                                $is_involved_organizations = 1;
                            }
                        }
                        $randomPassword = Str::random(8);

                        $hashedPassword = Hash::make($randomPassword);

                        // if (!is_numeric($phone_number)) {
                        //     throw new \Exception("Row $rowNumber: Invalid contact for Graduate $user_id - $phone_number");
                        // }

                        if(!$firstname) {
                            throw new \Exception("Row $rowNumber: Please provide firstname for Graduate $user_id");
                        }

                        if(!$lastname) {
                            throw new \Exception("Row $rowNumber: Please provide lastname for Graduate $user_id");
                        }

                        if(!$graduation_year) {
                            throw new \Exception("Row $rowNumber: Please provide graduation for Graduate $user_id");
                        }

                        if(!$gender) {
                            throw new \Exception("Row $rowNumber: Please provide gender for Graduate $user_id");
                        }

                        if (!preg_match('/^\d{4}$/', $graduation_year) || $graduation_year < 1900 || $graduation_year > date('Y')) {
                            throw new \Exception("Row $rowNumber: Invalid graduation year '$graduation_year' for Graduate $user_id");
                        }

                        UserUpload::updateOrCreate(
                            ['id' => $user_id],
                            [
                                'role' => 1,
                                'firstname' => $firstname,
                                'middlename' => $middlename,
                                'lastname' => $lastname,
                                'email' => $email,
                                'password' => $hashedPassword,
                                'default_password' => $randomPassword,
                                'graduation_year' => $graduation_year,
                                'gender' => $gender,
                                'changepass' => $email ? 1 : 0,
                            ]
                        );

                        if(
                            // $phone_number || $facebook ||
                            // || $graduation_year || $gender
                             ($employed || (!$employed && $notemployed))
                            || $position
                            || $company_name || $company_address
                            || $industry_sector || $is_cpe_related
                            || $has_awards || $awards_details
                            || $has_awards || $org_details
                        )
                        {
                            Graduate::updateOrCreate(
                                ['user_id' => $user_id],
                                [
                                    'phone_number' => $phone_number,
                                    'gender' => $gender,
                                    'graduation_year' => $graduation_year,
                                    'facebook' => $facebook,
                                    'employed' => $employed,
                                    'position' => $position,
                                    'company_name' => $company_name,
                                    'company_address' => $company_address,
                                    'industry_sector' => $industry_sector,
                                    'is_cpe_related' => $is_cpe_related,
                                    'has_awards' => $has_awards,
                                    'is_involved_organizations' => $is_involved_organizations,
                                    'awards_details' => $awards_details,
                                    'org_details' => $org_details,
                                ]
                            );
                        }


                    }
                }
            }
            
            DB::commit(); // Commit transaction if all rows pass validation
            $reader->close();

            sleep(1);
            
            // Try deleting with Storage, and if it fails, use unlink
            try {
                Storage::delete($path) || unlink($fullPath);
            } catch (\Exception $e) {
                return redirect()->route('admin.importpage')->with('error', 'There is a problem with your excel file. Please re-check.');
            }
            return redirect()->route('admin.graduates')->with('success', 'Graduate imported successfully');

        }
        catch (\Exception $e) {
            // return back()->withErrors(['excel_file' => 'Failed to read Excel file. Error: ' . $e->getMessage()]);
            return redirect()->route('admin.importpage')->with('error',  $e->getMessage());
        }

    }

    public function notifypage(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }
        $jobCount = DB::table('jobs')
            ->where('payload', 'like', '%NotifyEmail%')
            ->count();
        
        return view('admin.notifypage', compact('jobCount'))
        ->with('success', 'Graduate notified successfully');
    }

    public function importpage(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        return view('admin.importpage')
        ->with('success', 'Graduate uploaded successfully');
    }

    public function employernotifypage(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->route('login');
        }

        return view('admin.employernotifypage')
        ->with('success', 'Employer notified successfully');
    }

    public function edit(Graduate $graduate)
    {
        return view('admin.graduates.edit', compact('graduate'))
            ->with('backUrl', route('admin.graduates')); // Add back URL context
    }

    public function update(Request $request, Graduate $graduate)
    {
        $validated = $request->validate([
            'phone_number' => 'required|numeric|max:20',
            'gender' => 'required|in:male,female',
            'graduation_year' => 'required|numeric',
            'facebook' => 'nullable|string',
            'employment_status' => 'required|in:employed,self-employed,unemployed'
        ]);

        $graduate->update([
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
            'graduation_year' => $validated['graduation_year'],
            'facebook' => $validated['facebook'],
            'employed' => in_array($validated['employment_status'], ['employed', 'self-employed']),
            'current_employment' => $validated['employment_status']
        ]);

        return redirect()->route('admin.graduates')
            ->with('success', 'Graduate updated successfully');
    }

    public function destroy(Graduate $graduate)
    {
        $graduate->delete();
        return redirect()->route('admin.graduates')
            ->with('success', 'Graduate deleted successfully');
    }
    // Add this method to your AdminController
    public function getYearStats($year)
    {
        // Query for graduates of the selected year
        $totalGraduates = DB::table('users')
            ->where('graduation_year', $year)
            ->count();

        // Get employed count for the selected year
        $employedCount = DB::table('users')
            ->where('graduation_year', $year)
            ->where('employed', 1)
            ->count();

        // Get unemployed count for the selected year
        $unemployedCount = DB::table('users')
            ->where('graduation_year', $year)
            ->where('employed', 0)
            ->count();

        // Get lifelong learners count (you may need to adjust this query based on your definition)
        $lifelongLearners = DB::table('users')
            ->where('graduation_year', $year)
            ->where('current_employment', 'graduate') // Adjust this condition as needed
            ->count();

        return response()->json([
            'totalGraduates' => $totalGraduates,
            'employedCount' => $employedCount,
            'unemployedCount' => $unemployedCount,
            'lifelongLearners' => $lifelongLearners
        ]);
    }

    public function exportCsv()
    {
        $graduates = Graduate::with('user')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=graduates.csv'
        ];

        $callback = function () use ($graduates) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Gender', 'Graduation Year', 'Employment']);

            foreach ($graduates as $graduate) {
                $employment_status = match ($graduate->employed) {
                    1 => 'employed',
                    2 => 'self-employed',
                    default => 'unemployed',
                };

                fputcsv($file, [
                    $graduate->user->name,
                    $graduate->user->email,
                    $graduate->phone_number,
                    $graduate->gender,
                    $graduate->graduation_year,
                    $employment_status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function employmentStats($year)
    {
        $stats = [
            'employed' => Graduate::where('current_employment', 'employed')
                ->where('graduation_year', $year)
                ->count(),
            'selfEmployed' => Graduate::where('current_employment', 'self-employed')
                ->where('graduation_year', $year)
                ->count(),
            'unemployed' => Graduate::where('current_employment', 'unemployed')
                ->where('graduation_year', $year)
                ->count(),
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'employedTrend' => [],
            'selfEmployedTrend' => [],
            'unemployedTrend' => []
        ];

        // Get monthly trends by graduation year instead of created_at
        for ($month = 1; $month <= 12; $month++) {
            $stats['employedTrend'][] = Graduate::where('current_employment', 'employed')
                ->where('graduation_year', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $stats['selfEmployedTrend'][] = Graduate::where('current_employment', 'self-employed')
                ->where('graduation_year', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $stats['unemployedTrend'][] = Graduate::where('current_employment', 'unemployed')
                ->where('graduation_year', $year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        return response()->json($stats);
    }

    public function getSurveyData(Graduate $graduate)
    {
        try {
            $graduate->load('user');
            $data = array_merge($graduate->toArray(), [
                'name' => $graduate->user->name,
                'email' => $graduate->user->email,
                'created_at' => $graduate->created_at,
                'updated_at' => $graduate->updated_at
            ]);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load survey data'], 500);
        }
    }

    public function getEmployerSurveyData(Request $request)
    {
        try {

            $data = Survey::
                leftJoin('users as emp', 'surveys.user_id', '=', 'emp.id')
                ->leftJoin('users as grad', 'surveys.graduate_id', '=', 'grad.id')
                ->select('surveys.*', 
                    'grad.email',
                    DB::raw("CONCAT(
                        IFNULL(emp.firstname, ''),
                        ' ',
                        IFNULL(emp.middlename, ''),
                        ' ',
                        IFNULL(emp.lastname, '')
                    ) as name"),
                    DB::raw("CONCAT(
                        IFNULL(grad.firstname, ''),
                        ' ',
                        IFNULL(grad.middlename, ''),
                        ' ',
                        IFNULL(grad.lastname, '')
                    ) as grad_name"),
                    DB::raw("
                    CASE 
                        WHEN surveys.q1 = 1 THEN 'Excellent' 
                        WHEN surveys.q1 = 2 THEN 'Good' 
                        WHEN surveys.q1 = 3 THEN 'Average' 
                        WHEN surveys.q1 = 4 THEN 'Poor' 
                        ELSE 'None' 
                    END as q1"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q5 = 1 THEN 'Very Well' 
                        WHEN surveys.q5 = 2 THEN 'Well' 
                        WHEN surveys.q5 = 3 THEN 'Fairly Well' 
                        WHEN surveys.q5 = 4 THEN 'Not well at all' 
                        ELSE 'None' 
                    END as q5"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q6 = 1 THEN 'Very Quickly' 
                        WHEN surveys.q6 = 2 THEN 'Quickly' 
                        WHEN surveys.q6 = 3 THEN 'Moderately' 
                        WHEN surveys.q6 = 4 THEN 'Slowly' 
                        ELSE 'None' 
                    END as q6"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q7 = 1 THEN 'Very effective' 
                        WHEN surveys.q7 = 2 THEN 'Effective' 
                        WHEN surveys.q7 = 3 THEN 'Neutral' 
                        WHEN surveys.q7 = 4 THEN 'Ineffective' 
                        ELSE 'None' 
                    END as q7"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q8 = 1 THEN 'Very well' 
                        WHEN surveys.q8 = 2 THEN 'Well' 
                        WHEN surveys.q8 = 3 THEN 'Adequately' 
                        WHEN surveys.q8 = 4 THEN 'Poorly' 
                        ELSE 'None' 
                    END as q8"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q9 = 1 THEN 'Excellent' 
                        WHEN surveys.q9 = 2 THEN 'Good' 
                        WHEN surveys.q9 = 3 THEN 'Fair' 
                        WHEN surveys.q9 = 4 THEN 'Poor' 
                        ELSE 'None' 
                    END as q9"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q10 = 1 THEN 'Always' 
                        WHEN surveys.q10 = 2 THEN 'Often' 
                        WHEN surveys.q10 = 3 THEN 'Occasionally' 
                        WHEN surveys.q10 = 4 THEN 'Never' 
                        ELSE 'None' 
                    END as q10"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q11 = 1 THEN 'Excellent' 
                        WHEN surveys.q11 = 2 THEN 'Good' 
                        WHEN surveys.q11 = 3 THEN 'Fair' 
                        WHEN surveys.q11 = 4 THEN 'Poor' 
                        ELSE 'None' 
                    END as q11"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q12 = 1 THEN 'Very professional' 
                        WHEN surveys.q12 = 2 THEN 'Professional' 
                        WHEN surveys.q12 = 3 THEN 'Somewhat professional' 
                        WHEN surveys.q12 = 4 THEN 'Unprofessional' 
                        ELSE 'None' 
                    END as q12"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q13 = 1 THEN 'Yes, definitely' 
                        WHEN surveys.q13 = 2 THEN 'Yes, potentially' 
                        WHEN surveys.q13 = 3 THEN 'Uncertain' 
                        WHEN surveys.q13 = 4 THEN 'No' 
                        ELSE 'None' 
                    END as q13"),

                    DB::raw("
                    CASE 
                        WHEN surveys.q14 = 1 THEN 'Yes' 
                        WHEN surveys.q14 = 2 THEN 'No' 
                        WHEN surveys.q14 = 3 THEN 'Unsure' 
                        ELSE 'None' 
                    END as q14"),
        
                )
                ->where('surveys.id', $request->employer)
                ->first();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load survey data'.$e], 500);
        }
    }

    public function history(Graduate $graduate)
    {
        $graduateinfo = User::where('id', $graduate->user_id)->first();
        $history = History::where('user_id', $graduate->user_id)->get();
        $current = Graduate::where('user_id', $graduate->user_id)->get();

        return view('admin.history', compact('history', 'graduateinfo', 'current'));
    }
}
