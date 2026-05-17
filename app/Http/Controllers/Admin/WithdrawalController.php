<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal_Request;
use App\Models\Common;
use App\Models\Producer;
use App\Models\Provider;
use Illuminate\Http\Request;
use Exception;

class WithdrawalController extends Controller
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
            $params['producer'] = Producer::latest()->get();
            if ($request->ajax()) {

                $input_status = $request['input_status'];
                $input_producer = $request['input_producer'];

                $query = Withdrawal_Request::query();

                if ($input_producer != "all") {
                    $query->where('producer_id', $input_producer);
                }
                if ($input_status != "all") {
                    $query->where('status', $input_status);
                }
                $data = $query->with('producer')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' class='show-btn'>". __('label.completed') . "</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' class='hide-btn'>". __('label.pending') . "</button>";
                        }
                    })
                    ->rawColumns(['status'])
                    ->make(true);
            }
            return view('admin.withdrawal.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Withdrawal_Request::where('id', $id)->first();
            if ($data != null) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
