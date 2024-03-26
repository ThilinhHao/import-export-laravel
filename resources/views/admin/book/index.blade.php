@extends('admin.layout.master')

@section('title', 'List book')

@section('content')

    <style>
        .popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .popup-content {
        background-color: #fff;
        width: 300px;
        margin: 100px auto;
        padding: 20px;
        border-radius: 5px;
    }

    .popup-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .popup-close:hover {
        color: #000;
    }

    #file-input {
        margin-bottom: 10px;
    }

    #import-button {
        display: block;
        margin-top: 10px;
    }
    </style>

    <div class="pagetitle">
        <h1>Books</h1>
        <nav>
            <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">Home</a>
                    </li>
                <li class="breadcrumb-item active">Books</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if (session()->has('msg'))
                    <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form class="row g-12 d-flex" action="{{ route('admin.book_search') }}" method="get">
                    <div style="display: inline-flex" class="col-4">
                        <a title="Add new category" style="padding-top: 10px" class="btn btn-primary" href="{{ route('admin.book_create') }}">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="me-2">
                            <input type="text" name="search" placeholder="Search" title="Type search keyword" class="form-control" value="{{ !empty($search) ? $search : '' }}">
                        </div>
                        <div class="me-2">
                            <input type="date" class="form-control" name="imported_at" value="{{ !empty($importedAt) ? $importedAt : '' }}">
                        </div>

                        <div class="btn-two-button d-flex">
                            <button id="btn-save" title="Click search keyword" type="submit" class="btn btn-primary me-1">
                                <i class="bi bi-search"></i>
                            </button>
                            <button id="btn-loading" class="btn btn-primary" type="submit" disabled style="margin-right:4px; display:none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                                <a title="Reset" href="{{ route('admin.book_list') }}" style="padding-top: 10px" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
            <div class="col-lg-12">
                <form id="import-form" method="POST" action="{{ route('admin.book_import') }}" enctype="multipart/form-data">
                    @csrf
                    <button id="btn-import" title="Import dữ liệu" type="button" class="btn btn-primary me-1">
                        <i class="bi bi-cloud-upload"></i> Import
                    </button>
                    <div id="import-popup" class="popup">
                        <div class="popup-content">
                            <span class="popup-close">&times;</span>
                            <h3>Chọn file để import</h3>
                            <input type="file" name="file" id="file-input" accept=".xls,.xlsx,.csv">
                            <button type="submit" class="btn btn-primary">Check</button>
                            <button type="button" id="btn-preview" class="btn btn-primary" style="display: none;">Preview</button>
                            <button type="submit" id="btn-import-data" class="btn btn-primary" style="display: none;">Import dữ liệu</button>
                        </div>
                    </div>
                </form>
                <div id="preview-data" style="display: none;">
                    <h3>Dữ liệu chuẩn bị được import</h3>
                    <p><strong>Số dữ liệu thỏa mãn: </strong><span id="valid-count"></span></p>
                    <p><strong>Số dữ liệu không thỏa mãn: </strong><span id="invalid-count"></span></p>
                    <button id="btn-download-invalid" class="btn btn-primary" style="display: none;">Download dữ liệu không thỏa mãn</button>
                </div>
            </div>
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Table with stripped rows -->
                    <table width=100% class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%" scope="col">No</th>
                                <th width="20%" scope="col" class="text-start">Image</th>
                                <th width="10%" scope="col" class="text-start">Name</th>
                                <th width="15%" scope="col" class="text-start">Code book</th>
                                <th width="30%" scope="col" class="text-start">Imported at</th>
                                <th width="20%" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($countBooks > 0)
                                @foreach ($books as $index => $items)
                                    <tr>
                                        <th scope="row">{{ $startIndex + $index + 1 }}</th>
                                        <td class="text-start">
                                            <img src="{{ asset('storage/admin/books/' . $items->image) }}" alt="Book Image" style="width: 100px;">
                                        </td>
                                        <td class="text-start">{{ $items->name }}</td>
                                        <td class="text-start">{{ $items->code }}</td>
                                        <td class="text-start">{{ \Carbon\Carbon::parse($items->imported_at)->format('m/d/Y') }}</td>

                                        <td scope="col">
                                            <a title="Edit" href="{{ route('admin.book_edit', ['id' => $items->id]) }}" type="button" class="btn btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button title="Delete" type="button" class="btn btn-danger" onclick="deleteModal('{{ $items->id }}', '{{ $items->name }}', '{{  route('admin.book_delete.id', ['id' => $items->id]) }}');">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                            <div id="modal"></div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" style="height: 280px;">There are no data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{ $books->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/js/base.js') }}"></script>
    <script>
        document.getElementById('btn-import').addEventListener('click', function() {
            document.getElementById('import-popup').style.display = 'block';
        });

        document.getElementsByClassName('popup-close')[0].addEventListener('click', function() {
            document.getElementById('import-popup').style.display = 'none';
        });

        document.getElementById('import-button').addEventListener('click', function() {
            var fileInput = document.getElementById('file-input');
            var selectedFile = fileInput.files[0];
            console.log(selectedFile);
            document.getElementById('import-popup').style.display = 'none';
        });
    </script>
@endpush
