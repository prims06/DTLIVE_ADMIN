<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Common;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class TransactionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            // Package Expiry
            $this->common->package_expiry();

            $params['data'] = [];
            $params['package'] = Package::latest()->get();
            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_package = $request['input_package'];
                $input_search = $request['input_search'];

                $query = Transaction::query();

                if (!empty($input_search)) {
                    $query->where('transaction_id', 'LIKE', "%{$input_search}%");
                }

                if ($input_package != 0) {
                    $query->where('package_id', $input_package);
                }

                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->with('package', 'user')->latest()->get();

                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['date'] = date("Y-m-d", strtotime($data[$i]['created_at']));
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_transaction') . '\');" method="POST"  action="' . route('admin.transaction.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' class='show-btn'>" . __('label.active') . "</button>";
                        } else {
                            return "<button type='button' class='hide-btn'>" . __('label.expiry') . "</button>";
                        }
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create(Request $request)
    {
        try {
            $params['data'] = [];
            $params['user'] = User::where('id', $request->user_id)->first();
            $params['package'] = Package::where('status', 1)->get();

            return view('admin.transaction.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function searchUser(Request $request)
    {
        try {
            $name = $request->name;
            $user = User::orWhere('full_name', 'like', '%' . $name . '%')->orWhere('mobile_number', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->latest()->get();

            $url = url('admin/transaction/create?user_id');
            $text = '<div class="table-responsive table"><table width="100%" class="table table-striped category-table text-center table-bordered"><thead><tr><th>' . __("label.full_name") . '</th><th>' . __("label.mobile_number") . '</th><th>' . __("label.email") . '</th><th>' . __("label.action") . '</th></tr></thead>';
            if ($user->count() > 0) {
                foreach ($user as $row) {

                    $a = '<a class="btn-link" style="color: var(--assest-color) !important; border-bottom: 1px solid var(--assest-color);" href="' . $url . '=' . $row->id . '">' . __("label.select") . '</a>';
                    $text .= '<tr><td>' . $row->full_name . '</td><td>' . $row->mobile_number . '</td><td>' . $row->email . '</td><td>' . $a . '</td></tr>';
                }
            } else {
                $text .= '<tr><td colspan="4">' . __("label.user_not_found") . '</td></tr>';
            }
            $text .= '</table></div>';

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $text]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'package_id' => 'required'
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $package = Package::where('id', $request->package_id)->first();
            $existing_package = Transaction::where('user_id', $request->user_id)->where('status', 1)->orderBy('id', 'desc')->first();

            $duration = '+' . $package->time . ' ' . strtolower($package->type);
            if ($existing_package != null) {
                $baseDate = $existing_package->expiry_date ?? now();
                $expiry_date = date('Y-m-d H:i:s', strtotime($duration, strtotime($baseDate)));
            } else {
                $expiry_date = date('Y-m-d H:i:s', strtotime($duration));
            }

            $Transaction = new Transaction();
            $Transaction->unique_id = "";
            $Transaction->user_id = $request->user_id;
            $Transaction->package_id = $request->package_id;
            $Transaction->transaction_id = 'admin';
            $Transaction->price = $package->price;
            $Transaction->description = 'admin';
            $Transaction->expiry_date = $expiry_date;
            $Transaction->transaction_status = 2;
            $Transaction->status = 1;

            if ($Transaction->save()) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_transaction')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_transaction')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $data = Transaction::where('id', $id)->first();
            if($data != null) {
                $data->delete();
            }
            return redirect()->route('admin.transaction.index')->with('success', __('label.transaction_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
