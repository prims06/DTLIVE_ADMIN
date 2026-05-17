<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Producer;
use App\Models\Shorts;
use App\Models\TVShow;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;

class ProducerController extends Controller
{
    private $folder = "producer";
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $input_search = $request['input_search'];

            $query = Producer::latest();
            if ($input_search) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('full_name', 'LIKE', "%{$input_search}%")
                        ->orWhere('user_name', 'LIKE', "%{$input_search}%")
                        ->orWhere('email', 'LIKE', "%{$input_search}%")
                        ->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                });
            }
            $params['data'] = $query->paginate(15);

            foreach ($params['data'] as $value) {

                $value['image'] = $this->common->getImage($this->folder, $value['image'], 'profile', $value['storage_type']);
                $value['movies_count'] = Video::where('producer_id', $value['id'])->count();
                $value['tvshow_count'] = TVShow::where('producer_id', $value['id'])->count();
                $value['shorts_count'] = Shorts::where('producer_id', $value['id'])->count();
            }
            return view('admin.producer.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.producer.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|min:2|unique:tbl_producer,user_name',
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_producer,email|email',
                'password' => 'required|min:4',
                'mobile_number' => 'required|numeric|unique:tbl_producer,mobile_number',
                'image' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            $requestData['password'] = Hash::make($requestData['password']);
            if (isset($requestData['image'])) {
                $file = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($file, $this->folder, 'prod_', $requestData['storage_type']);
            }
            $requestData['wallet'] = 0;

            $data = Producer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_producer')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_producer')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = Producer::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);
                return view('admin.producer.edit', $params);
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
                'user_name' => 'required|min:2|unique:tbl_producer,user_name,' . $id,
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_producer,email,' . $id,
                'mobile_number' => 'required|numeric|unique:tbl_producer,mobile_number,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($requestData['password'])) {
                $requestData['password'] = Hash::make($requestData['password']);
            } else {
                unset($requestData['password']);
            }

            if (isset($requestData['image'])) {

                $file = $requestData['image'];
                $requestData['storage_type'] = Storage_Type();
                $requestData['image'] = $this->common->saveImage($file, $this->folder, 'prod_', $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']), $request['old_storage_type']);
            }
            unset($requestData['old_image'], $requestData['old_storage_type']);

            $data = Producer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_producer')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_producer')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Producer::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.producer.index')->with('success', __('label.producer_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_index(Request $request, $producer_id, $content_type)
    {
        try {

            $params['producer_id'] = $producer_id;
            $params['content_type'] = $content_type;
            $params['result'] = [];

            $input_search = $request['input_search'];
            $input_rent = $request['input_rent'];
            $input_status = $request['input_status'];

            if ($content_type == 1) {
                $input_premimum = $request['input_premimum'];
                $query = Video::where('producer_id', $producer_id);
            } else if ($content_type == 2) {
                $query = TVShow::where('producer_id', $producer_id);
            } else if ($content_type == 3) {
                $query = Shorts::where('producer_id', $producer_id);
            }
            if ($input_search != null) {
                $query->where('name', 'LIKE', "%{$input_search}%");
            }
            if ($input_rent != null && $input_rent != 0) {
                $query->where('is_rent', 1);
            }
            if ($content_type == 1) {
                if ($input_premimum != null && $input_premimum != 'all') {
                    $query->where('is_premium', $input_premimum);
                }
            }
            if ($input_status != null && $input_status != 'all') {
                $query->where('status', $input_status);
            }
            $params['result'] = $query->with('type')->latest()->paginate(20);

            $this->common->imageNameToUrl($params['result'], 'thumbnail', $this->folder_content, 'portrait');

            return view('admin.producer.content_index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {
            if ($request['content_type'] == 1) {
                $data = Video::where('id', $request->id)->where('producer_id', $request['producer_id'])->first();
            } else if ($request['content_type'] == 2) {
                $data = TVShow::where('id', $request->id)->where('producer_id', $request['producer_id'])->first();
            } else {
                $data = Shorts::where('id', $request->id)->where('producer_id', $request['producer_id'])->first();
            }

            if ($data) {
                $data['status'] = $data['status'] === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data['status']]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
