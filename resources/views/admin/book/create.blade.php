@extends('admin.layout.master')

@section('title', 'Add new book')

@section('content')
    <style>

        .ck-editor__editable {
                height: 300px;
            }
    </style>
    <div class="pagetitle">
        <h1>Book</h1>
        <nav>
            <ol class="breadcrumb">
                @if (Route::has('admin'))
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin') }}">Dashboard</a>
                    </li>
                @endif
                @if (Route::has('admin.categories.index'))
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.book_list') }}">Book</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">Add new book</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form class="row g-3" method="post" action="{{ route('admin.book_store') }}" id="form-category" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control input-field" name="name" id="name" value="{{ old('name') }}" placeholder="Please type book name">

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Image" class="form-label">Image <span class="text-error-notify">(*)</span></label>
                                <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
                                <br>
                                <span class="text-danger" id="message_avatar"></span>
                                <img src="#" id="preview-image" alt="Preview Image" style="width:200px;display: none;" class="mt-2 pt-2">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}">

                                @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputDate" class="form-label">Imported day<span class="text-danger">&nbsp;(*)</span></label>
                                <input type="datetime-local" class="form-control" name="imported_at" value="{{ old('imported_at') }}">
                                @error('imported_at')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" rows="10" data-sample-short name="description">{{ old('description') }}</textarea>
                                <span id="description-error" class="text-error-notify"></span>
                                @error('description')
                                    <span class="text-error-notify">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <button id="btn-save" class="btn btn-primary" type="submit">Save</button>
                                    <button id="btn-loading" class="btn btn-primary" type="submit" disabled style="display:none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading
                                    </button>
                                        <a href="{{ route('admin.book_list') }}" type="reset" class="btn btn-secondary">Cancel</a>
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
