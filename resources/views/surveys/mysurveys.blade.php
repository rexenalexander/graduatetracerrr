@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3">Survey List</h2>
        @if (auth()->user()->role == 2)
        <a href="{{ route('employers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Survey
        </a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Graduate/Employee Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($graduates as $graduate)
                        <tr>
                            <td>{{ $graduate->firstname }} {{ $graduate->middlename }} {{ $graduate->lastname }}</td>
                            <td>{{ $graduate->email }}</td>
                            <td>{{ $graduate->company_name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-survey" data-graduate-id="{{ $graduate->id }}">
                                    View 
                                </button>
                                <a href="{{ route('employers.edit', $graduate->id) }}"
                                        class="btn btn-sm btn-success">Edit
                                </a>
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
                <h5 class="modal-title">Employer Survey Details</h5>
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
                                            <small class="text-muted d-block">Employee Name</small>
                                            <strong class="employer-name"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Company</small>
                                            <strong class="employer-company_name"></strong>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted d-block">Company Address</small>
                                            <strong class="employer-company_name-address"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Survey Information -->
                        <div class="col-12 employer-details">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2 mb-3 employer-title">Survey Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How would you rate the graduate's overall performance?</small>
                                            <strong class="employer-q1"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">What are the graduate's strengths?
                                            </small>
                                            <strong class="employer-q2"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">What areas need improvement?</small>
                                            <strong class="employer-q3"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How well did the graduate adapt to the work environment?</small>
                                            <strong class="employer-q5"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How quickly did the graduate learn and apply new concepts or skills?</small>
                                            <strong class="employer-q6"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How effective were the graduate's communication skills within the team?</small>
                                            <strong class="employer-q7"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How well did the graduate work with colleagues and supervisors?</small>
                                            <strong class="employer-q8"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How would you rate the graduate’s ability to solve problems independently?</small>
                                            <strong class="employer-q9"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Did the graduate demonstrate good decision-making skills in challenging situations?</small>
                                            <strong class="employer-q10"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How would you rate the graduate's work ethic and punctuality?</small>
                                            <strong class="employer-q11"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">How professional was the graduate in their conduct and appearance?</small>
                                            <strong class="employer-q12"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Do you believe the graduate has the potential for growth and advancement in the field?</small>
                                            <strong class="employer-q13"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Would you hire this graduate again or recommend them for similar positions in the future?</small>
                                            <strong class="employer-q14"></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Any additional feedback or comments on how the graduate can improve in the future?</small>
                                            <strong class="employer-q15"></strong>
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
            
            const employerId = this.dataset.graduateId;
            const name = this.closest('tr').querySelector('td:first-child').textContent;
            
            // Show loading state
            loadingSpinner.style.display = 'block';
            surveyContent.style.display = 'none';

            // Fetch survey data
            fetch(`/survey-details/${employerId}`)
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        loadingSpinner.style.display = 'none';
                        surveyContent.style.opacity = '0';
                        surveyContent.style.display = 'block';

                        // Update content
                        document.querySelector('.employer-title').textContent = "Survey Details";
                        document.querySelector('.employer-name').textContent = data.grad_name;
                        document.querySelector('.employer-company_name').textContent = data.company_name;
                        document.querySelector('.employer-company_name-address').textContent = data.company_address;

                        document.querySelector('.employer-q1').textContent = data.q1;
                        document.querySelector('.employer-q2').textContent = data.q2;
                        document.querySelector('.employer-q3').textContent = data.q3;
                        document.querySelector('.employer-q5').textContent = data.q5;
                        document.querySelector('.employer-q6').textContent = data.q6;
                        document.querySelector('.employer-q7').textContent = data.q7;
                        document.querySelector('.employer-q8').textContent = data.q8;
                        document.querySelector('.employer-q9').textContent = data.q9;
                        document.querySelector('.employer-q10').textContent = data.q10;
                        document.querySelector('.employer-q11').textContent = data.q11;
                        document.querySelector('.employer-q12').textContent = data.q12;
                        document.querySelector('.employer-q13').textContent = data.q13;
                        document.querySelector('.employer-q14').textContent = data.q14;
                        document.querySelector('.employer-q15').textContent = data.q15;

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
