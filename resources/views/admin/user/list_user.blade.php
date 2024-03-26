@extends('admin.layout.master')

@section('title', 'List users')

@section('content')
        <div class="pagetitle">
            <h1>List users</h1>
            <nav>
                <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Home</a>
                        </li>
                    <li class="breadcrumb-item active">Users</li>
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

                    <form class="row g-12 d-flex">
                        <div style="display: inline-flex" class="col-4">
                            <a title="Add new category" style="padding-top: 10px" class="btn btn-primary" href="{{ route('users.create') }}">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>

                        <div class="col-2"></div>

                        <div class="col-6 d-flex justify-content-end">
                            <form action="{{ route('users.list') }}" method="GET" class="d-flex">
                                <div class="me-2">
                                    <input type="text" name="name" placeholder="Search by name" title="Type search keyword" class="form-control" value="{{ request()->input('name') }}">
                                </div>
                                <div class="me-2">
                                    <input type="date" name="start_date" placeholder="Start date" title="Choose start date" class="form-control datepicker" value="{{ request()->input('start_date') }}">
                                </div>
                                <div class="btn-two-button d-flex">
                                    <button id="btn-search" title="Search" type="submit" class="btn btn-primary me-1">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <button id="btn-loading" class="btn btn-primary" type="button" disabled style="margin-right:4px; display:none;">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    </form>
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
                                    <th width="10%" scope="col" class="text-start">ID User</th>
                                    <th width="15%" scope="col" class="text-start">Name</th>
                                    <th width="10%" scope="col" class="text-start">Role</th>
                                    <th width="20%" scope="col" class="text-start">Date created</th>
                                    <th width="20%" scope="col" class="text-start">Image</th>
                                    <th width="20%" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $startIndex + $index + 1 }}</th>
                                        <td>{{ $user->id }}</td>
                                        <td><a href="{{ route('users.edit', $user->id) }}">{{ $user->name }}</a></td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>
                                        <td class="text-start">
                                            <img src="{{ asset('storage/admin/users/' . $user->avatar) }}" alt="User Image" style="width: 100px;">
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a title="Edit" href="{{ route('users.edit', $user->id) }}" type="button" class="btn btn-warning me-2">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa dữ liệu này không?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                {{ $users->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
            
                    </div>
                </div>
            </div>
        </section>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/js/base.js') }}"></script>
@endpush
