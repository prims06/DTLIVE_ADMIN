<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ChannelController extends Controller
{
    private $folder = "channel";
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

                $query = Channel::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest()->get();

                foreach ($data as $key => $value) {
                    $value['portrait_img'] = $this->common->getImage($this->folder, $value['portrait_img'], 'portrait', $value['storage_type']);
                    $value['landscape_img'] = $this->common->getImage($this->folder, $value['landscape_img'], 'landscape', $value['storage_type']);
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_channel') . '\');" method="POST" action="' . route('admin.channel.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_channel" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-portrait_img="' . $row->portrait_img . '" data-landscape_img="' . $row->landscape_img . '" data-is_title="' . $row->is_title . '" data-storage_type="' . $row->storage_type . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id)' class='show-btn'>". __('label.show') ."</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id)' class='hide-btn'>". __('label.hide') ."</button>";
                        }
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.channel.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'is_title' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg',
                'landscape_img' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['portrait_img'])) {

                $file1 = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($file1, $this->folder, 'ch_port_',  $requestData['storage_type']);
            } else {
                $requestData['portrait_img'] = "";
            }
            if (isset($requestData['landscape_img'])) {

                $file2 = $requestData['landscape_img'];
                $requestData['landscape_img'] = $this->common->saveImage($file2, $this->folder, 'ch_land_',  $requestData['storage_type']);
            } else {
                $requestData['landscape_img'] = "";
            }
            $requestData['status'] = 1;

            $data = Channel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_channel')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_channel')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'is_title' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg',
                'landscape_img' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['portrait_img'])) {

                $file1 = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($file1, $this->folder, 'ch_port_', $requestData['storage_type']);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']), $request['old_storage_type']);
            }
            if (isset($requestData['landscape_img'])) {

                $file2 = $requestData['landscape_img'];
                $requestData['landscape_img'] = $this->common->saveImage($file2, $this->folder, 'ch_land_', $requestData['storage_type']);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_landscape_img']), $request['old_storage_type']);
            }
            unset($requestData['old_portrait_img'], $requestData['old_landscape_img'], $requestData['old_storage_type']);

            $data = Channel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_channel')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_channel')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Channel::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['portrait_img'], $data['storage_type']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.channel.index')->with('success', __('label.channel_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Channel::where('id', $id)->first();
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
