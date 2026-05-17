<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Common;
use App\Models\Device_Sync;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

// Login Type : 1- OTP, 2- Google, 3- Apple, 4- Normal
class UserController extends Controller
{
    private $folder = "user";
    private $folder_avatar = "avatar";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];
                $input_login_type = $request['input_login_type'];

                $query = User::latest();
                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('email', 'LIKE', "%{$input_search}%")
                            ->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_login_type !== 'all') {
                    $query->where('type', $input_login_type);
                }
                if ($input_type == 'today') {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'month') {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'year') {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->get();

                // Image
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['image_type'] == 1) {
                        $data[$i]['image'] = $this->common->getImage($this->folder, $data[$i]['image'], 'profile', $data[$i]['storage_type']);
                    } else if ($data[$i]['image_type'] == 2) {
                        $data[$i]['image'] = $this->common->getAvatarImage($data[$i]['image'], $this->folder_avatar);
                    }
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_user') . '\');" method="POST"  action="' . route('admin.user.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('admin.user.edit', [$row->id]) . '" class="edit-delete-btn mr-2">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.user.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.user.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number',
                'email' => 'required|unique:tbl_user|email',
                'password' => 'required|min:4',
                'image' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            $emailArray = explode('@', $requestData['email']);
            $requestData['user_name'] = $this->common->userName($emailArray[0]);
            $requestData['password'] = Hash::make($requestData['password']);
            $requestData['storage_type'] = Storage_Type();
            $requestData['image_type'] = 1;
            if (isset($requestData['image'])) {

                $file = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($file, $this->folder, 'user_', $requestData['storage_type']);
            } else {
                $requestData['image'] = "";
            }
            $requestData['type'] = 4;
            $requestData['parent_control_status'] = 0;
            $requestData['parent_control_password'] = "";
            $requestData['status'] = 1;

            $data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_user')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_user')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = User::where('id', $id)->first();
            if ($params['data'] != null) {

                if ($params['data']['image_type'] == 1) {
                    $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);
                } else if ($params['data']['image_type'] == 2) {
                    $params['data']['image'] = $this->common->getAvatarImage($params['data']['image'], $this->folder_avatar);
                }
                return view('admin.user.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_user,email,' . $id,
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($request['image'])) {

                $requestData['image_type'] = 1;
                $requestData['storage_type'] = Storage_Type();
                $file = $request['image'];
                $requestData['image'] = $this->common->saveImage($file, $this->folder, 'user_', $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']), $request['old_storage_type']);
            }
            unset($requestData['old_image'], $requestData['old_storage_type']);

            $data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_user')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_user')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = User::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image'], $data['storage_type']);
                $data->delete();

                Device_Sync::where('user_id', $id)->delete();
                Bookmark::where('user_id', $id)->delete();
            }
            return redirect()->route('admin.user.index')->with('success', __('label.user_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
