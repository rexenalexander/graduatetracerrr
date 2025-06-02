@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Import Graduate List</h1>
        <!-- Year Filter Dropdown -->
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold">Reminders:</h6>
            <div class="ms-3 fst-italic">
                <li>Upon uploading, it will update the data of graduates when already exist, otherwise it will create new record</li>
                <li>May take up some time when uploading the list, depending on the file size</li>
                <li>Make sure that your excel file data are correct, as it will not upload all data when error encounters</li>
                <li>The default password for accounts is auto-generated</li>
            </div>
            
        <div class="d-flex mt-4">
            <!-- Excel Upload Form -->
            <form action="{{ route('admin.graduates.importlist') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <label for="lifelong_learner" class="form-label">Upload Excel:</label>
                    <input type="file" name="excel_file" class="form-control border p-2" accept=".xlsx,.xls" required>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    
</script>
@endpush
@endsection
