<?php

namespace App\Http\Controllers;

use App\Models\Graduate;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GraduatesController extends Controller
{
    public function create()
    {
        // Check if user has already submitted a survey
        if (auth()->user()->graduate) {
            return redirect()->route('dashboard')
                ->with('error', 'You have already submitted a survey. You can only edit your existing submission.');
        }
        return view('graduates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'employer_email' => 'required_if:employed,true|email',
            'graduation_year' => 'required|integer',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'facebook' => 'nullable|string',
            'position' => 'required_if:employment_status,true',
            'company_name' => 'required_if:employment_status,true',
            'company_address' => 'required_if:employment_status,true',
            'industry_sector' => 'required_if:employment_status,true',
            'is_cpe_related' => 'required_if:employment_status,true',
            'has_awards' => 'required_if:employment_status,true',
            'is_involved_organizations' => 'required_if:employment_status,true',
            'lifelong_learner' => 'nullable|in:0,1',
            'photo' => 'nullable|image|max:2048',
            'course_details' => 'required_if:employment_status,true|required_if:lifelong_learner,1',
            'awards_details' => 'required_if:employment_status,true|required_if:has_awards,1',
            'org_details' => 'required_if:employment_status,true|required_if:is_involved_organizations,1',
        ]);

        $validated['lifelong_learner'] = $request->lifelong_learner;

        $validated['employed'] = $request->employment_status;
        $validated['user_id'] = auth()->id();

        User::where('id', auth()->id())->update([
            'email' => $validated['email'],
            'employer_email' => $validated['employer_email'],
            'graduation_year' => $validated['graduation_year'],
            'gender' => $validated['gender']
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('graduate-photos', 'public');
        }

        $checkExist = Graduate::where('user_id', auth()->id())->first();
        if($checkExist) {
            return redirect()->route('dashboard')->with('error', 'You have already submitted a survey. You can only edit your existing submission!');
        }

        Graduate::create($validated);

        return redirect()->route('dashboard')->with('success', 'Graduate survey submitted successfully!');
    }

    public function edit(Graduate $graduate)
    {
        // Check if user owns this graduate record
        if ($graduate->user_id !== auth()->id()) {
            abort(403);
        }
        $graduate = Graduate::leftJoin('users', 'graduates.user_id', '=', 'users.id')
            ->select('graduates.*', 'users.employer_email')
            ->where('graduates.user_id', $graduate->user_id)->first();

        return view('graduates.edit', compact('graduate'));
    }

    public function update(Request $request, Graduate $graduate)
    {
        if ($graduate->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'employer_email' => 'required_if:employed,true|email',
            'graduation_year' => 'required|integer',
            'employment_status' => 'required|string',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'facebook' => 'nullable|string',
            'position' => 'required_if:employed,true',
            'company_name' => 'required_if:employed,true',
            'company_address' => 'required_if:employed,true',
            'industry_sector' => 'required_if:employed,true',
            'is_cpe_related' => 'required_if:employed,true',
            'has_awards' => 'required_if:employed,true',
            'is_involved_organizations' => 'required_if:employed,true',
            'photo' => 'nullable|image|max:2048',
            'course_details' => 'required_if:employment_status,true|required_if:lifelong_learner,1',
            'awards_details' => 'required_if:employment_status,true|required_if:has_awards,1',
            'org_details' => 'required_if:employment_status,true|required_if:is_involved_organizations,1',
        ]);
        
        $checkChanges = Graduate::where('user_id', auth()->id())->first();

        if($checkChanges 
            && (($checkChanges->company_name !=  $validated['company_name'] && $checkChanges->company_name != "") 
            || ($checkChanges->position != $validated['position']  && $checkChanges->position != ""))) {
                History::create([
                'user_id' => auth()->id(),
                'position' => $checkChanges->position,
                'company_name' => $checkChanges->company_name,
                'company_address' => $checkChanges->company_address,
                'industry_sector' => $checkChanges->industry_sector,
                'is_cpe_related' => $checkChanges->is_cpe_related,
                'has_awards' => $checkChanges->has_awards,
                'is_involved_organizations' => $checkChanges->is_involved_organizations,
                'awards_details' => $checkChanges->awards_details,
                'org_details' => $checkChanges->org_details,
            ]);

        }
        

        $validated['employed'] = $request->employment_status;
        if($request->notconfirmed == 1) {
            $validated['company_name'] = '';
            $validated['position'] = '';
            $validated['company_address'] = '';
            $validated['industry_sector'] = '';
            $validated['is_cpe_related'] = null;
            $validated['has_awards'] = null;
            $validated['is_involved_organizations'] = null;
            $validated['course_details'] = null;
            $validated['awards_details'] = null;
            $validated['org_details'] = null;
        }
        User::where('id', auth()->id())->update([
            'email' => $validated['email'],
            'employer_email' => $validated['employer_email'],
            'graduation_year' => $validated['graduation_year'],
            'gender' => $validated['gender']
        ]);

        if ($request->hasFile('photo')) {
            if ($graduate->photo) {
                Storage::delete($graduate->photo);
            }
            $validated['photo'] = $request->file('photo')->store('graduate-photos', 'public');
        }


        $graduate->update($validated);

        return redirect()->route('dashboard')->with('success', 'Graduate survey updated successfully!');
    }
}