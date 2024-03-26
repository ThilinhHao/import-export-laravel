@extends('admin.layout.master')

@section('title', 'Import data')

@section('content')
    <div style="margin-bottom: 20px;" class="col-2 ">
        <a style="padding-top: 10px;" class="btn btn-primary" href="{{ route('admin.book_list') }}">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
<div class="row">
    <div class="col-md-6">
        <h3>Dữ liệu hợp lệ</h3>
        <div id="valid-data">
            <p>Tổng số dữ liệu hợp lệ: {{ $validCount }}</p>
            @if (count($validDataResult) > 0)
                <ul>
                    @foreach (array_slice($validDataResult, 0, 10) as $data)
                        <li>{{ $data['code'] }} - {{ $data['name'] }} - {{ $data['description'] }} - {{ $data['imported_at'] }}</li>
                    @endforeach
                </ul>
                @if (count($validDataResult) > 10)
                    <p>...</p>
                @endif
                <a href="{{ route('admin.preview_import', ['name_file' => $fileValid]) }}" class="btn btn-primary">Import dữ liệu</a>

            @else
                <p>Không có dữ liệu hợp lệ</p>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <h3>Dữ liệu không hợp lệ</h3>
        <div id="invalid-data">
            <p>Tổng số dữ liệu không hợp lệ: {{ $invalidCount }}</p>
            @if (count($invalidDataResult) > 0)
                <ul>
                    @foreach (array_slice($invalidDataResult, 0, 10) as $data)
                        <li>{{ $data['code'] }} - {{ $data['name'] }} - {{ $data['description'] }} - {{ $data['imported_at'] }}</li>
                    @endforeach
                </ul>
                @if (count($invalidDataResult) > 10)
                    <p>...</p>
                @endif
                <a href="{{ route('admin.download_invalid_data', ['name_file' => $fileInvalid]) }}" class="btn btn-primary">Download</a>
            @else
                <p>Không có dữ liệu không hợp lệ</p>
            @endif
        </div>
    </div>
</div>

@endsection
