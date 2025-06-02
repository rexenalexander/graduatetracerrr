<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Graduate;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployersController extends Controller
{
    public function create()
    {
        // $exist = Employer::where('user_id', auth()->id())->exists();
        // // Check if user has already submitted a survey
        // if ($exist) {
        //     return redirect()->route('dashboard')
        //         ->with('error', 'You have already submitted a survey. You can only edit your existing submission.');
        // }
        $authUser = User::select('email')->where('id', auth()->id())->where('role', 2)->first();

        $authEmail = '';
        if($authUser && $authUser->email) $authEmail = $authUser->email;
        

        $graduates = User::where('role', 1)
            ->whereNotIn('id', function ($query) {
                $query->select('graduate_id')->from('surveys');
            })
            ->where('employer_email', $authEmail)
           // get only user columns
            ->get();

        $graduatesCount = User::where('role', 1)
            ->whereNotIn('id', function ($query) {
                $query->select('graduate_id')->from('surveys');
            })
            ->where('employer_email', $authEmail)
            ->count();

        if (!$graduatesCount) {
            return redirect()->route('survey.employers')
                ->with('error', 'No Graduates to survey!');
        }

        return view('employers.create', compact('graduates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string',
            'graduate_id' => 'required',
            'position' => 'required|string',
            'company_address' => 'required|string',
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'q11' => 'required',
            'q12' => 'required',
            'q13' => 'required',
            'q14' => 'required',
            'q15' => 'required',
        ]);

        $validated['user_id'] = auth()->id();

        // $checkExist = Employer::where('user_id', auth()->id())->first();
        // if($checkExist) {
        //     return redirect()->route('dashboard')->with('error', 'You have already submitted a survey. You can only edit your existing submission!');
        // }

        Survey::create($validated);

        return redirect()->route('survey.employers')->with('success', 'Survey submitted successfully!');
    }

    public function edit(Request $request)
    {
        $employer = Survey::leftJoin('users', 'surveys.graduate_id', '=', 'users.id')
            ->select('surveys.*', 
                DB::raw("CONCAT(
                    IFNULL(users.firstname, ''), ' ',
                    IFNULL(users.middlename, ''), ' ',
                    IFNULL(users.lastname, '')
                ) AS fullname")
            )
            ->where('surveys.user_id', auth()->id())
            ->where('surveys.id', $request->employer)
            ->first();
        // Check if user has already submitted a survey
        if (!$employer) {
            return redirect()->route('survey.employers')
                ->with('error', 'We cannot retrieve your survey, please refresh and try again.');
        }

        return view('employers.edit', compact('employer'));
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            'company_name' => 'required|string',
            'position' => 'required|string',
            'company_address' => 'required|string',
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q5' => 'required',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'q11' => 'required',
            'q12' => 'required',
            'q13' => 'required',
            'q14' => 'required',
            'q15' => 'required',
        ]);

        Survey::where('user_id', auth()->id())->where('id', $request->id)->update($validated);

        return redirect()->route('survey.employers')->with('success', 'Survey updated successfully!');
    }

    public function mysurveys(Request $request)
    {
        $query = Survey::
            leftJoin('users as emp', 'surveys.user_id', '=', 'emp.id')
            ->leftJoin('users as grad', 'surveys.graduate_id', '=', 'grad.id')
            ->select('surveys.*', 
                'grad.firstname', 
                'grad.middlename', 
                'grad.lastname', 
                'grad.email',
                'grad.email',
                )
            ->where('user_id', auth()->id());

        $graduates = $query->latest()->paginate(10);

        return view('surveys.mysurveys', compact('graduates'))
            ->with('backUrl', route('dashboard')); // Add back URL context
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
}