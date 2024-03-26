<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Constants\AppConstants;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Traits\ImageTrait;

class UserController extends Controller
{
    use ImageTrait;
    public function index(Request $request)
    {
        $name = trim($request->input('name'));
        $startDate = $request->input('start_date');

        $users = User::query()
            ->when(!empty($name), function ($query) use ($name) {
                $query->where(function ($subQuery) use ($name) {
                    $subQuery->where('name', 'LIKE', '%' . $name . '%');
                });
            })
            ->when(!empty($startDate), function ($query) use ($startDate) {
                $query->whereDate('created_at', $startDate);
            })
            ->orderBy('id', 'DESC')
            ->paginate(AppConstants::PAGE); // PAGE = 10
 
        $currentPage = $users->currentPage();
        $startIndex = ($currentPage - 1) * AppConstants::PAGE;

        if (!is_numeric($currentPage) || $currentPage < 1 || $currentPage > $users->lastPage()) {
            return redirect()->back();
        }

        return view('admin.user.list_user', compact('users', 'startIndex', 'name', 'startDate'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $avatarPath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filenameWithExtension = $this->uploadImage($image, 'public/admin/users', null);
            $avatarPath = $filenameWithExtension;
        }

        // Lưu thông tin người dùng vào cơ sở dữ liệu
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role');
        $user->avatar = $avatarPath;
        $user->save();

        // Chuyển hướng đến màn hình danh sách người dùng
        return redirect()->route('users.list')->with('msg', 'Add new user successfully!');
    }
    public function edit($id)
    {
        // Lấy thông tin người dùng dựa trên $id
        $user = User::find($id);

        // Trả về view chỉnh sửa người dùng
        return view('admin.user.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $oldAvatar = $user->avatar;
        $avatarPath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $this->deleteImage('public/admin/users/' . $oldAvatar);

            $filenameWithExtension = $this->uploadImage($image, 'public/admin/users', null);
            $avatarPath = $filenameWithExtension;
        }
        $imageResult = $avatarPath ?? $oldAvatar;

        $isUpdated =  $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
            'avatar' =>  $imageResult,
        ]);

        if ($isUpdated) {
            return redirect()->route('users.list')->with('msg', 'Edit user successfully !');
        }
    }

    public function destroy($id)
    {
        // Xóa người dùng dựa trên $id
        User::destroy($id);

        // Điều hướng về trang danh sách người dùng
        return redirect()->route('users.list')->with('msg', 'Delete user successfully!');
    }
}
