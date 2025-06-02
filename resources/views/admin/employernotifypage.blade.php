@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Notify Employers</h1>
        <!-- Year Filter Dropdown -->
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            
        <div class="d-flex">
            <!-- Excel Upload Form -->
            <form action="{{ route('admin.graduates.notify') }}" method="POST" enctype="multipart/form-data" class="w-100">
                @csrf
                    <label for="lifelong_learner" class="form-label">Upload Excel:</label>
                    <input type="hidden" name="graduate" value="false">
                    <div class="mb-3">
                        <label for="message_body" class="form-label">Message</label>
                        <textarea class="form-control w-100" id="message_body" name="message_body"rows="7" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="message_footer" class="form-label">Footer</label>
                        <textarea class="form-control w-100" id="message_footer" name="message_footer"rows="4" required></textarea>
                    </div>
                    <input type="file" name="excel_file" class="form-control p-2" accept=".xlsx,.xls" required>
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
