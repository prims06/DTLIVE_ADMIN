<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Rent_Transaction;
use Illuminate\Http\Request;
use Exception;

// 1- Video, 2- Show, 3- Category, 4-Language, 5- Upcoming, 6- Channel, 7- Kids
class RentTransactionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            // Rent Expiry
            $this->common->rent_expiry();
            $producer = Producer_Data();

            $params['data'] = [];
            // Year
            $params['year_sum'] = Rent_Transaction::whereYear('created_at', date('Y'))
             ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Month
            $params['month_sum'] = Rent_Transaction::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Today
            $params['today_sum'] = Rent_Transaction::whereDate('created_at', date('Y-m-d'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();

            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_search = $request['input_search'];

                $query = Rent_Transaction::where('producer_id', $producer['id']);
                if (!empty($input_search)) {
                    $query->where('transaction_id', 'LIKE', "%{$input_search}%");
                }

                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->with('user')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            $active = __('label.active');
                            return "<button type='button' class='show-btn'>" . $active . "</button>";
                        } else {
                            $expiry = __('label.expiry');
                            return "<button type='button' class='hide-btn'>" . $expiry . "</button>";
                        }
                    })
                    ->addColumn('video_name', function ($row) {
                        return Rent_Transaction::getVideoName($row->video_id, $row->video_type, $row->sub_video_type);
                    })
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('producer.rent_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
