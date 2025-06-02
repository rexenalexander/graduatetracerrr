@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4"> <span class="text-warning">{{$graduateinfo->firstname}} {{$graduateinfo->middlename}} {{$graduateinfo->lastname}}</span>'s Employment History</h1>
    </div>

    @if($history || $current)
        @php
            $index = 0;
        @endphp
        @foreach($current as $cur)
        <div class="card shadow-sm mt-3 position-relative">
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-circle bg-primary" 
                style="font-size: 1rem; z-index: 1; margin-left: 0; margin-top: 0.5rem; padding: 0.6rem; padding-right: 0.8rem; padding-left: 0.8rem;">
                {{ $index + 1 }}
            </span>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Job Title</h5>
                        <p class="mb-2"> {{$cur->position}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Company Name</h5>
                        <p class="mb-2"> {{$cur->company_name ? $cur->company_name : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Company Address</h5>
                        <p class="mb-2"> {{$cur->company_address ? $cur->company_address : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Industry/Sector</h5>
                        <p class="mb-2"> {{$cur->industry_sector ? $cur->industry_sector : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Is CPE related?</h5>
                        <p class="mb-2"> {{$cur->is_cpe_related ? "Yes" : "No"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Organization</h5>
                        <p class="mb-2"> {{$cur->org_details ? $cur->org_details : "None"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Awards</h5>
                        <p class="mb-2"> {{$cur->awards_details ? $cur->awards_details : "None"}} </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @php
            $index = 1;
        @endphp
        @foreach($history as $his)
        <div class="card shadow-sm mt-3 position-relative">
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-circle bg-primary" 
                style="font-size: 1rem; z-index: 1; margin-left: 0; margin-top: 0.5rem; padding: 0.6rem; padding-right: 0.8rem; padding-left: 0.8rem;">
                {{ $index + 1 }}
            </span>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Job Title</h5>
                        <p class="mb-2"> {{$his->position}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Company Name</h5>
                        <p class="mb-2"> {{$his->company_name ? $his->company_name : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Company Address</h5>
                        <p class="mb-2"> {{$his->company_address ? $his->company_address : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Industry/Sector</h5>
                        <p class="mb-2"> {{$his->industry_sector ? $his->industry_sector : "Not Specified"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Is CPE related?</h5>
                        <p class="mb-2"> {{$his->is_cpe_related ? "Yes" : "No"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Organization</h5>
                        <p class="mb-2"> {{$his->org_details ? $his->org_details : "None"}} </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="h6 mb-0">Awards</h5>
                        <p class="mb-2"> {{$his->awards_details ? $his->awards_details : "None"}} </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    @else
        <div class="card shadow-sm ">
            <div class="card-body">
                <div class="d-flex">
                    No employment History!
                </div>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    
</script>
@endpush
@endsection
