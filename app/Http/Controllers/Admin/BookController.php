<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Books\StoreBookRequest;
use App\Http\Requests\Admin\Books\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\InvalidDataExportTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class BookController extends Controller
{
    use ImageTrait;
    use InvalidDataExportTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $importedAt = $request->input('imported_at');

        $books = Book::query()
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('code', 'LIKE', '%' . $search . '%');
                });
            })
            ->when(!empty($importedAt), function ($query) use ($importedAt) {
                $query->whereDate('imported_at', $importedAt);
            })
            ->orderBy('id', 'DESC')
            ->paginate(AppConstants::PAGE);

        $currentPage = $books->currentPage();
        $startIndex = ($currentPage - 1) * AppConstants::PAGE;

        if (!is_numeric($currentPage) || $currentPage < 1 || $currentPage > $books->lastPage()) {
            return redirect()->back();
        }

        $countBooks = $books->total();

        return view('admin.book.index', compact(
            'books',
            'startIndex',
            'countBooks',
            'search',
            'importedAt',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        $description = trim(html_entity_decode(strip_tags($request->input('description')), ENT_QUOTES, 'UTF-8'));

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filenameWithExtension = $this->uploadImage($image, 'public/admin/books', null);
            $imagePath = $filenameWithExtension;
        }

        $createBook = Book::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'imported_at' => $request->input('imported_at'),
            'description' =>  $description,
            'image' =>  $imagePath,
        ]);

        if ($createBook) {
            return redirect()->route('admin.book_list')->with('msg', 'Add book successfully !');
        }
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        return view('admin.book.edit', compact('book'));
    }

     /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $oldImage = $book->image;

        $description = trim(html_entity_decode(strip_tags($request->input('description')), ENT_QUOTES, 'UTF-8'));

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $this->deleteImage('public/admin/books/' . $oldImage);

            $filenameWithExtension = $this->uploadImage($image, 'public/admin/books', null);
            $imagePath = $filenameWithExtension;
        }

        $imageResult = $imagePath ?? $oldImage;

        $createBook =  $book->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'imported_at' => $request->input('imported_at'),
            'description' =>  $description,
            'image' =>  $imageResult,
        ]);

        if ($createBook) {
            return redirect()->route('admin.book_list')->with('msg', 'Edit book successfully !');
        }
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return redirect()->route('admin.book_list')->with('msg', 'Delete book successfully !');
    }

    /**
    * Update the specified resource in storage.
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function importValidate(Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            $fileExtension = $file->getClientOriginalExtension();
            $allowedExtensions = ['xls', 'xlsx', 'csv'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                return back()->with('msg', 'Định dạng tệp tin không được hỗ trợ !');
            }

            $reader = null;
            if ($fileExtension === 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
            }

            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $headerRow = $worksheet->getRowIterator(1)->current();

            $expectedHeaders = ['code', 'name', 'description', 'imported_at'];
            $actualHeaders = [];
            foreach ($headerRow->getCellIterator() as $cell) {
                $actualHeaders[] = $cell->getValue();
            }

            if ($expectedHeaders !== $actualHeaders) {
                return back()->with('msg', 'Tiêu đề file không đúng chuẩn !');
            }

            $validCount = 0;
            $invalidCount = 0;
            $validData = [];
            $invalidData = [];

            $chunkSize = 500;

            $chunkedRows = array_chunk(iterator_to_array($worksheet->getRowIterator(2)), $chunkSize);

            foreach ($chunkedRows as $chunk) {
                foreach ($chunk as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }

                    $rules = [
                        'code' => 'required|min:20|max:50|unique:books,code',
                        'name' => 'required|max:120',
                        'imported_at' => 'required',
                        'description' => 'nullable|max:255',
                    ];

                    $data = [
                        'code' => $rowData[0],
                        'name' => $rowData[1],
                        'imported_at' => $rowData[3],
                        'description' => $rowData[2],
                    ];

                    $validator = Validator::make($data, $rules);

                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        $invalidCount++;
                        $invalidData[] = array_combine(['code', 'name', 'description', 'imported_at'], $rowData);
                    } else {
                        $validCount++;
                        $validData[] = array_combine(['code', 'name', 'description', 'imported_at'], $rowData);
                    }
                }
            }

            $validDataResult = [];
            $invalidDataResult = [];
            $expectedHeaders = ['code', 'name', 'description', 'imported_at'];
            $storagePath = 'public/files_valid';
            $fileValid = '';
            $fileInvalid = '';

            if ($validCount > 0) {
                $validFileName = 'valid_data_' . now()->format('Ymd_His') . '.csv';
                $this->saveToCsvFile($validData, $expectedHeaders, $validFileName);
                $fileValid = $validFileName;

                $validDataResult = $this->getRecordsFromCsvFile($storagePath . '/' . $validFileName, $expectedHeaders);
            }

            if ($invalidCount > 0) {
                $validFileName = 'invalid_data_' . now()->format('Ymd_His') . '.csv';
                $this->saveToCsvFile($invalidData, $expectedHeaders, $validFileName);
                $fileInvalid = $validFileName;

                $invalidDataResult = $this->getRecordsFromCsvFile($storagePath . '/' . $validFileName, $expectedHeaders);
            }

            return view('admin.book.display_data', compact(
                'validCount',
                'invalidCount',
                'validDataResult',
                'invalidDataResult',
                'fileValid',
                'fileInvalid',
            ));
        } else {
            return back();
        }
    }
    private function prepareDataForInsert($data)
    {
        $preparedData = [];

        foreach ($data as $item) {
            $importedAt = Carbon::parse($item['imported_at'])->format('Y-m-d H:i:s');

            $preparedData[] = [
                'code' => $item['code'],
                'name' => $item['name'],
                'description' => $item['description'],
                'imported_at' => $importedAt,
            ];
        }

        return $preparedData;
    }

    public function importPreview($nameFile)
    {
        $storagePath = 'public/files_valid';
        $validFileName = $nameFile;

        $expectedHeaders = ['code', 'name', 'description', 'imported_at'];
        $validDataResult = $this->getAllRecordsFromCsvFile($storagePath . '/' . $validFileName, $expectedHeaders);

        $chunkSize = 500;

        foreach (array_chunk($validDataResult, $chunkSize) as $chunk) {
            Book::insert($this->prepareDataForInsert($chunk));
        }

        Storage::delete($storagePath . '/' . $validFileName);

        return redirect()->route('admin.book_list')->with('msg', 'Dữ liệu đã được import thành công');
    }

    public function downloadInvalidData($nameFile)
    {
        $storagePath = 'public/files_valid';
        $invalidFileName = $nameFile;

        $expectedHeaders = ['code', 'name', 'description', 'imported_at'];
        $invalidDataResult = $this->getAllRecordsFromCsvFile($storagePath . '/' . $invalidFileName, $expectedHeaders);

        $fileName = 'invalid_data.csv';

        if (isset($invalidFileName)) {
            $exportResponse = $this->exportInvalidData($invalidDataResult, $fileName);
            
            return $exportResponse;
        } else {
            abort(404);
        }
    }


    private function saveToCsvFile($data, $headers, $fileName)
    {
        $storagePath = 'public/files_valid';

        $fileContent = implode(',', $headers) . "\n";
        foreach ($data as $row) {
            $fileContent .= implode(',', $row) . "\n";
        }

        Storage::put($storagePath . '/' . $fileName, $fileContent);
    }

    private function getRecordsFromCsvFile($filePath, $header)
    {
        $fileContent = Storage::get($filePath);

        $rows = explode("\n", $fileContent);

        $records = array_slice($rows, 1, 12);

        $data = [];
        foreach ($records as $row) {
            $rowData = str_getcsv($row);

            $record = array_combine($header, $rowData);

            $data[] = $record;
        }

        return $data;
    }

    private function getAllRecordsFromCsvFile($filePath, $header)
    {
        $fileContent = Storage::get($filePath);

        $rows = explode("\n", $fileContent);

        $records = array_slice($rows, 1);

        $data = [];
        foreach ($records as $row) {
            $rowData = str_getcsv($row);

            if (count($header) === count($rowData)) {
                $record = array_combine($header, $rowData);
                $data[] = $record;
            }
        }

        return $data;
    }
}
