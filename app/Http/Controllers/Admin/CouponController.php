<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $this->common->coupon_expiry();
            $params['data'] = [];
            if ($request->ajax()) {

                $query = Coupon::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_coupon') . '\');" method="POST"  action="' . route('admin.coupon.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_coupon" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-start_date="' . $row->start_date . '" data-end_date="' . $row->end_date . '" data-amount_type="' . $row->amount_type . '" data-price="' . $row->price . '" data-is_use="' . $row->is_use . '" data-is_use_limit="' . $row->is_use_limit . '" data-use_limit="' . $row->use_limit . '">';
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
            return view('admin.coupon.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'start_date' => 'required',
                'end_date' => 'required|date|after_or_equal:start_date',
                'is_use_limit' => 'required',
            ];
            if ($request['amount_type'] == 1) {
                $rules['price'] = 'required';                
            } elseif ($request['amount_type'] == 2) {
                $rules['price'] = 'required|numeric|max:100';                
            }
            if ($request['is_use_limit'] == 1) {
                $rules['use_limit'] = 'required|min:1';                
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['unique_id'] = Str::random(6);
            $requestData['use_limit'] = $request['is_use_limit'] == 1 ? $request['use_limit'] : 0;

            $coupon_data = Coupon::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($coupon_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_coupon')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_coupon')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'start_date' => 'required',
                'end_date' => 'required|date|after_or_equal:start_date',
                'edit_is_use_limit' => 'required',
            ];
            if ($request['amount_type'] == 1) {
                $rules['price'] = 'required';                
            } elseif ($request['amount_type'] == 2) {
                $rules['price'] = 'required|numeric|max:100';                
            }
            if ($request['edit_is_use_limit'] == 1) {
                $rules['use_limit'] = 'required|numeric|min:1';                
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['is_use_limit'] = $requestData['edit_is_use_limit'];
            $requestData['use_limit'] = $request['edit_is_use_limit'] == 1 ? $request['use_limit'] : 0;
            unset($requestData['edit_is_use_limit']);

            $coupon_data = Coupon::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($coupon_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_coupon')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_coupon')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Coupon::where('id', $id)->first();
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
    public function destroy($id)
    {
        try {

            $data = Coupon::where('id', $id)->first();
            if (isset($data)) {
                $data->delete();
            }
            return redirect()->route('admin.coupon.index')->with('success', __('label.coupon_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
