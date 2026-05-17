<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Common;
use Illuminate\Http\Request;
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
                $data = $query->where('status', 1)->latest()->get();

                $this->common->imageNameToUrl($data, 'portrait_img', $this->folder, 'portrait');
                $this->common->imageNameToUrl($data, 'landscape_img', $this->folder, 'landscape');

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
            return view('producer.channel.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
