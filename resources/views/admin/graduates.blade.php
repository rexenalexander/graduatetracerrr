@extends('layouts.app')


@section('content')

<style>
    @media (max-width: 768px) {
            .responsive{
                width: 100%;
            }
        }
</style>
<div class="container-fluid px-4">
    <div class="mb-4">
        <div class="row">
            <div class="col-xl-7 col-lg-6 col-md-12">
                <h1 class="h3  mb-2 mb-lg-0 text-nowrap">Manage Graduates</h1>
            </div>
            <div class="col-xl-5 col-lg-6 col-md-12">
                <!-- Year Filter & Search -->
                <div class="d-flex justify-content-end">
                    <div class="d-md-flex d-block justify-content-end w-100 me-2 flex-wrap">
                        <a href="{{ route('admin.notifypage') }}" class="responsive btn btn-success me-2 mb-2">Notify Graduates</a>
                        <a href="{{ route('admin.importpage') }}" class="responsive btn btn-primary me-2 mb-2">Import Graduates</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <!-- Year Filter & Search -->
                <div class="d-flex justify-content-end">
                    <form method="GET" action="{{ route('admin.graduates') }}" class="d-md-flex d-block justify-content-end w-100 me-2 flex-wrap">
                        @php
                            $currentYear = date('Y');
                            $startYear = 2018;
                            $selectedYear = request('year');
                            $searchTerm = request('searchtext');
                            $employment = request('employment');
                        @endphp
            
                        <div class="d-block d-md-flex">
                            
                        <!-- Year Dropdown -->
                        <select name="year" class="form-control form-control-sm form-control-select px-3 pe-5 me-2 mb-2 rounded-0 responsive" onchange="this.form.submit()">
                            <option value="">Select Year</option>
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
            
                        <!-- Employment Dropdown -->
                        <select name="employment" class="form-control form-control-sm form-control-select px-3 pe-5 me-2 mb-2 rounded-0 responsive" onchange="this.form.submit()">
                            <option value="">Employment Status</option>
                            <option value="0" {{ $employment == '0' ? 'selected' : '' }}>Unemployed</option>
                            <option value="1" {{ $employment == '1' ? 'selected' : '' }}>Employed</option>
                            <option value="2" {{ $employment == '2' ? 'selected' : '' }}>Self-Employed</option>
                        </select>

                        <!-- Search Input -->
                        <input type="text" name="searchtext" value="{{ $searchTerm }}" class="form-control form-control-sm border mb-2 responsive" placeholder="Search graduates...">
                        </div>
            
                        <!-- Buttons -->
                        <button type="submit" class="btn btn-success me-2 mb-2 border-0 rounded-0 responsive">Search</button>
                        <a href="{{ $backUrl }}" class="btn btn-secondary mb-2 border-0 rounded-0 responsive">Back</a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Graduation Year</th>
                            <th>Employment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($graduates as $graduate)
                        <tr>
                            <td>{{ $graduate->user->name }}</td>
                            <td>{{ $graduate->user->email }}</td>
                            <td>{{ $graduate->graduation_year }}</td>
                            <td>
                                @php
                                    if ($graduate->employed == 1)
                                        $employement_status = "employed";
                                    else if ($graduate->employed == 2)
                                        $employement_status = "self-employed";
                                    else
                                        $employement_status = "unemployed";
                                @endphp
                                <span class="badge bg-{{ $graduate->employed == 1 ? 'success' : ($graduate->employed == 2 ? 'primary' : 'secondary') }}">
                                    {{ ucfirst($employement_status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-survey" 
                                        data-graduate-id="{{ $graduate->id }}">
                                    View Survey
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" >
                                    <a href="{{ route('admin.history', $graduate->id) }}" >
                                        History
                                    </a>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $graduates->links() }}
        </div>
    </div>
</div>

<!-- Updated Modal -->
<div class="modal fade" id="surveyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Graduate Survey Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="loading-spinner text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="survey-content" style="display: none;">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2 mb-3">Personal Information</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Full Name</small>
                                            <strong class="graduate-name"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Email</small>
                                            <strong class="graduate-email"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Phone Number</small>
                                            <strong class="graduate-phone"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Gender</small>
                                            <strong class="graduate-gender"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Facebook</small>
                                            <strong class="graduate-facebook"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Graduation Year</small>
                                            <strong class="graduate-year"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="col-12 employment-details">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2 mb-3 employment-title">Employment Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Position</small>
                                            <strong class="graduate-position"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Company</small>
                                            <strong class="graduate-company"></strong>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Company Address</small>
                                            <strong class="graduate-company-address"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Industry</small>
                                            <strong class="graduate-industry"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">CPE Related</small>
                                            <strong class="graduate-cpe-related"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Awards/Recognition</small>
                                            <strong class="graduate-awards"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Awards Details</small>
                                            <strong class="graduate-awards-details"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Professional Organizations</small>
                                            <strong class="graduate-organizations"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Organization Details</small>
                                            <strong class="graduate-org-details"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const surveyModal = new bootstrap.Modal(document.getElementById('surveyModal'), {
        backdrop: 'static',
        keyboard: true
    });
    const modalElement = document.getElementById('surveyModal');
    const loadingSpinner = modalElement.querySelector('.loading-spinner');
    const surveyContent = modalElement.querySelector('.survey-content');
    const employmentDetails = modalElement.querySelector('.employment-details');

    // Close button handler
    modalElement.querySelector('.btn-close').addEventListener('click', () => {
        surveyModal.hide();
    });

    // Reset modal on hidden
    modalElement.addEventListener('hidden.bs.modal', function () {
        loadingSpinner.style.display = 'block';
        surveyContent.style.display = 'none';
        surveyContent.style.opacity = '0';
    });

    document.querySelectorAll('.view-survey').forEach(button => {
        button.addEventListener('click', function() {
            // Show modal first
            surveyModal.show();
            
            const graduateId = this.dataset.graduateId;
            const name = this.closest('tr').querySelector('td:first-child').textContent;
            
            // Show loading state
            loadingSpinner.style.display = 'block';
            surveyContent.style.display = 'none';

            // Fetch survey data
            fetch(`/admin/graduate-survey/${graduateId}`)
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        loadingSpinner.style.display = 'none';
                        surveyContent.style.opacity = '0';
                        surveyContent.style.display = 'block';

                        // Update content
                        document.querySelector('.graduate-name').textContent = data.name;
                        document.querySelector('.graduate-email').textContent = data.email;
                        document.querySelector('.graduate-phone').textContent = data.phone_number;
                        document.querySelector('.graduate-gender').textContent = data.gender;
                        document.querySelector('.graduate-facebook').textContent = data.facebook || 'N/A';
                        document.querySelector('.graduate-year').textContent = data.graduation_year;

                        if (data.company_name || 
                            data.position ||
                            data.company_address ||
                            data.industry_sector ||
                            data.is_cpe_related
                            ) 
                        {
                            if(data.employed) {
                                document.querySelector('.employment-title').textContent = "Employement Details";
                            }
                            else {
                                document.querySelector('.employment-title').textContent = "Previous Employement Details";
                            }
                            employmentDetails.style.display = 'block';
                            document.querySelector('.graduate-position').textContent = data.position;
                            document.querySelector('.graduate-company').textContent = data.company_name;
                            document.querySelector('.graduate-company-address').textContent = data.company_address;
                            document.querySelector('.graduate-industry').textContent = data.industry_sector;
                            document.querySelector('.graduate-cpe-related').textContent = data.is_cpe_related ? 'Yes' : 'No';
                            document.querySelector('.graduate-awards').textContent = data.has_awards ? 'Yes' : 'No';
                            document.querySelector('.graduate-awards-details').textContent = data.awards_details;
                            document.querySelector('.graduate-org-details').textContent = data.org_details;
                            document.querySelector('.graduate-organizations').textContent = data.is_involved_organizations ? 'Yes' : 'No';
                        } else {
                            employmentDetails.style.display = 'none';
                        }

                        // Fade in animation
                        requestAnimationFrame(() => {
                            surveyContent.style.transition = 'opacity 0.3s ease-in-out';
                            surveyContent.style.opacity = '1';
                        });
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.style.display = 'none';
                    surveyContent.innerHTML = `
                        <div class="alert alert-danger">
                            Failed to load survey data. Please try again.
                        </div>`;
                    surveyContent.style.display = 'block';
                });
        });
    });
});
</script>
@endpush
@endsection
