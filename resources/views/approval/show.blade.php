@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>User Details for Approval</h4>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Valid ID Image:</strong></p>
                    @if($user->valid_id_image)
                        <img src="{{ asset('storage/' . $user->valid_id_image) }}" alt="Valid ID" class="img-fluid mb-3" style="max-width:300px;">
                    @else
                        <p>Not uploaded</p>
                    @endif

                    <form method="POST" action="{{ route('approval.approve', $user) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('approval.reject', $user) }}" class="d-inline ms-2">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                    <a href="{{ route('approval.index') }}" class="btn btn-secondary ms-2">Back to list</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
