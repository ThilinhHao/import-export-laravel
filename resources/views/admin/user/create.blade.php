@extends('admin.layout.master')

@section('title', 'Add new user')


@section('content')

@php
    use App\Constants\AppConstants;
@endphp

    <style>
        .ck-editor__editable {
            height: 300px;
        }
    </style>
    <div class="pagetitle">
        <h1>Add new user</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form class="row g-3" novalidate method="post" action="{{ route('users.store') }}" id="form-category" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control input-field" name="name" id="name" value="{{ old('name') }}" placeholder="Please type name">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
                                <br>
                                <span class="text-danger" id="message_avatar"></span>
                                <img src="#" id="preview-image" alt="Preview Image" style="width:200px;display: none;" class="mt-2 pt-2">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control input-field" name="email" id="email" value="{{ old('email') }}" placeholder="Please type email">

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control input-field" name="password" id="password" placeholder="Please type password">

                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="userRole" class="form-label">Role</label>
                                <select name="role" class="form-select" id="userRole">
                                    <option value="">Select role</option>
                                    @foreach (AppConstants::ROLES as $role)
                                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('role')
                                    <span class="text-error-notify" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <div>
                                    <button id="btn-save" class="btn btn-primary" type="submit">Save</button>
                                    <button id="btn-loading" class="btn btn-primary" type="submit" disabled style="display:none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading
                                    </button>
                                        <a href="{{ route('users.list') }}" type="reset" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/js/base.js') }}"></script>
@endpush

