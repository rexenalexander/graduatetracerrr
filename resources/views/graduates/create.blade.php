@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"></h4>
            </div>
            <div class="card-body">
                <form action="{{ route('graduates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <h4 class="text-primary fw-bold text-uppercase mb-2">Personal Details</h4>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name ="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" value="{{ Auth::user()->email }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            $startYear = 1950;
                            $endYear = date('Y');
                        @endphp

                        <div class="col-md-6 mb-3">
                            <label for="graduation_year" class="form-label">Year Graduated</label>
                            <select class="form-select @error('graduation_year') is-invalid @enderror" id="graduation_year"
                                name="graduation_year" required>
                                <option value="">Select year</option>
                                @for ($year = $endYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                            @error('graduation_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- New lifelong learner question for employed users -->
                        <div id="lifelong-learner-question" class="col-md-6 mb-3" style="display: none;">
                            <label for="lifelong_learner" class="form-label">Are you a life long learner? [Did you take another course or went to Masteral, PhD?]</label>
                            <select class="form-select @error('lifelong_learner') is-invalid @enderror"
                                id="lifelong_learner" name="lifelong_learner">
                                <option value="">Select Answer</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('lifelong_learner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="course_details_show"  class="col-md-6 mb-3">
                            <label for="course_details" class="form-label">Specify another course</label>
                            <input type="text" class="form-control @error('course_details') is-invalid @enderror" id="course_details"
                                name="course_details" value="{{ old('course_details') }}">
                            @error('course_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                                required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="facebook" class="form-label">Facebook Account Name</label>
                            <input type="text" class="form-control @error('facebook') is-invalid @enderror" id="facebook"
                                name="facebook" value="{{ old('facebook') }}">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h4 class="text-primary fw-bold text-uppercase mb-2">Employment Details</h4>
                        <div class="col-md-6 mb-3">
                            <label for="employment_status" class="form-label">Employment Status</label>
                            <select class="form-select @error('employment_status') is-invalid @enderror"
                                id="employment_status" name="employment_status" required>
                                <option value="">Select Status</option>
                                <option value="0" {{ old('employment_status') == '0' ? 'selected' : '' }}>Unemployed</option>
                                <option value="1" {{ old('employment_status') == '1' ? 'selected' : '' }}>Employed</option>
                                <option value="2" {{ old('employment_status') == '2' ? 'selected' : '' }}>Self-Employed
                                </option>
                            </select>
                            @error('employment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="employment-details" class="row" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="employer_email" class="form-label">Employer Email</label>
                                <input type="text" class="form-control @error('employer_email') is-invalid @enderror"
                                    id="employer_email" name="employer_email" value="{{ old('employer_email') }}">
                                @error('employer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position/Designation</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror"
                                    id="position" name="position" value="{{ old('position') }}">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Name of Company/Organization</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    id="company_name" name="company_name" value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="company_address" class="form-label">Address of Company</label>
                                <textarea class="form-control @error('company_address') is-invalid @enderror"
                                    id="company_address" name="company_address"
                                    rows="2">{{ old('company_address') }}</textarea>
                                @error('company_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="industry_sector" class="form-label">Industry/Sector</label>
                                <input type="text" class="form-control @error('industry_sector') is-invalid @enderror"
                                    id="industry_sector" name="industry_sector" value="{{ old('industry_sector') }}"
                                    placeholder="Enter industry sector (e.g., IT, Manufacturing, Education)">
                                @error('industry_sector')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="is_cpe_related" class="form-label">Is your work related to Computer
                                    Engineering?</label>
                                <select class="form-select @error('is_cpe_related') is-invalid @enderror"
                                    id="is_cpe_related" name="is_cpe_related">
                                    <option value="">Select Answer</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="has_awards" class="form-label">Received awards/recognition?</label>
                                <select class="form-select @error('has_awards') is-invalid @enderror" id="has_awards"
                                    name="has_awards">
                                    <option value="">Select Answer</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div id="awards_details_show" class="col-md-6 mb-3">
                                <label for="awards_details" class="form-label">What awards?</label>
                                <input type="text" class="form-control @error('awards_details') is-invalid @enderror" id="awards_details"
                                    name="awards_details" value="{{ old('awards_details') }}">
                                @error('awards_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="is_involved_organizations" class="form-label">Involved in professional
                                    organizations?</label>
                                <select class="form-select @error('is_involved_organizations') is-invalid @enderror"
                                    id="is_involved_organizations" name="is_involved_organizations">
                                    <option value="">Select Answer</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div id="org_details_show" class="col-md-6 mb-3">
                                <label for="org_details" class="form-label">What Organization?</label>
                                <input type="text" class="form-control @error('org_details') is-invalid @enderror" id="org_details"
                                    name="org_details" value="{{ old('org_details') }}">
                                @error('org_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            
                        </div>

                        <!--<div class="col-md-6 mb-3">
                                    <label for="photo" class="form-label">Upload Photo</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo"
                                        name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>-->
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Submit Survey</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employmentStatus = document.getElementById('employment_status');
            const employmentDetails = document.getElementById('employment-details');
            const lifelongLearnerQuestion = document.getElementById('lifelong-learner-question');
            const requiredFields = employmentDetails.querySelectorAll('input, select, textarea');
            
            const hasCourse = document.getElementById('lifelong_learner');
            const courseDetail = document.getElementById('course_details_show');
            const courseRequire = document.getElementById('course_details');

            function toggleCourseDetails() {
                if (hasCourse.value === '1') {
                    courseDetail.style.display = 'block';
                    courseRequire.setAttribute('required', 'required');
                } else {
                    courseDetail.style.display = 'none';
                    courseRequire.removeAttribute('required');
                    courseRequire.value = ''; // Optional: Clear the value if hidden
                }
            }

            toggleCourseDetails();
            hasCourse.addEventListener('change', toggleCourseDetails);

            const hasAwards = document.getElementById('has_awards');
            const awardsDetails = document.getElementById('awards_details_show');
            const awardsRequire= document.getElementById('awards_details');

            function toggleAwardsDetails() {
                if (hasAwards.value === '1') {
                    awardsDetails.style.display = 'block';
                    awardsRequire.setAttribute('required', 'required');
                } else {
                    awardsDetails.style.display = 'none';
                    awardsRequire.removeAttribute('required');
                    awardsRequire.value = ''; // Optional: Clear the value if hidden
                }
            }

            toggleAwardsDetails();
            hasAwards.addEventListener('change', toggleAwardsDetails);

            const hasOrgs = document.getElementById('is_involved_organizations');
            const orgsDetail = document.getElementById('org_details_show');
            const orgsRequire = document.getElementById('org_details');

            function toggleOrgsDetails() {
                if (hasOrgs.value === '1') {
                    orgsDetail.style.display = 'block';
                    orgsRequire.setAttribute('required', 'required');

                } else {
                    orgsDetail.style.display = 'none';
                    orgsRequire.removeAttribute('required');
                    orgsRequire.value = ''; // Optional: Clear the value if hidden
                }
            }

            toggleOrgsDetails();
            hasOrgs.addEventListener('change', toggleOrgsDetails);

            function toggleEmploymentDetails(forceShow = false) {
                const value = employmentStatus.value;

                const isEmployed = value === '1' || value === '2';
                const isUnemployed = value === '0';

                const shouldShow = isEmployed || forceShow;

                employmentDetails.style.display = shouldShow ? 'flex' : 'none';
                lifelongLearnerQuestion.style.display = shouldShow ? 'block' : 'none';

                requiredFields.forEach(field => {
                    const excludedIds = ['awards_details', 'course_details', 'org_details'];

                    if (!excludedIds.includes(field.id)) {
                        field.required = shouldShow;
                        field.disabled = !shouldShow;
                    }
                });
            }

            employmentStatus.addEventListener('change', function () {
                const value = employmentStatus.value;

                if (value === '0') {
                    Swal.fire({
                        title: 'Previous Employment',
                        text: 'Have you ever been employed before?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Don't change the select value
                            document.getElementById('notconfirmed').value = 0;
                            toggleEmploymentDetails(true); // force show fields
                        } else {
                            document.getElementById('notconfirmed').value = 1;
                            toggleEmploymentDetails(false); // hide fields
                        }
                    });
                } else {
                    toggleEmploymentDetails(); // normal behavior for employed/self-employed
                }
            });

            // Initialize state
            toggleEmploymentDetails();

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const isEmployed = ['employed', 'self-employed'].includes(employmentStatus.value);

                // Enable disabled fields before submit if employed
                if (isEmployed) {
                    requiredFields.forEach(field => field.disabled = false);
                }

                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Show error messages
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                form.classList.add('was-validated');
            });
        });
    </script>
@endsection