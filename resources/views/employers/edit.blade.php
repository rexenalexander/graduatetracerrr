@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Employer Survey</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('employers.update', $employer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <p class="div">
                        The Department of Computer Engineering is currently conducting a Graduate Tracer Study (GTS) survey to assess the employability of its graduates. Your participation in this endeavor will greatly contribute to the enhancement of the programs offered by the university. Rest assured that all information provided will be treated with the utmost confidentiality. Kindly ensure that you fill out all items in the Google Form survey.
                    </p>
                    <p class="mt-2 fst-italic">
                        <b>Note: </b> By providing your personal data to Mariano Marcos State University, you consent to the collection, use, processing, and storage of your information for the purpose of tracking graduates’ employability outcomes. The data collected will be used to analyze employment trends, assess the effectiveness of academic programs, and support career services offered by the university. Your information may be shared with relevant university departments, accrediting bodies, and authorized third-party organizations involved in educational research or employment tracking under strict confidentiality agreements. Your personal data will be retained as long as necessary to fulfill the purposes outlined above and in accordance with the Data Privacy Act of 2012 and its Implementing Rules and Regulations. You have the right to access, correct, or request the deletion of your data at any time by contacting the Department of Computer Engineering at coe-comeng@mmsu.edu.ph.
                    </p>
                    <input type="hidden" name="id" id="id" value="{{$employer->id}}">

                    <div class="row">
                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">I. Employer's Information</h4>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="tel" class="form-control @error('company_name') is-invalid @enderror"
                                id="company_name" name="company_name" value="{{ old('company_name', $employer->company_name) }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="tel" class="form-control @error('position') is-invalid @enderror"
                                id="position" name="position" value="{{ old('position', $employer->position) }}" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="company_address" class="form-label">Company Address</label>
                            <textarea class="form-control @error('company_address') is-invalid @enderror"
                                id="company_address" name="company_address"
                                rows="2">{{ old('company_address', $employer->company_address) }}</textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="graduate_id" class="form-label">Employee Name</label>
                            <input type="text" class="form-control @error('graduate_id') is-invalid @enderror"
                                id="graduate_id" name="graduate_id" value="{{ old('graduate_id', $employer->fullname) }}" disabled>
                            @error('graduate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">II. Performance Evaluation</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q1" class="form-label">How would you rate the graduate's overall performance?</label>
                            <select class="form-select @error('q1') is-invalid @enderror"
                                id="q1" name="q1" required>
                                <option value=""></option>
                                <option value="1" {{ old('q1', $employer->q1) == '1' ? 'selected' : '' }}>Excellent</option>
                                <option value="2" {{ old('q1', $employer->q1) == '2' ? 'selected' : '' }}>Good</option>
                                <option value="3" {{ old('q1', $employer->q1) == '3' ? 'selected' : '' }}>Average</option>
                                <option value="4" {{ old('q1', $employer->q1) == '4' ? 'selected' : '' }}>Poor</option>
                            </select>
                            @error('q1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="q2" class="form-label">What are the graduate's strengths?</label>
                            <input type="tel" class="form-control @error('q2') is-invalid @enderror"
                                id="q2" name="q2" value="{{ old('q2', $employer->q2) }}" required
                                placeholder="e.g. Technical Skills, Problem-Solving">
                            @error('q2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q3" class="form-label">What areas need improvement?</label>
                            <input type="tel" class="form-control @error('q3') is-invalid @enderror"
                                id="q3" name="q3" value="{{ old('q3',$employer->q3) }}" required
                                placeholder="e.g. Technical Skills, Problem-Solving">
                            @error('q3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">III. Graduate’s Adaptability and Learning Capacity</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q5" class="form-label">How well did the graduate adapt to the work environment?</label>
                            <select class="form-select @error('q5') is-invalid @enderror"
                                id="q5" name="q5" required>
                                <option value=""></option>
                                <option value="1" {{ old('q5', $employer->q5) == '1' ? 'selected' : '' }}>Very Well</option>
                                <option value="2" {{ old('q5', $employer->q5) == '2' ? 'selected' : '' }}>Well</option>
                                <option value="3" {{ old('q5', $employer->q5) == '3' ? 'selected' : '' }}>Fairly Well</option>
                                <option value="4" {{ old('q5', $employer->q5) == '4' ? 'selected' : '' }}>Not well at all</option>
                            </select>
                            @error('q5')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q6" class="form-label">How quickly did the graduate learn and apply new concepts or skills?</label>
                            <select class="form-select @error('q6') is-invalid @enderror"
                                id="q6" name="q6" required>
                                <option value=""></option>
                                <option value="1" {{ old('q6', $employer->q6) == '1' ? 'selected' : '' }}>Very Quickly</option>
                                <option value="2" {{ old('q6', $employer->q6) == '2' ? 'selected' : '' }}>Quickly</option>
                                <option value="3" {{ old('q6', $employer->q6) == '3' ? 'selected' : '' }}>Moderately</option>
                                <option value="4" {{ old('q6', $employer->q6) == '4' ? 'selected' : '' }}>Slowly</option>
                            </select>
                            @error('q6')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">IV. Communication Skills and Collaboration</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q7" class="form-label">How effective were the graduate's communication skills within the team?</label>
                            <select class="form-select @error('q7') is-invalid @enderror"
                                id="q7" name="q7" required>
                                <option value=""></option>
                                <option value="1" {{ old('q7', $employer->q7) == '1' ? 'selected' : '' }}>Very effective</option>
                                <option value="2" {{ old('q7', $employer->q7) == '2' ? 'selected' : '' }}>Effective</option>
                                <option value="3" {{ old('q7', $employer->q7) == '3' ? 'selected' : '' }}>Neutral</option>
                                <option value="4" {{ old('q7', $employer->q7) == '4' ? 'selected' : '' }}>Ineffective</option>
                            </select>
                            @error('q7')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q8" class="form-label">How well did the graduate work with colleagues and supervisors?</label>
                            <select class="form-select @error('q8') is-invalid @enderror"
                                id="q8" name="q8" required>
                                <option value=""></option>
                                <option value="1" {{ old('q8', $employer->q8) == '1' ? 'selected' : '' }}>Very well</option>
                                <option value="2" {{ old('q8', $employer->q8) == '2' ? 'selected' : '' }}>Well</option>
                                <option value="3" {{ old('q8', $employer->q8) == '3' ? 'selected' : '' }}>Adequately</option>
                                <option value="4" {{ old('q8', $employer->q8) == '4' ? 'selected' : '' }}>Poorly</option>
                            </select>
                            @error('q8')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">V. Problem-Solving and Decision-Making</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q9" class="form-label">How would you rate the graduate’s ability to solve problems independently?</label>
                            <select class="form-select @error('q9') is-invalid @enderror"
                                id="q9" name="q9" required>
                                <option value=""></option>
                                <option value="1" {{ old('q9', $employer->q9) == '1' ? 'selected' : '' }}>Excellent</option>
                                <option value="2" {{ old('q9', $employer->q9) == '2' ? 'selected' : '' }}>Good</option>
                                <option value="3" {{ old('q9', $employer->q9) == '3' ? 'selected' : '' }}>Fair</option>
                                <option value="4" {{ old('q9', $employer->q9) == '4' ? 'selected' : '' }}>Poor</option>
                            </select>
                            @error('q9')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q10" class="form-label">Did the graduate demonstrate good decision-making skills in challenging situations?</label>
                            <select class="form-select @error('q10') is-invalid @enderror"
                                id="q10" name="q10" required>
                                <option value=""></option>
                                <option value="1" {{ old('q10', $employer->q10) == '1' ? 'selected' : '' }}>Always</option>
                                <option value="2" {{ old('q10', $employer->q10) == '2' ? 'selected' : '' }}>Often</option>
                                <option value="3" {{ old('q10', $employer->q10) == '3' ? 'selected' : '' }}>Occasionally</option>
                                <option value="4" {{ old('q10', $employer->q10) == '4' ? 'selected' : '' }}>Never</option>
                            </select>
                            @error('q10')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">VI. Work Ethic and Professionalism</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q11" class="form-label">How would you rate the graduate's work ethic and punctuality?</label>
                            <select class="form-select @error('q11') is-invalid @enderror"
                                id="q11" name="q11" required>
                                <option value=""></option>
                                <option value="1" {{ old('q11', $employer->q11) == '1' ? 'selected' : '' }}>Excellent</option>
                                <option value="2" {{ old('q11', $employer->q11) == '2' ? 'selected' : '' }}>Good</option>
                                <option value="3" {{ old('q11', $employer->q11) == '3' ? 'selected' : '' }}>Fair</option>
                                <option value="4" {{ old('q11', $employer->q11) == '4' ? 'selected' : '' }}>Poor</option>
                            </select>
                            @error('q11')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q12" class="form-label">How professional was the graduate in their conduct and appearance?</label>
                            <select class="form-select @error('q12') is-invalid @enderror"
                                id="q12" name="q12" required>
                                <option value=""></option>
                                <option value="1" {{ old('q12', $employer->q12) == '1' ? 'selected' : '' }}>Very professional</option>
                                <option value="2" {{ old('q12', $employer->q12) == '2' ? 'selected' : '' }}>Professional</option>
                                <option value="3" {{ old('q12', $employer->q12) == '3' ? 'selected' : '' }}>Somewhat professional</option>
                                <option value="4" {{ old('q12', $employer->q12) == '4' ? 'selected' : '' }}>Unprofessional</option>
                            </select>
                            @error('q12')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">VII. Long-Term Potential and Growth</h4>

                        <div class="col-md-6 mb-3">
                            <label for="q13" class="form-label">Do you believe the graduate has the potential for growth and advancement in the field?</label>
                            <select class="form-select @error('q13') is-invalid @enderror"
                                id="q13" name="q13" required>
                                <option value=""></option>
                                <option value="1" {{ old('q13', $employer->q13) == '1' ? 'selected' : '' }}>Yes, definitely</option>
                                <option value="2" {{ old('q13', $employer->q13) == '2' ? 'selected' : '' }}>Yes, potentially</option>
                                <option value="3" {{ old('q13', $employer->q13) == '3' ? 'selected' : '' }}>Uncertain</option>
                                <option value="4" {{ old('q13', $employer->q13) == '4' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('q13')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="q14" class="form-label">Would you hire this graduate again or recommend them for similar positions in the future?</label>
                            <select class="form-select @error('q14') is-invalid @enderror"
                                id="q14" name="q14" required>
                                <option value=""></option>
                                <option value="1" {{ old('q14', $employer->q14) == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="2" {{ old('q14', $employer->q14) == '2' ? 'selected' : '' }}>No</option>
                                <option value="3" {{ old('q14', $employer->q14) == '3' ? 'selected' : '' }}>Unsure</option>
                            </select>
                            @error('q14')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <h4 class="text-primary fw-bold text-uppercase mt-3 my-2">Recommendation</h4>
                        
                        <div class="col-md-12 mb-3">
                            <label for="q15" class="form-label">Any additional feedback or comments on how the graduate can improve in the future?</label>
                            <textarea class="form-control @error('q15') is-invalid @enderror"
                                id="q15" name="q15"
                                rows="2">{{ old('q15', $employer->q15) }}</textarea>
                            @error('q15')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Survey</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection