<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Rent_Price_List;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class RentPriceListController extends Controller
{
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

                $query = Rent_Price_List::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('price', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_price') . '\');" method="POST" action="' . route('admin.rent-price-list.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_price" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-price="' . $row->price . '" data-android_product_package="' . $row->android_product_package . '" data-ios_product_package="' . $row->ios_product_package . '" data-web_price_id="' . $row->web_price_id . '">';
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
            return view('admin.rent_price_list.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_price_id'] = $request['web_price_id'] ?? "";
            $requestData['status'] = 1;

            $price_data = Rent_Price_List::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($price_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_price')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_price')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_price_id'] = $request['web_price_id'] ?? "";

            $price_data = Rent_Price_List::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($price_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_price')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_price')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Rent_Price_List::where('id', $id)->first();
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

            $data = Rent_Price_List::where('id', $id)->first();
            if($data != null){
                $data->delete();
            }
            return redirect()->route('admin.rent-price-list.index')->with('success', __('label.price_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
