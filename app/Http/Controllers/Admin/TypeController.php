<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Exception;

// Type : 1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts
class TypeController extends Controller
{
    private $folder = "type";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = Type::where('status', 1)->orderby('sort_order', 'asc')->latest()->get();
            if ($request->ajax()) {

                $query = Type::query();

                $input_type = $request['input_type'];
                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                if ($input_type != 0) {
                    $query->where('type', $input_type);
                }
                $data = $query->orderby('status', 'desc')->orderby('sort_order', 'asc')->latest()->get();

                $this->common->imageNameToUrl($data, 'icon', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_type') . '\');" method="POST" action="' . route('admin.type.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_type" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-type="' . $row->type . '" data-icon="' . $row->icon . '" data-storage_type="' . $row->storage_type . '">';
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
            return view('admin.type.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'type' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['icon'])) {
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'type_', $requestData['storage_type']);
            } else {
                $requestData['icon'] = "";
            }
            $requestData['sort_order'] = 0;
            $requestData['status'] = 1;

            $data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_type')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_type')]);
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
                'type' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            if (isset($requestData['icon'])) {
                $requestData['storage_type'] = Storage_Type();
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'type_',  $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_icon']), $request['old_storage_type']);
            }
            unset($requestData['old_icon'], $requestData['old_storage_type']);

            $data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_type')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_type')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Type::where('id', $id)->first();
            if ($data) {
                $this->common->deleteImageToFolder($this->folder, $data['icon'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.type.index')->with('success', __('label.type_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Type::where('id', $id)->first();
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
    // Sortable
    public function TypeSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Type::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
