<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Banner;
use App\Models\Bookmark;
use App\Models\Cast;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\General_Setting;
use App\Models\Language;
use App\Models\Common;
use App\Models\Device_Sync;
use App\Models\Home_Section;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Onboarding_Screen;
use App\Models\Package;
use App\Models\Social_Link;
use App\Models\Package_Detail;
use App\Models\Payment_Option;
use App\Models\Page;
use App\Models\Producer;
use App\Models\Read_Notification;
use App\Models\Rent_Transaction;
use App\Models\Season;
use App\Models\Shorts;
use App\Models\Shorts_Episode;
use App\Models\Transaction;
use App\Models\TVShow;
use App\Models\TVShow_Video;
use App\Models\Type;
use App\Models\User;
use App\Models\Video;
use App\Models\Video_Watch;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

// Video Type = 1-Video, 2-Show, 3-Language, 4-Category, 5-Upcoming, 6-Channel, 7-Kids, 8- Shorts
// Video Upload Type = server_video, external, youtube, live_stream_url
// Trailer Type = server_video, external, youtube
// Subtitle Type = server_video, external

class HomeController extends Controller
{

    private $folder_language = "language";
    private $folder_channel = "channel";
    private $folder_cast = "cast";
    private $folder_category = "category";
    private $folder_app = "app";
    private $folder_avatar = "avatar";
    private $folder_notification = "notification";
    private $folder_type = "type";
    private $folder_user = "user";
    private $folder_content = "content";
    public $common;
    public $page_limit;
    public function __construct()
    {
        try {

            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function general_setting()
    {
        try {

            $list = General_Setting::get();
            $storage_type = General_Setting::where('key', 'app_logo_storage_type')->first();

            foreach ($list as $key => $value) {

                if ($value['key'] == 'app_logo') {
                    $value['value'] = $this->common->getImage($this->folder_app, $value['value'], 'normal', $storage_type['value']);
                }
                if ($value['key'] == 'powered_by_image') {
                    $value['value'] = $this->common->getImage($this->folder_app, $value['value'], 'normal', 1);
                }
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $list);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_payment_option()
    {
        try {

            $return['status'] = 200;
            $return['message'] = __('api_msg.data_retrieved');
            $return['result'] = [];

            $Option_data = Payment_Option::get();
            foreach ($Option_data as $key => $value) {
                $return['result'][$value['name']] = $value;
            }

            return $return;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_pages()
    {
        try {

            $return['status'] = 200;
            $return['message'] = __('api_msg.data_retrieved');
            $return['result'] = [];

            $data = Page::where('status', 1)->get();
            for ($i = 0; $i < count($data); $i++) {
                $return['result'][$i]['title'] = $data[$i]['title'];
                $return['result'][$i]['url'] = route('page.view', $data[$i]['title']);
                $return['result'][$i]['icon'] = $this->common->getImage($this->folder_app, $data[$i]['icon'], 'normal', $data[$i]['storage_type']);
                $return['result'][$i]['page_subtitle'] = $data[$i]['page_subtitle'];
            }
            return $return;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_social_link()
    {
        try {
            $data = Social_Link::get();
            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_app, 'normal');
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_onboarding_screen()
    {
        try {
            $data = Onboarding_Screen::get();
            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_app, 'normal');
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_avatar()
    {
        try {
            $Data = Avatar::where('status', 1)->latest()->get();
            if (sizeof($Data) > 0) {

                $this->common->imageNameToUrl($Data, 'image', $this->folder_avatar, 'profile');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $Data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_category()
    {
        try {
            $Data = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
            if (sizeof($Data) > 0) {

                $this->common->imageNameToUrl($Data, 'image', $this->folder_category, 'normal');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $Data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_language()
    {
        try {
            $Data = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
            if (sizeof($Data) > 0) {

                $this->common->imageNameToUrl($Data, 'image', $this->folder_language, 'normal');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $Data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_channel()
    {
        try {
            $Data = Channel::where('status', 1)->latest()->get();
            if (sizeof($Data) > 0) {

                $this->common->imageNameToUrl($Data, 'portrait_img', $this->folder_channel, 'portrait');
                $this->common->imageNameToUrl($Data, 'landscape_img', $this->folder_channel, 'landscape');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $Data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_type(Request $request)
    {
        try {

            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? "";

            // Check Parent Control
            $user_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($user_status == 1) {
                $Data = Type::where('type', 7)->where('status', 1)->orderBy('sort_order', 'asc')->get();
            } else {
                $Data = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();
            }

            if (sizeof($Data) > 0) {

                $this->common->imageNameToUrl($Data, 'icon', $this->folder_type, 'normal');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $Data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_package(Request $request)
    {
        try {
            $this->common->package_expiry();
            $user_id = $request['user_id'] ?? 0;

            $data['status'] = 200;
            $data['message'] = __('api_msg.data_retrieved');
            $data['result'] = [];

            $Package_Data = Package::select('id', 'package_type', 'name', 'price', 'time', 'type', 'android_product_package', 'ios_product_package', 'web_product_package')->where('status', 1)->orderBy('price', 'asc')->get();
            foreach ($Package_Data as $key => $value) {

                $Transaction_Data = Transaction::where('user_id', $user_id)->where('package_id', $value['id'])->first();
                if (!empty($Transaction_Data) && $Transaction_Data['status'] == 1) {
                    $value['is_buy'] = 1;

                    $active_data = Transaction::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'asc')->first();
                    if ($value['id'] == $active_data['package_id']) {
                        $value['is_active_plan'] = 1;
                    } else {
                        $value['is_active_plan'] = 0;
                    }
                } else {
                    $value['is_buy'] = 0;
                    $value['is_active_plan'] = 0;
                }
                $Data = Package_Detail::where('package_id', $value['id'])->get();
                $value['data'] = $Data;

                $data['result'][] = $value;
            }
            return $data;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_transaction(Request $request)
    {
        try {
            $this->common->package_expiry();

            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'package_id' => 'required|numeric',
                'price' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $package_id = $request['package_id'];
            $price = $request['price'];
            $unique_id = $request['unique_id'] ?? "";
            $transaction_id = $request['transaction_id'] ?? "";
            $description = $request['description'] ?? "";

            $package = Package::where('id', $package_id)->where('status', 1)->first();
            if (empty($package)) {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_package_id'));
            }

            if ($package['package_type'] == 2) {
                $is_buy = Transaction::where('user_id', $user_id)->where('package_id', $package_id)->first();
                if ($is_buy != null) {
                    return $this->common->API_Response(400, __('api_msg.you_have_already_claimed_the_free_package'));
                }
            }

            $existing_package = Transaction::where('user_id', $request->user_id)->where('status', 1)->orderBy('id', 'desc')->first();
            $duration = '+' . $package->time . ' ' . strtolower($package->type);
            if ($existing_package != null) {
                $baseDate = $existing_package->expiry_date ?? now();
                $expiry_date = date('Y-m-d H:i:s', strtotime($duration, strtotime($baseDate)));
            } else {
                $expiry_date = date('Y-m-d H:i:s', strtotime($duration));
            }

            $insert = new Transaction();
            $insert['unique_id'] = $unique_id;
            $insert['user_id'] = $user_id;
            $insert['package_id'] = $package_id;
            $insert['transaction_id'] = $transaction_id;
            $insert['price'] = $price;
            $insert['description'] = $description;
            $insert['expiry_date'] = $expiry_date;
            $insert['transaction_status'] = $package['package_type'] == 2 ? 2 : 1;
            $insert['status'] = 1;
            if ($insert->save()) {

                $user_data = User::where('id', $user_id)->first();
                if ($user_data) {

                    // Send Mail & Notification
                    $check = $this->common->NotificationConfiguration('package_buy');
                    if (isset($check) && $check['status'] == 1) {

                        if ($check['send_mail'] == 1) {
                            $this->common->Send_Mail(2, $user_data['email'], "");
                        }
                        if ($check['send_notification'] == 1) {
                            $title = "Your Subscription is Active!";
                            $this->common->save_notification($title, $user_id);
                        }
                    }
                }

                return $this->common->API_Response(200, __('api_msg.transaction_Successful'), [$insert]);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update_transaction_status(Request $request) // transaction_status :- 1-Processing, 2-Success, 3-Failed	
    {
        try {
            $validation = Validator::make($request->all(), [
                'type' => 'required',
                'user_id' => 'required|numeric',
                'trasaction_id' => 'required|numeric',
                'transaction_status' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request->type;

            if ($type == 1) {
                $data = Transaction::where('id', $request->trasaction_id)->where('user_id', $request->user_id)->first();
            } else if ($type == 2) {
                $data = Rent_Transaction::where('id', $request->trasaction_id)->where('user_id', $request->user_id)->first();
            }

            if ($data != null) {
                $data->update([
                    'transaction_status' => $request->transaction_status,
                    'status' => $request->transaction_status == 3 ? 0 : 1,
                ]);
                return $this->common->API_Response(200, __('api_msg.transaction_status_changed'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_transaction_list(Request $request)
    {
        try {
            $this->common->package_expiry();

            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request->user_id;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            $data = Transaction::where('user_id', $user_id)->with('package')->orderBy('id', 'desc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                foreach ($data as $key => $value) {

                    if ($value['status'] == 1) {

                        $active_data = Transaction::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'asc')->first();
                        if ($value['id'] != $active_data['id']) {
                            $value['is_upcoming'] = 1;
                        } else {
                            $value['is_upcoming'] = 0;
                        }
                    } else {
                        $value['is_upcoming'] = 0;
                    }

                    $value['package_name'] = "";
                    $value['package_price'] = 0;
                    if ($value['package'] != null) {
                        $value['package_name'] = $value['package']['name'];
                        $value['package_price'] = $value['package']['price'];
                    }

                    $value['date'] = $value['created_at']->format('Y-m-d');
                    unset($value['package']);
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_rent_transaction(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'producer_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'video_id' => 'required|numeric',
                'price' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $producer_id = $request['producer_id'];
            $video_type = $request['video_type'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $video_id = $request['video_id'];
            $price = $request['price'];

            if ($video_type == 1) {
                $Rent_Video = Video::where('id', $video_id)->where('video_type', 1)->where('status', 1)->where('is_rent', 1)->first();
            } else if ($video_type == 2) {
                $Rent_Video = TVShow::where('id', $video_id)->where('video_type', 2)->where('status', 1)->where('is_rent', 1)->first();
            } else if ($video_type == 5 || $video_type == 6 || $video_type == 7) {
                if ($sub_video_type == 1) {
                    $Rent_Video = Video::where('id', $video_id)->where('video_type', $video_type)->where('status', 1)->where('is_rent', 1)->first();
                } else if ($sub_video_type == 2) {
                    $Rent_Video = TVShow::where('id', $video_id)->where('video_type', $video_type)->where('status', 1)->where('is_rent', 1)->first();
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_rent_video'));
            }

            if (isset($Rent_Video) && $Rent_Video != null) {

                $baseDate = now();
                $expiry_date = date('Y-m-d H:i:s', strtotime("+$Rent_Video->rent_day days", strtotime($baseDate)));
            } else {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_rent_video'));
            }

            $insert = new Rent_Transaction();
            $insert['unique_id'] = $request['unique_id'] ?? "";
            $insert['user_id'] = $user_id;
            $insert['producer_id'] = $producer_id;
            $insert['video_type'] = $video_type;
            $insert['sub_video_type'] = $sub_video_type;
            $insert['video_id'] = $video_id;
            $insert['price'] = $price;
            $insert['commission'] =  0;
            $insert['producer_earning'] = 0;
            $insert['transaction_id'] = $request['transaction_id'] ?? "";
            $insert['description'] = $request['description'] ?? "";
            $insert['expiry_date'] = $expiry_date;
            $insert['transaction_status'] = 1;
            $insert['status'] = 1;

            if ($producer_id != 0) {

                $commission = Commission();
                $commission_price = round(((int)$price * (int)$commission) / 100);
                $producer_earning = $price - $commission_price;
                $insert['commission'] =  $commission_price;
                $insert['producer_earning'] = $producer_earning;
            }

            if ($insert->save()) {

                $producer = Producer::where('id', $producer_id)->first();
                if ($producer != null) {
                    $producer->increment('wallet', $insert['producer_earning']);
                }

                $user_data = User::where('id', $user_id)->first();
                if (isset($user_data) && $user_data != null) {

                    // Send Mail & Notification
                    $check = $this->common->NotificationConfiguration('rent_buy');
                    if (isset($check) && $check['status'] == 1) {

                        if ($check['send_mail'] == 1) {
                            $this->common->Send_Mail(3, $user_data['email'], $Rent_Video->name);
                        }

                        if ($check['send_notification'] == 1) {
                            $title = "Thank You for Renting *$Rent_Video->name*! Happy Watching";
                            $this->common->save_notification($title, $user_id);
                        }
                    }
                }
                return $this->common->API_Response(200, __('api_msg.transaction_Successful'), [$insert]);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_coupon_list(Request $request)
    {
        try {
            $this->common->coupon_expiry();

            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            $data = Coupon::where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function apply_coupon(Request $request) // apply_coupon_type : 1- Package, 2- Rent Video
    {
        try {
            if ($request['apply_coupon_type'] == 1) {
                $rules = [
                    'user_id' => 'required|numeric',
                    'package_id' => 'required|numeric',
                    'unique_id' => 'required',
                ];
            } elseif ($request['apply_coupon_type'] == 2) {
                $rules = [
                    'user_id' => 'required|numeric',
                    'unique_id' => 'required',
                    'video_type' => 'required|numeric',
                    'sub_video_type' => 'numeric',
                    'video_id' => 'required|numeric',
                    'price' => 'required|numeric',
                ];
            } else {
                $rules['apply_coupon_type'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->common->API_Response(400, $validator->errors()->first());
            }

            $user_id = $request['user_id'];
            $unique_id = $request['unique_id'];
            $date = date("Y-m-d");
            $array = array();

            $coupon_check = Coupon::where('unique_id', $unique_id)->where('status', 1)->first();
            if (isset($coupon_check) && $coupon_check != null) {

                if ($coupon_check['start_date'] > $date) {
                    return $this->common->API_Response(400, __('api_msg.coupon_not_start'));
                }
                if ($coupon_check['end_date'] < $date) {
                    $coupon_check->update(['status' => 0]);
                    return $this->common->API_Response(400, __('api_msg.coupon_expriy'));
                }

                if ($coupon_check['is_use'] == 1) { // Single Use	

                    if ($coupon_check['is_use_limit'] == 1) { // 0-No 1-Yes

                        $total_tran_use = Transaction::where('unique_id', $coupon_check['unique_id'])->count();
                        $total_rent_tran_use = Rent_Transaction::where('unique_id', $coupon_check['unique_id'])->count();

                        if ($total_tran_use + $total_rent_tran_use >= $coupon_check['use_limit']) {

                            $coupon_check->update(['status' => 0]);
                            return $this->common->API_Response(400, __('api_msg.this_coupon_reached_maximum_usage_limit'));
                        }
                    }

                    $use_check = Transaction::where('user_id', $user_id)->where('unique_id', $coupon_check['unique_id'])->first();
                    $rent_use_check = Rent_Transaction::where('user_id', $user_id)->where('unique_id', $coupon_check['unique_id'])->first();
                    if (isset($use_check) || isset($rent_use_check)) {
                        return $this->common->API_Response(400, __('api_msg.coupon_already_use'));
                    }
                } else { // Multiple Use

                    if ($coupon_check['is_use_limit'] == 1) {

                        $total_tran_use = Transaction::where('unique_id', $coupon_check['unique_id'])->count();
                        $total_rent_tran_use = Rent_Transaction::where('unique_id', $coupon_check['unique_id'])->count();

                        if ($total_tran_use + $total_rent_tran_use >= $coupon_check['use_limit']) {

                            $coupon_check->update(['status' => 0]);
                            return $this->common->API_Response(400, __('api_msg.this_coupon_reached_maximum_usage_limit'));
                        }
                    }
                }

                if ($request['apply_coupon_type'] == 1) {

                    $package = Package::where('id', $request['package_id'])->where('status', 1)->first();
                    if (empty($package)) {
                        return $this->common->API_Response(400, __('api_msg.please_enter_right_package_id'));
                    }

                    $discount_amount = 0;
                    if ($coupon_check['amount_type'] == 1) {

                        $discount_amount = $package['price'] - $coupon_check['price'];
                    } elseif ($coupon_check['amount_type'] == 2) {

                        $minus_amount = ($coupon_check['price'] / 100) * $package['price'];
                        $discount_amount = $package['price'] - $minus_amount;

                        if ($discount_amount > $package['price']) {
                            $discount_amount = 0;
                        }
                    }
                    $discount_amount = max($discount_amount, 0);

                    $array = array(
                        'id' => $coupon_check['id'],
                        'unique_id' => $unique_id,
                        'total_amount' => $package['price'],
                        'discount_amount' => (float)$discount_amount,
                    );
                } elseif ($request['apply_coupon_type'] == 2) {

                    if ($request['video_type'] == 1) {
                        $Rent_Video = Video::where('id', $request['video_id'])->where('video_type', 1)->where('status', 1)->where('is_rent', 1)->first();
                    } else if ($request['video_type'] == 2) {
                        $Rent_Video = TVShow::where('id', $request['video_id'])->where('video_type', 2)->where('status', 1)->where('is_rent', 1)->first();
                    } else if ($request['video_type'] == 5 || $request['video_type'] == 6 || $request['video_type'] == 7) {
                        if ($request['sub_video_type'] == 1) {
                            $Rent_Video = Video::where('id', $request['video_id'])->where('video_type', $request['video_type'])->where('status', 1)->where('is_rent', 1)->first();
                        } else if ($request['sub_video_type'] == 2) {
                            $Rent_Video = TVShow::where('id', $request['video_id'])->where('video_type', $request['video_type'])->where('status', 1)->where('is_rent', 1)->first();
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.please_enter_right_rent_video'));
                    }

                    $discount_amount = 0;
                    if ($coupon_check['amount_type'] == 1) {

                        $discount_amount = $Rent_Video['price'] - $coupon_check['price'];
                    } elseif ($coupon_check['amount_type'] == 2) {

                        $minus_amount = ($coupon_check['price'] / 100) * $Rent_Video['price'];
                        $discount_amount = $Rent_Video['price'] - $minus_amount;

                        if ($discount_amount > $Rent_Video['price']) {
                            $discount_amount = 0;
                        }
                    }
                    $discount_amount = max($discount_amount, 0);

                    $array = array(
                        'id' => $coupon_check['id'],
                        'unique_id' => $unique_id,
                        'total_amount' => $Rent_Video['price'],
                        'discount_amount' => (float)$discount_amount,
                    );
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.coupon_id_worng'));
            }

            return $this->common->API_Response(200, __('api_msg.coupon_apply_successfully'), $array);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function user_rent_content_list(Request $request) // type : 1- Video, 2- Show
    {
        try {
            $this->common->rent_expiry();

            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $is_kids_profile = $request['is_kids_profile'] ?? 0;
            $type = $request['type'] ?? 0;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            if (isset($type) && $type != 0) {

                $video_ids = [];
                $Rent_Data = Rent_Transaction::where('user_id', $user_id)->where('status', 1)->get();
                if ($type == 1) {

                    foreach ($Rent_Data as $value) {
                        if ($value['video_type'] == 1 || (in_array($value['video_type'], [5, 6, 7]) && $value['sub_video_type'] == 1)) {
                            $video_ids[] = $value['video_id'];
                        }
                    }
                    $data = Video::whereIn('id', $video_ids)->where('status', 1)->orderBy('id', 'desc');
                } else if ($type == 2) {

                    foreach ($Rent_Data as $key => $value) {
                        if ($value['video_type'] == 2 || (in_array($value['video_type'], [5, 6, 7]) && $value['sub_video_type'] == 2)) {
                            $video_ids[] = $value['video_id'];
                        }
                    }
                    $data = TVShow::whereIn('id', $video_ids)->where('status', 1)->orderBy('id', 'desc');
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }

                $total_rows = $data->count();
                $total_page = $this->page_limit;
                $page_size = ceil($total_rows / $total_page);
                $offset = $page_no * $total_page - $total_page;

                $more_page = $this->common->more_page($page_no, $page_size);
                $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

                $data->take($total_page)->offset($offset);
                $data = $data->latest()->get();

                if (count($data) > 0) {

                    $this->common->add_url_to_array($type, $data);
                    $this->common->rent_price_list($data);

                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {

                $video_ids = [];
                $tvshow_ids = [];

                $Rent_Data = Rent_Transaction::where('user_id', $user_id)->where('status', 1)->get();
                foreach ($Rent_Data as $key => $value) {

                    if ($value['video_type'] == 1 || (in_array($value['video_type'], [5, 6, 7]) && $value['sub_video_type'] == 1)) {
                        $video_ids[] = $value['video_id'];
                    } else if ($value['video_type'] == 2 || (in_array($value['video_type'], [5, 6, 7]) && $value['sub_video_type'] == 2)) {
                        $tvshow_ids[] = $value['video_id'];
                    }
                }

                $video_data = Video::whereIn('id', $video_ids)->where('status', 1)->orderBy('id', 'desc')->get();
                $tvshow_data = TVShow::whereIn('id', $tvshow_ids)->where('status', 1)->orderBy('id', 'desc')->get();

                $this->common->add_url_to_array(1, $video_data);
                $this->common->rent_price_list($video_data);
                for ($i = 0; $i < count($video_data); $i++) {

                    $video_sub_type = 0;
                    if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                        $video_sub_type = 1;
                    }

                    $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                    $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                    $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $is_kids_profile);
                    $video_data[$i]['sub_video_type'] = $video_sub_type;
                    $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
                }

                $this->common->add_url_to_array(2, $tvshow_data);
                $this->common->rent_price_list($tvshow_data);
                for ($i = 0; $i < count($tvshow_data); $i++) {

                    $show_sub_type = 0;
                    if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                        $show_sub_type = 2;
                    }

                    $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                    $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                    $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $is_kids_profile);
                    $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                    $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
                }

                $video_data = $video_data->toArray();
                $tvshow_data = $tvshow_data->toArray();

                $fin_array = array_merge($video_data, $tvshow_data);

                usort($fin_array, function ($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });

                $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);
                $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $data = $paginator->items();

                if (count($data) > 0) {
                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function rent_content_list(Request $request) // type : 1- Video, 2- Show
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $type = $request['type'] ?? 0;
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? "";
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            if (isset($type) && $type != 0) {

                // Check Parent Control
                $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
                if ($user_parent_control_status == 1) {

                    if ($type == 1) {
                        $data = Video::where('is_rent', 1)->where('video_type', 7)->where('status', 1)->orderBy('id', 'desc');
                    } else if ($type == 2) {
                        $data = TVShow::where('is_rent', 1)->where('video_type', 7)->where('status', 1)->orderBy('id', 'desc');
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else {

                    if ($type == 1) {
                        $data = Video::where('is_rent', 1)->whereIn('video_type', [1, 6, 7])->where('status', 1)->orderBy('id', 'desc');
                    } else if ($type == 2) {
                        $data = TVShow::where('is_rent', 1)->whereIn('video_type', [2, 6, 7])->where('status', 1)->orderBy('id', 'desc');
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                }

                $total_rows = $data->count();
                $total_page = $this->page_limit;
                $page_size = ceil($total_rows / $total_page);
                $offset = $page_no * $total_page - $total_page;

                $more_page = $this->common->more_page($page_no, $page_size);
                $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

                $data->take($total_page)->offset($offset);
                $data = $data->latest()->get();

                if (count($data) > 0) {

                    $this->common->add_url_to_array($type, $data);
                    $this->common->rent_price_list($data);

                    for ($i = 0; $i < count($data); $i++) {

                        if ($data[$i]['video_type'] == 1 || $data[$i]['video_type'] == 2) {
                            $data[$i]['sub_video_type'] = 0;
                        } elseif ($data[$i]['video_type'] == 5 || $data[$i]['video_type'] == 6 || $data[$i]['video_type'] == 7) {
                            $data[$i]['sub_video_type'] = $type;
                        } else {
                            $data[$i]['sub_video_type'] = 0;
                        }
                    }

                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {

                // Check Parent Control
                $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
                if ($user_parent_control_status == 1) {
                    $video_data = Video::where('is_rent', 1)->where('video_type', 7)->where('status', 1)->orderBy('id', 'desc')->get();
                    $tvshow_data = TVShow::where('is_rent', 1)->where('video_type', 7)->where('status', 1)->orderBy('id', 'desc')->get();
                } else {
                    $video_data = Video::where('is_rent', 1)->whereIn('video_type', [1, 6, 7])->where('status', 1)->orderBy('id', 'desc')->get();
                    $tvshow_data = TVShow::where('is_rent', 1)->whereIn('video_type', [2, 6, 7])->where('status', 1)->orderBy('id', 'desc')->get();
                }

                $this->common->add_url_to_array(1, $video_data);
                $this->common->rent_price_list($video_data);

                for ($i = 0; $i < count($video_data); $i++) {

                    $video_sub_type = 0;
                    if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                        $video_sub_type = 1;
                    }

                    $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                    $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                    $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $user_parent_control_status);
                    $video_data[$i]['sub_video_type'] = $video_sub_type;
                    $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
                }

                $this->common->add_url_to_array(2, $tvshow_data);
                $this->common->rent_price_list($tvshow_data);

                for ($i = 0; $i < count($tvshow_data); $i++) {

                    $show_sub_type = 0;
                    if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                        $show_sub_type = 2;
                    }

                    $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                    $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                    $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $user_parent_control_status);
                    $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                    $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
                }

                $video_data = $video_data->toArray();
                $tvshow_data = $tvshow_data->toArray();

                $fin_array = array_merge($video_data, $tvshow_data);

                usort($fin_array, function ($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });

                $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);
                $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $response['data'] = $paginator->items();

                if (count($response['data']) > 0) {
                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), $response['data'], $response['pagination']);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_banner(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'is_home_screen' => 'required|numeric',
                'type_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $is_home_screen = $request['is_home_screen'];
            $type_id = $request['type_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? "";

            // Check Parent Control
            $parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($is_home_screen == 1) {

                if ($parent_control_status == 1) {
                    $banner_data = Banner::where('is_home_screen', 1)->where('video_type', 7)->orderBy('sort_order', 'asc')->latest()->get();
                } else {
                    $banner_data = Banner::where('is_home_screen', 1)->orderBy('sort_order', 'asc')->latest()->get();
                }
            } elseif ($is_home_screen == 2) {

                if ($parent_control_status == 1) {
                    $banner_data = Banner::where('is_home_screen', 2)->where('video_type', 7)->where('type_id', $type_id)->orderBy('sort_order', 'asc')->latest()->get();
                } else {
                    $banner_data = Banner::where('is_home_screen', 2)->where('type_id', $type_id)->orderBy('sort_order', 'asc')->latest()->get();
                }
            } else {
                return $this->common->API_Response(400, __('api_msf.data_not_found'));
            }

            if (count($banner_data) > 0) {

                $final_data = [];
                for ($i = 0; $i < count($banner_data); $i++) {

                    if ($banner_data[$i]['video_type'] == 1) {

                        $data = Video::where('video_type', 1)->where('id', $banner_data[$i]['video_id'])->where('status', 1)->first();
                        if ($data != null && isset($data)) {

                            $this->common->add_url_to_array(1, array($data));
                            $this->common->rent_price_list(array($data));

                            $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 0, $data['id']);
                            $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 0, $data['id']);
                            $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $parent_control_status);
                            $data['sub_video_type'] = 0;
                            $data['total_language'] = $this->common->get_total_language($data['language_id']);
                            $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);

                            $final_data[] = $data;
                        }
                    } else if ($banner_data[$i]['video_type'] == 2) {

                        $data = TVShow::where('video_type', 2)->where('id', $banner_data[$i]['video_id'])->where('status', 1)->first();
                        if ($data != null && isset($data)) {

                            $this->common->add_url_to_array(2, array($data));
                            $this->common->rent_price_list(array($data));

                            $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 0, $data['id']);
                            $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 0, $data['id']);
                            $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $parent_control_status);
                            $data['sub_video_type'] = 0;
                            $data['total_language'] = $this->common->get_total_language($data['language_id']);
                            $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);

                            $final_data[] = $data;
                        }
                    } else if ($banner_data[$i]['video_type'] == 5 || $banner_data[$i]['video_type'] == 6 || $banner_data[$i]['video_type'] == 7) {

                        if ($banner_data[$i]['subvideo_type'] == 1) {

                            $data = Video::where('video_type', $banner_data[$i]['video_type'])->where('id', $banner_data[$i]['video_id'])->where('status', 1)->first();
                            if ($data != null && isset($data)) {

                                $this->common->add_url_to_array(1, array($data));
                                $this->common->rent_price_list(array($data));

                                $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 1, $data['id']);
                                $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 1, $data['id']);
                                $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 1, $data['id'], $parent_control_status);
                                $data['sub_video_type'] = 1;
                                $data['total_language'] = $this->common->get_total_language($data['language_id']);
                                $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);

                                $final_data[] = $data;
                            }
                        } else if ($banner_data[$i]['subvideo_type'] == 2) {

                            $data = TVShow::where('video_type', $banner_data[$i]['video_type'])->where('id', $banner_data[$i]['video_id'])->where('status', 1)->first();
                            if ($data != null && isset($data)) {

                                $this->common->add_url_to_array(2, array($data));
                                $this->common->rent_price_list(array($data));

                                $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 2, $data['id']);
                                $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 2, $data['id']);
                                $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 2, $data['id'], $parent_control_status);
                                $data['sub_video_type'] = 2;
                                $data['total_language'] = $this->common->get_total_language($data['language_id']);
                                $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);

                                $final_data[] = $data;
                            }
                        }
                    } else if ($banner_data[$i]['video_type'] == 8) {

                        $data = Shorts::where('video_type', 8)->where('id', $banner_data[$i]['video_id'])->where('status', 1)->first();
                        if ($data != null && isset($data)) {

                            $this->common->add_url_to_array(4, array($data));

                            $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $parent_control_status);
                            $data['total_language'] = $this->common->get_total_language($data['language_id']);
                            $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);

                            $final_data[] = $data;
                        }
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $final_data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function section_list(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'is_home_screen' => 'required|numeric',
                'type_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $is_home_screen = $request['is_home_screen'];
            $type_id = $request['type_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? "";
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($is_home_screen == 1) {

                if ($user_parent_control_status == 1) {
                    $data = Home_Section::where('is_home_screen', $is_home_screen)->whereIn('video_type', [7, 101])->where('status', 1)->orderBy('sort_order', 'asc')->latest();
                } else {
                    $data = Home_Section::where('is_home_screen', $is_home_screen)->where('status', 1)->orderBy('sort_order', 'asc')->latest();
                }
            } else if ($is_home_screen == 2) {

                if ($user_parent_control_status == 1) {
                    $data = Home_Section::where('is_home_screen', $is_home_screen)->whereIn('video_type', [7, 101])->where('type_id', $type_id)->where('status', 1)->orderBy('sort_order', 'asc')->latest();
                } else {
                    $data = Home_Section::where('is_home_screen', $is_home_screen)->where('type_id', $type_id)->where('status', 1)->orderBy('sort_order', 'asc')->latest();
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['data'] = [];
                    if (in_array($data[$i]['video_type'], [1, 2, 5, 6, 7])) {

                        $query = $this->common->home_section_query($user_id, $user_parent_control_status, $data[$i]['section_type'], $data[$i]['video_type'], $data[$i]['sub_video_type'], $data[$i]['type_id'], $data[$i]['content_ids'], $data[$i]['category_id'], $data[$i]['language_id'], $data[$i]['channel_id'], $data[$i]['order_by_upload'], $data[$i]['order_by_view'], $data[$i]['premium_video'], $data[$i]['no_of_content']);
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['video_type'] == 3) {

                        if ($data[$i]['section_type'] == 1) {
                            $content_ids = explode(',', $data[$i]['content_ids']);
                            $query = Category::whereIn('id', $content_ids)->get();
                        } else {
                            $query = Category::orderBy('sort_order', 'asc')->take($data[$i]['no_of_content'])->latest()->get();
                        }
                        $this->common->imageNameToUrl($query, 'image', $this->folder_category, 'normal');
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['video_type'] == 4) {

                        if ($data[$i]['section_type'] == 1) {
                            $content_ids = explode(',', $data[$i]['content_ids']);
                            $query = Language::whereIn('id', $content_ids)->get();
                        } else {
                            $query = Language::orderBy('sort_order', 'asc')->take($data[$i]['no_of_content'])->latest()->get();
                        }
                        $this->common->imageNameToUrl($query, 'image', $this->folder_language, 'normal');
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['video_type'] == 8) {

                        if ($data[$i]['section_type'] == 1) {

                            $contents_ids = explode(',', $data[$i]['content_ids']);
                            $content = Shorts::whereIn('id', $contents_ids)->where('status', 1);
                        } else {

                            $content = Shorts::where('status', 1);
                            if ($data[$i]['type_id'] != 0) {
                                $content->where('type_id', $data[$i]['type_id']);
                            }
                            if ($data[$i]['category_id'] != 0) {
                                $content->whereRaw("FIND_IN_SET(?, category_id)", [$data[$i]['category_id']]);
                            }
                            if ($data[$i]['language_id'] != 0) {
                                $content->whereRaw("FIND_IN_SET(?, language_id)", [$data[$i]['language_id']]);
                            }
                            if ($data[$i]['order_by_upload'] == 2) {
                                $content->orderBy('id', 'desc');
                            }
                            if ($data[$i]['order_by_view'] == 2) {
                                $content->orderBy('total_view', 'desc');
                            }
                            $content->take($data[$i]['no_of_content']);
                        }
                        $query = $content->get();

                        $this->common->add_url_to_array(4, $query);
                        for ($j = 0; $j < count($query); $j++) {

                            $query[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $query[$j]['is_bookmark'] = $this->common->is_bookmark($user_id, $query[$j]['video_type'], 0, $query[$j]['id'], $user_parent_control_status);
                            $query[$j]['category_name'] = $this->common->get_category_name_by_ids($query[$j]['category_id']);
                        }
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['video_type'] == 101) {

                        $watched_data = Video_Watch::where('user_id', $user_id)->where('is_kids_profile', $user_parent_control_status)->where('status', 1)->latest()->orderBy('id', 'desc')->take(10)->get();
                        if (count($watched_data) > 0) {

                            $final_array = [];
                            for ($j = 0; $j < count($watched_data); $j++) {

                                if ($watched_data[$j]['video_type'] == 1) {

                                    $content_data = Video::where('id', $watched_data[$j]['video_id'])->where('status', 1)->where('video_type', 1)->first();
                                    if ($content_data != null && isset($content_data)) {

                                        $this->common->add_url_to_array(1, array($content_data));
                                        $this->common->rent_price_list(array($content_data));
                                        $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                        $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                                        $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                                        $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                                        $content_data['sub_video_type'] = 0;
                                        $content_data['stop_time'] = $watched_data[$j]['stop_time'];
                                        $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);

                                        $final_array[] = $content_data;
                                    }
                                } else if ($watched_data[$j]['video_type'] == 2) {

                                    $content_data = TVShow::where('id', $watched_data[$j]['video_id'])->where('status', 1)->where('video_type', 2)->first();
                                    if ($content_data != null && isset($content_data)) {

                                        $this->common->add_url_to_array(2, array($content_data));
                                        $this->common->rent_price_list(array($content_data));
                                        $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                        $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                                        $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                                        $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                                        $content_data['sub_video_type'] = 0;
                                        $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);

                                        $episode = [];
                                        $episode_data = TVShow_Video::where('id', $watched_data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                                        if ($episode_data != null && isset($episode_data)) {

                                            $this->common->add_url_to_array(3, array($episode_data));
                                            $episode = $episode_data->toArray();
                                        }
                                        $content_data['episode'] = $episode;

                                        $content_data['stop_time'] = $watched_data[$j]['stop_time'];
                                        $final_array[] = $content_data;
                                    }
                                } else if ($watched_data[$j]['video_type'] == 6 || $watched_data[$j]['video_type'] == 7) {

                                    if ($watched_data[$j]['sub_video_type'] == 1) {

                                        $content_data = Video::where('id', $watched_data[$j]['video_id'])->where('status', 1)->where('video_type', $watched_data[$j]['video_type'])->first();
                                        if ($content_data != null && isset($content_data)) {

                                            $this->common->add_url_to_array(1, array($content_data));
                                            $this->common->rent_price_list(array($content_data));
                                            $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                            $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 1, $content_data['id']);
                                            $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 1, $content_data['id']);
                                            $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 1, $content_data['id'], $user_parent_control_status);
                                            $content_data['sub_video_type'] = 1;
                                            $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);
                                            $content_data['stop_time'] = $watched_data[$j]['stop_time'];

                                            $final_array[] = $content_data;
                                        }
                                    } else if ($watched_data[$j]['sub_video_type'] == 2) {

                                        $content_data = TVShow::where('id', $watched_data[$j]['video_id'])->where('status', 1)->where('video_type', $watched_data[$j]['video_type'])->first();
                                        if ($content_data != null && isset($content_data)) {

                                            $this->common->add_url_to_array(2, array($content_data));
                                            $this->common->rent_price_list(array($content_data));
                                            $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                            $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 2, $content_data['id']);
                                            $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 2, $content_data['id']);
                                            $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 2, $content_data['id'], $user_parent_control_status);
                                            $content_data['sub_video_type'] = 2;
                                            $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);

                                            $episode = [];
                                            $episode_data = TVShow_Video::where('id', $watched_data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                                            if ($episode_data != null && isset($episode_data)) {

                                                $this->common->add_url_to_array(3, array($episode_data));
                                                $episode = $episode_data->toArray();
                                            }
                                            $content_data['episode'] = $episode;

                                            $content_data['stop_time'] = $watched_data[$j]['stop_time'];
                                            $final_array[] = $content_data;
                                        }
                                    }
                                }
                            }
                            $data[$i]['data'] = $final_array;
                        }
                    } else if ($data[$i]['video_type'] == 102) {

                        if ($data[$i]['section_type'] == 1) {
                            $content_ids = explode(',', $data[$i]['content_ids']);
                            $query = Channel::whereIn('id', $content_ids)->get();
                        } else {
                            $query = Channel::orderBy('id', 'desc')->take($data[$i]['no_of_content'])->get();
                        }
                        $this->common->imageNameToUrl($query, 'portrait_img', $this->folder_channel, 'portrait');
                        $this->common->imageNameToUrl($query, 'landscape_img', $this->folder_channel, 'landscape');
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['video_type'] == 103) {

                        $type = Type::where('id', $data[$i]['type_id'])->first();
                        if ($data[$i]['section_type'] == 1) {

                            $contents_ids = explode(',', $data[$i]['content_ids']);
                            if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $data[$i]['sub_video_type'] == 1)) {

                                $content = Video::whereIn('id', $contents_ids)->where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                            } else if ($type['type'] == 2 || (in_array($type['type'], [6, 7]) && $data[$i]['sub_video_type'] == 2)) {

                                $content = TVShow::whereIn('id', $contents_ids)->where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                            }
                        } else {

                            if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $data[$i]['sub_video_type'] == 1)) {

                                $content = Video::where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                            } else if ($type['type'] == 2 || (in_array($type['type'], [6, 7]) && $data[$i]['sub_video_type'] == 2)) {

                                $content = TVShow::where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                            }

                            if ($data[$i]['type_id'] != 0) {
                                $content->where('type_id', $data[$i]['type_id']);
                            }
                            if ($data[$i]['category_id'] != 0) {
                                $category_id = $data[$i]['category_id'];
                                $content->whereRaw("FIND_IN_SET('$category_id', category_id)");
                            }
                            if ($data[$i]['language_id'] != 0) {
                                $language_id = $data[$i]['language_id'];
                                $content->whereRaw("FIND_IN_SET('$language_id', language_id)");
                            }
                            if ($data[$i]['channel_id'] != 0) {
                                $content->where('channel_id', $data[$i]['channel_id']);
                            }
                            if ($data[$i]['order_by_upload'] == 2) {
                                $content->orderBy('id', 'desc');
                            }
                            if ($data[$i]['order_by_view'] == 2) {
                                $content->orderBy('total_view', 'desc');
                            }
                            if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $data[$i]['sub_video_type'] == 1)) {

                                if ($data[$i]['premium_video'] == 1) {
                                    $content->where('is_premium', 1);
                                } else if ($data[$i]['premium_video'] == 0) {
                                    $content->where('is_premium', 0);
                                }
                            }
                            $content->take($data[$i]['no_of_content']);
                        }
                        $query = $content->get();

                        if ($type['type'] == 1) {

                            $this->common->add_url_to_array(1, $query);
                            $this->common->rent_price_list($query);
                            foreach ($query as $value) {

                                $value['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $value['rent_buy'] = $this->common->is_rent_buy($user_id, $value['video_type'], 0, $value['id']);
                                $value['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $value['video_type'], 0, $value['id']);
                                $value['is_bookmark'] = $this->common->is_bookmark($user_id, $value['video_type'], 0, $value['id'], $user_parent_control_status);
                                $value['sub_video_type'] = 0;
                                $value['stop_time'] = $this->common->get_stop_time($user_id, $value['video_type'], 0, $value['id'], 0);
                                $value['category_name'] = $this->common->get_category_name_by_ids($value['category_id']);
                            }
                        } else if ($type['type'] == 2) {

                            $this->common->add_url_to_array(2, $query);
                            $this->common->rent_price_list($query);
                            foreach ($query as $key) {

                                $key['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $key['rent_buy'] = $this->common->is_rent_buy($user_id, $key['video_type'], 0, $key['id']);
                                $key['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $key['video_type'], 0, $key['id']);
                                $key['is_bookmark'] = $this->common->is_bookmark($user_id, $key['video_type'], 0, $key['id'], $user_parent_control_status);
                                $key['sub_video_type'] = 0;
                                $key['stop_time'] = $this->common->get_stop_time($user_id, $key['video_type'], 0, $key['id'], 0);
                                $key['category_name'] = $this->common->get_category_name_by_ids($key['category_id']);
                            }
                        } else if ($type['type'] == 6 || $type['type'] == 7) {

                            if ($data[$i]['sub_video_type'] == 1) {

                                $this->common->add_url_to_array(1, $query);
                                $this->common->rent_price_list($query);
                                foreach ($query as $val) {

                                    $val['is_buy'] = $this->common->is_any_package_buy($user_id);
                                    $val['rent_buy'] = $this->common->is_rent_buy($user_id, $val['video_type'], 1, $val['id']);
                                    $val['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $val['video_type'], 1, $val['id']);
                                    $val['is_bookmark'] = $this->common->is_bookmark($user_id, $val['video_type'], 1, $val['id'], $user_parent_control_status);
                                    $val['sub_video_type'] = 1;
                                    $val['stop_time'] = $this->common->get_stop_time($user_id, $val['video_type'], 1, $val['id'], 0);
                                    $val['category_name'] = $this->common->get_category_name_by_ids($val['category_id']);
                                }
                            } else if ($data[$i]['sub_video_type'] == 2) {

                                $this->common->add_url_to_array(2, $query);
                                $this->common->rent_price_list($query);
                                foreach ($query as $ra) {

                                    $ra['is_buy'] = $this->common->is_any_package_buy($user_id);
                                    $ra['rent_buy'] = $this->common->is_rent_buy($user_id, $ra['video_type'], 2, $ra['id']);
                                    $ra['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $ra['video_type'], 2, $ra['id']);
                                    $ra['is_bookmark'] = $this->common->is_bookmark($user_id, $ra['video_type'], 2, $ra['id'], $user_parent_control_status);
                                    $ra['sub_video_type'] = 2;
                                    $ra['stop_time'] = $this->common->get_stop_time($user_id, $ra['video_type'], 2, $ra['id'], 0);
                                    $ra['category_name'] = $this->common->get_category_name_by_ids($ra['category_id']);
                                }
                            }
                        }
                        $data[$i]['data'] = $query;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function section_detail(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'section_id' => 'required|numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $section_id = $request['section_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? 0;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            $section = Home_Section::where('id', $section_id)->first();
            if ($section != null && isset($section)) {

                $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);

                if (in_array($section['video_type'], [1, 2, 5, 6, 7])) {

                    $data = $this->common->home_section_query_detail($section['video_type'], $section['section_type'], $section['sub_video_type'], $section['type_id'], $section['content_ids'], $section['category_id'], $section['language_id'], $section['channel_id'], $section['order_by_upload'], $section['order_by_view'], $section['premium_video']);
                } else if ($section['video_type'] == 3) {

                    if ($section['section_type'] == 1) {
                        $content_ids = explode(',', $section['content_ids']);
                        $data = Category::whereIn('id', $content_ids);
                    } else {
                        $data = Category::orderBy('sort_order', 'asc')->latest();
                    }
                } else if ($section['video_type'] == 4) {

                    if ($section['section_type'] == 1) {
                        $content_ids = explode(',', $section['content_ids']);
                        $data = Language::whereIn('id', $content_ids);
                    } else {
                        $data = Language::orderBy('sort_order', 'asc')->latest();
                    }
                } else if ($section['video_type'] == 8) {

                    if ($section['section_type'] == 1) {
                        $content_ids = explode(',', $section['content_ids']);
                        $data = Shorts::whereIn('id', $content_ids)->where('status', 1);
                    } else {

                        $data = Shorts::where('status', 1);
                        if ($section['type_id'] != 0) {
                            $data->where('type_id', $section['type_id']);
                        }
                        if ($section['category_id'] != 0) {
                            $data->whereRaw("FIND_IN_SET(?, category_id)", [$section['category_id']]);
                        }
                        if ($section['language_id'] != 0) {
                            $data->whereRaw("FIND_IN_SET(?, language_id)", [$section['language_id']]);
                        }
                        if ($section['order_by_upload'] == 2) {
                            $data->orderBy('id', 'desc');
                        }
                        if ($section['order_by_view'] == 2) {
                            $data->orderBy('total_view', 'desc');
                        }
                    }
                } else if ($section['video_type'] == 102) {

                    if ($section['section_type'] == 1) {
                        $content_ids = explode(',', $section['content_ids']);
                        $data = Channel::whereIn('id', $content_ids);
                    } else {
                        $data = Channel::orderBy('id', 'desc')->latest();
                    }
                } else if ($section['video_type'] == 101) {

                    $data = Video_Watch::where('user_id', $user_id)->where('is_kids_profile', $user_parent_control_status)->where('status', 1)->latest()->orderBy('id', 'desc');
                } else if ($section['video_type'] == 103) {

                    $type = Type::where('id', $section['type_id'])->first();
                    if ($section['section_type'] == 1) {

                        $contents_ids = explode(',', $section['content_ids']);
                        if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $section['sub_video_type'] == 1)) {

                            $data = Video::whereIn('id', $contents_ids)->where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                        } else if ($type['type'] == 2 || (in_array($type['type'], [6, 7]) && $section['sub_video_type'] == 2)) {

                            $data = TVShow::whereIn('id', $contents_ids)->where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                        }
                    } else {

                        if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $section['sub_video_type'] == 1)) {

                            $data = Video::where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                        } else if ($type['type'] == 2 || (in_array($type['type'], [6, 7]) && $section['sub_video_type'] == 2)) {

                            $data = TVShow::where('video_type', $type['type'])->where('status', 1)->where('is_rent', 1);
                        }

                        if ($section['type_id'] != 0) {
                            $data->where('type_id', $section['type_id']);
                        }
                        if ($section['category_id'] != 0) {
                            $category_id = $section['category_id'];
                            $data->whereRaw("FIND_IN_SET('$category_id', category_id)");
                        }
                        if ($section['language_id'] != 0) {
                            $language_id = $section['language_id'];
                            $data->whereRaw("FIND_IN_SET('$language_id', language_id)");
                        }
                        if ($section['channel_id'] != 0) {
                            $data->where('channel_id', $section['channel_id']);
                        }
                        if ($section['order_by_upload'] == 2) {
                            $data->orderBy('id', 'desc');
                        }
                        if ($section['order_by_view'] == 2) {
                            $data->orderBy('total_view', 'desc');
                        }
                        if ($type['type'] == 1 || (in_array($type['type'], [6, 7]) && $section['sub_video_type'] == 1)) {

                            if ($section['premium_video'] == 1) {
                                $data->where('is_premium', 1);
                            } else if ($section['premium_video'] == 0) {
                                $data->where('is_premium', 0);
                            }
                        }
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->skip($offset);
            $data = $data->get();

            if (count($data) > 0) {

                if ($section['video_type'] == 1) {

                    $this->common->add_url_to_array(1, $data);
                    $this->common->rent_price_list($data);
                    for ($i = 0; $i < count($data); $i++) {

                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], $user_parent_control_status);
                        $data[$i]['sub_video_type'] = 0;
                        $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], 0);
                    }
                } elseif ($section['video_type'] == 2) {

                    $this->common->add_url_to_array(2, $data);
                    $this->common->rent_price_list($data);
                    for ($i = 0; $i < count($data); $i++) {

                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], $user_parent_control_status);
                        $data[$i]['sub_video_type'] = 0;
                        $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], 0);
                    }
                } elseif ($section['video_type'] == 3) {

                    $this->common->imageNameToUrl($data, 'image', $this->folder_category, 'normal');
                } elseif ($section['video_type'] == 4) {
                    $this->common->imageNameToUrl($data, 'image', $this->folder_language, 'normal');
                } elseif (in_array($section['video_type'], [5, 6, 7])) {

                    if ($section['sub_video_type'] == 1) {

                        $this->common->add_url_to_array(1, $data);
                        $this->common->rent_price_list($data);
                        for ($i = 0; $i < count($data); $i++) {

                            $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 1, $data[$i]['id']);
                            $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 1, $data[$i]['id']);
                            $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 1, $data[$i]['id'], $user_parent_control_status);
                            $data[$i]['sub_video_type'] = 1;
                            $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 1, $data[$i]['id'], 0);
                        }
                    } else if ($section['sub_video_type'] == 2) {

                        $this->common->add_url_to_array(2, $data);
                        $this->common->rent_price_list($data);
                        for ($i = 0; $i < count($data); $i++) {

                            $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 2, $data[$i]['id']);
                            $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 2, $data[$i]['id']);
                            $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 2, $data[$i]['id'], $user_parent_control_status);
                            $data[$i]['sub_video_type'] = 2;
                            $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 2, $data[$i]['id'], 0);
                        }
                    }
                } elseif ($section['video_type'] == 8) {

                    $this->common->add_url_to_array(4, $data);
                    for ($i = 0; $i < count($data); $i++) {

                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], $user_parent_control_status);
                    }
                } elseif ($section['video_type'] == 101) {

                    $final_array = [];
                    for ($j = 0; $j < count($data); $j++) {

                        if ($data[$j]['video_type'] == 1) {

                            $content_data = Video::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', 1)->first();
                            if ($content_data != null && isset($content_data)) {

                                $this->common->add_url_to_array(1, array($content_data));
                                $this->common->rent_price_list(array($content_data));
                                $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                                $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                                $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                                $content_data['sub_video_type'] = 0;
                                $content_data['stop_time'] = $data[$j]['stop_time'];
                                $final_array[] = $content_data;
                            }
                        } else if ($data[$j]['video_type'] == 2) {

                            $content_data = TVShow::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', 2)->first();
                            if ($content_data != null && isset($content_data)) {

                                $this->common->add_url_to_array(2, array($content_data));
                                $this->common->rent_price_list(array($content_data));
                                $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                                $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                                $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                                $content_data['sub_video_type'] = 0;

                                $episode = [];
                                $episode_data = TVShow_Video::where('id', $data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                                if ($episode_data != null && isset($episode_data)) {

                                    $this->common->add_url_to_array(3, array($episode_data));
                                    $episode = $episode_data->toArray();
                                }
                                $content_data['episode'] = $episode;

                                $content_data['stop_time'] = $data[$j]['stop_time'];
                                $final_array[] = $content_data;
                            }
                        } else if (in_array($data[$j]['video_type'], [6, 7])) {

                            if ($data[$j]['sub_video_type'] == 1) {

                                $content_data = Video::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', $data[$j]['video_type'])->first();
                                if ($content_data != null && isset($content_data)) {

                                    $this->common->add_url_to_array(1, array($content_data));
                                    $this->common->rent_price_list(array($content_data));
                                    $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                    $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 1, $content_data['id']);
                                    $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 1, $content_data['id']);
                                    $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 1, $content_data['id'], $user_parent_control_status);
                                    $content_data['sub_video_type'] = 1;
                                    $content_data['stop_time'] = $data[$j]['stop_time'];
                                    $final_array[] = $content_data;
                                }
                            } else if ($data[$j]['sub_video_type'] == 2) {

                                $content_data = TVShow::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', $data[$j]['video_type'])->first();
                                if ($content_data != null && isset($content_data)) {

                                    $this->common->add_url_to_array(2, array($content_data));
                                    $this->common->rent_price_list(array($content_data));
                                    $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                    $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 2, $content_data['id']);
                                    $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 2, $content_data['id']);
                                    $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 2, $content_data['id'], $user_parent_control_status);
                                    $content_data['sub_video_type'] = 2;

                                    $episode = [];
                                    $episode_data = TVShow_Video::where('id', $data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                                    if ($episode_data != null && isset($episode_data)) {

                                        $this->common->add_url_to_array(3, array($episode_data));
                                        $episode = $episode_data->toArray();
                                    }
                                    $content_data['episode'] = $episode;

                                    $content_data['stop_time'] = $data[$j]['stop_time'];
                                    $final_array[] = $content_data;
                                }
                            }
                        }
                    }
                    $data = $final_array;
                } elseif ($section['video_type'] == 102) {

                    $this->common->imageNameToUrl($data, 'portrait_img', $this->folder_channel, 'portrait');
                    $this->common->imageNameToUrl($data, 'landscape_img', $this->folder_channel, 'landscape');
                } elseif ($section['video_type'] == 103) {

                    $type = Type::where('id', $section['type_id'])->first();
                    if ($type['type'] == 1) {

                        $this->common->add_url_to_array(1, $data);
                        $this->common->rent_price_list($data);
                        foreach ($data as $value) {

                            $value['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $value['rent_buy'] = $this->common->is_rent_buy($user_id, $value['video_type'], 0, $value['id']);
                            $value['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $value['video_type'], 0, $value['id']);
                            $value['is_bookmark'] = $this->common->is_bookmark($user_id, $value['video_type'], 0, $value['id'], $user_parent_control_status);
                            $value['sub_video_type'] = 0;
                            $value['stop_time'] = $this->common->get_stop_time($user_id, $value['video_type'], 0, $value['id'], 0);
                            $value['category_name'] = $this->common->get_category_name_by_ids($value['category_id']);
                        }
                    } else if ($type['type'] == 2) {

                        $this->common->add_url_to_array(2, $data);
                        $this->common->rent_price_list($data);
                        foreach ($data as $key) {

                            $key['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $key['rent_buy'] = $this->common->is_rent_buy($user_id, $key['video_type'], 0, $key['id']);
                            $key['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $key['video_type'], 0, $key['id']);
                            $key['is_bookmark'] = $this->common->is_bookmark($user_id, $key['video_type'], 0, $key['id'], $user_parent_control_status);
                            $key['sub_video_type'] = 0;
                            $key['stop_time'] = $this->common->get_stop_time($user_id, $key['video_type'], 0, $key['id'], 0);
                            $key['category_name'] = $this->common->get_category_name_by_ids($key['category_id']);
                        }
                    } else if (in_array($type['type'], [6, 7])) {

                        if ($section['sub_video_type'] == 1) {

                            $this->common->add_url_to_array(1, $data);
                            $this->common->rent_price_list($data);
                            foreach ($data as $val) {

                                $val['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $val['rent_buy'] = $this->common->is_rent_buy($user_id, $val['video_type'], 1, $val['id']);
                                $val['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $val['video_type'], 1, $val['id']);
                                $val['is_bookmark'] = $this->common->is_bookmark($user_id, $val['video_type'], 1, $val['id'], $user_parent_control_status);
                                $val['sub_video_type'] = 1;
                                $val['stop_time'] = $this->common->get_stop_time($user_id, $val['video_type'], 1, $val['id'], 0);
                                $val['category_name'] = $this->common->get_category_name_by_ids($val['category_id']);
                            }
                        } else if ($section['sub_video_type'] == 2) {

                            $this->common->add_url_to_array(2, $data);
                            $this->common->rent_price_list($data);
                            foreach ($data as $ra) {

                                $ra['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $ra['rent_buy'] = $this->common->is_rent_buy($user_id, $ra['video_type'], 2, $ra['id']);
                                $ra['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $ra['video_type'], 2, $ra['id']);
                                $ra['is_bookmark'] = $this->common->is_bookmark($user_id, $ra['video_type'], 2, $ra['id'], $user_parent_control_status);
                                $ra['sub_video_type'] = 2;
                                $ra['stop_time'] = $this->common->get_stop_time($user_id, $ra['video_type'], 2, $ra['id'], 0);
                                $ra['category_name'] = $this->common->get_category_name_by_ids($ra['category_id']);
                            }
                        }
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_detail(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'type_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'video_id' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $type_id = $request['type_id'];
            $video_type = $request['video_type'];
            $video_id = $request['video_id'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $user_id = $request['user_id'] ?? 0;
            $is_kids_profile = $request['is_kids_profile'] ?? 0;

            if ($video_type == 1) {

                $data = Video::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
                if ($data != null && isset($data)) {

                    $this->common->add_url_to_array(1, array($data));
                    $this->common->rent_price_list(array($data));

                    $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 0, $data['id']);
                    $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 0, $data['id']);
                    $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $is_kids_profile);
                    $data['sub_video_type'] = 0;
                    $data['stop_time'] = $this->common->get_stop_time($user_id, $data['video_type'], 0, $data['id'], 0);
                    $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);
                    $data['language_name'] = $this->common->get_language_name_by_ids($data['language_id']);
                    $data['is_user_like'] = $this->common->is_like($user_id, $data['video_type'], 0, $data['id']);
                    $data['total_comment'] = $this->common->total_comment($data['video_type'], 0, $data['id']);
     
                    // Cast
                    $data['cast'] = array();
                    $cast_Ids = explode(',', $data['cast_id']);
                    $data['cast'] = Cast::whereIn('id', $cast_Ids)->get();
                    $this->common->imageNameToUrl($data['cast'], 'image', $this->folder_cast, 'profile');

                    // Season
                    $data['season'] = array();

                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } elseif ($video_type == 2) {

                $data = TVShow::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
                if ($data != null && isset($data)) {

                    $this->common->add_url_to_array(2, array($data));
                    $this->common->rent_price_list(array($data));

                    $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 0, $data['id']);
                    $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 0, $data['id']);
                    $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $is_kids_profile);
                    $data['sub_video_type'] = 0;
                    $data['stop_time'] = $this->common->get_stop_time($user_id, $data['video_type'], 0, $data['id'], 0);
                    $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);
                    $data['language_name'] = $this->common->get_language_name_by_ids($data['language_id']);
                    $data['is_user_like'] = $this->common->is_like($user_id, $data['video_type'], 0, $data['id']);
                    $data['total_comment'] = $this->common->total_comment($data['video_type'], 0, $data['id']);

                    // Cast
                    $data['cast'] = array();
                    $cast_Ids = explode(',', $data['cast_id']);
                    $data['cast'] = Cast::whereIn('id', $cast_Ids)->get();
                    $this->common->imageNameToUrl($data['cast'], 'image', $this->folder_cast, 'profile');

                    // Season
                    $data['season'] = array();
                    $season_id = TVShow_Video::select('season_id')->where('show_id', $data['id'])->where('status', 1)->groupBy('season_id')->get();
                    if (count($season_id) > 0) {

                        $season_Ids = [];
                        for ($i = 0; $i < count($season_id); $i++) {
                            $season_Ids[] = $season_id[$i]['season_id'];
                        }
                        $data['season'] = Season::whereIn('id', $season_Ids)->get();
                    }

                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } elseif (in_array($video_type, [5, 6, 7])) {

                if ($sub_video_type == 1) {

                    $data = Video::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
                    if ($data != null && isset($data)) {

                        $this->common->add_url_to_array(1, array($data));
                        $this->common->rent_price_list(array($data));

                        $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 1, $data['id']);
                        $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 1, $data['id']);
                        $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 1, $data['id'], $is_kids_profile);
                        $data['sub_video_type'] = 1;
                        $data['stop_time'] = $this->common->get_stop_time($user_id, $data['video_type'], 1, $data['id'], 0);
                        $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);
                        $data['language_name'] = $this->common->get_language_name_by_ids($data['language_id']);
                        $data['is_user_like'] = $this->common->is_like($user_id, $data['video_type'], 1, $data['id']);
                        $data['total_comment'] = $this->common->total_comment($data['video_type'], 1, $data['id']);

                        // Cast
                        $data['cast'] = array();
                        $cast_Ids = explode(',', $data['cast_id']);
                        $data['cast'] = Cast::whereIn('id', $cast_Ids)->get();
                        $this->common->imageNameToUrl($data['cast'], 'image', $this->folder_cast, 'profile');

                        // Season
                        $data['season'] = array();

                        return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else if ($sub_video_type == 2) {

                    $data = TVShow::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
                    if ($data != null && isset($data)) {

                        $this->common->add_url_to_array(2, array($data));
                        $this->common->rent_price_list(array($data));

                        $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data['rent_buy'] = $this->common->is_rent_buy($user_id, $data['video_type'], 2, $data['id']);
                        $data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data['video_type'], 2, $data['id']);
                        $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 2, $data['id'], $is_kids_profile);
                        $data['sub_video_type'] = 2;
                        $data['stop_time'] = $this->common->get_stop_time($user_id, $data['video_type'], 2, $data['id'], 0);
                        $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);
                        $data['language_name'] = $this->common->get_language_name_by_ids($data['language_id']);
                        $data['is_user_like'] = $this->common->is_like($user_id, $data['video_type'], 2, $data['id']);
                        $data['total_comment'] = $this->common->total_comment($data['video_type'], 2, $data['id']);

                        // Cast
                        $data['cast'] = array();
                        $cast_Ids = explode(',', $data['cast_id']);
                        $data['cast'] = Cast::whereIn('id', $cast_Ids)->get();
                        $this->common->imageNameToUrl($data['cast'], 'image', $this->folder_cast, 'profile');

                        // Season
                        $data['season'] = array();
                        $season_id = TVShow_Video::select('season_id')->where('show_id', $data['id'])->where('status', 1)->groupBy('season_id')->get();
                        if (count($season_id) > 0) {

                            $season_Ids = [];
                            for ($i = 0; $i < count($season_id); $i++) {
                                $season_Ids[] = $season_id[$i]['season_id'];
                            }
                            $data['season'] = Season::whereIn('id', $season_Ids)->get();
                        }

                        return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } elseif ($video_type == 8) {

                $data = Shorts::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
                if ($data != null && isset($data)) {

                    $this->common->add_url_to_array(4, array($data));

                    $data['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data['is_bookmark'] = $this->common->is_bookmark($user_id, $data['video_type'], 0, $data['id'], $is_kids_profile);
                    $data['category_name'] = $this->common->get_category_name_by_ids($data['category_id']);
                    $data['language_name'] = $this->common->get_language_name_by_ids($data['language_id']);
                    $data['is_user_like'] = $this->common->is_like($user_id, $data['video_type'], 0, $data['id']);
                    $data['total_comment'] = $this->common->total_comment($data['video_type'], 0, $data['id']);

                    // Cast
                    $data['cast'] = array();
                    $cast_Ids = explode(',', $data['cast_id']);
                    $data['cast'] = Cast::whereIn('id', $cast_Ids)->get();
                    $this->common->imageNameToUrl($data['cast'], 'image', $this->folder_cast, 'profile');

                    // Season
                    $data['season'] = [];
                    $seasonIds = Shorts_Episode::where('show_id', $data['id'])->where('status', 1)->pluck('season_id')->unique()->toArray();
                    if (!empty($seasonIds)) {
                        $data['season'] = Season::whereIn('id', $seasonIds)->get();
                    }

                    return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_releted_content(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'type_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'video_id' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $type_id = $request['type_id'];
            $video_type = $request['video_type'];
            $video_id = $request['video_id'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $user_id = $request['user_id'] ?? 0;
            $is_kids_profile = $request['is_kids_profile'] ?? 0;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            if ($video_type == 1) {

                $content_data = Video::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->latest()->first();
                if ($content_data != null && isset($content_data)) {

                    $C_Ids = explode(',', $content_data['category_id']);

                    $conditions = array_map(function ($value) {
                        return "FIND_IN_SET('$value', category_id)";
                    }, $C_Ids);

                    $data = Video::where(function ($query) use ($conditions) {
                        $query->whereRaw(implode(' OR ', $conditions));
                    })->whereNotIn('id', [$content_data['id']])->where('video_type', $content_data['video_type'])->orderBy('id', 'desc');
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } elseif ($video_type == 2) {

                $content_data = TVShow::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->latest()->first();
                if ($content_data != null && isset($content_data)) {

                    $C_Ids = explode(',', $content_data['category_id']);

                    $conditions = array_map(function ($value) {
                        return "FIND_IN_SET('$value', category_id)";
                    }, $C_Ids);

                    $data = TVShow::where(function ($query) use ($conditions) {
                        $query->whereRaw(implode(' OR ', $conditions));
                    })->whereNotIn('id', [$content_data['id']])->where('video_type', $content_data['video_type']);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } elseif ($video_type == 5 || $video_type == 6 || $video_type == 7) {

                if ($sub_video_type == 1) {

                    $content_data = Video::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->latest()->first();
                    if ($content_data != null && isset($content_data)) {

                        $C_Ids = explode(',', $content_data['category_id']);

                        $conditions = array_map(function ($value) {
                            return "FIND_IN_SET('$value', category_id)";
                        }, $C_Ids);

                        $data = Video::where(function ($query) use ($conditions) {
                            $query->whereRaw(implode(' OR ', $conditions));
                        })->whereNotIn('id', [$content_data['id']])->where('video_type', $content_data['video_type']);
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else if ($sub_video_type == 2) {

                    $content_data = TVShow::where('id', $video_id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->latest()->first();
                    if ($content_data != null && isset($content_data)) {

                        $C_Ids = explode(',', $content_data['category_id']);

                        $conditions = array_map(function ($value) {
                            return "FIND_IN_SET('$value', category_id)";
                        }, $C_Ids);

                        $data = TVShow::where(function ($query) use ($conditions) {
                            $query->whereRaw(implode(' OR ', $conditions));
                        })->whereNotIn('id', [$content_data['id']])->where('video_type', $content_data['video_type']);
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                if ($video_type == 1) {

                    $this->common->add_url_to_array(1, $data);
                    $this->common->rent_price_list($data);
                    for ($i = 0; $i < count($data); $i++) {

                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], $is_kids_profile);
                        $data[$i]['sub_video_type'] = 0;
                        $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], 0);
                    }
                } else if ($video_type == 2) {

                    $this->common->add_url_to_array(2, $data);
                    $this->common->rent_price_list($data);
                    for ($i = 0; $i < count($data); $i++) {

                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                        $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], $is_kids_profile);
                        $data[$i]['sub_video_type'] = 0;
                        $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], 0);
                    }
                } else if (in_array($video_type, [5, 6, 7])) {

                    if ($sub_video_type == 1) {

                        $this->common->add_url_to_array(1, $data);
                        $this->common->rent_price_list($data);
                        for ($i = 0; $i < count($data); $i++) {

                            $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 1, $data[$i]['id']);
                            $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 1, $data[$i]['id']);
                            $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 1, $data[$i]['id'], $is_kids_profile);
                            $data[$i]['sub_video_type'] = 1;
                            $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 1, $data[$i]['id'], 0);
                        }
                    } else if ($sub_video_type == 2) {

                        $this->common->add_url_to_array(2, $data);
                        $this->common->rent_price_list($data);
                        for ($i = 0; $i < count($data); $i++) {

                            $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $data[$i]['video_type'], 2, $data[$i]['id']);
                            $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $data[$i]['video_type'], 2, $data[$i]['id']);
                            $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 2, $data[$i]['id'], $is_kids_profile);
                            $data[$i]['sub_video_type'] = 2;
                            $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $data[$i]['video_type'], 2, $data[$i]['id'], 0);
                        }
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function cast_detail(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'cast_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $data = Cast::where('id', $request['cast_id'])->first();
            if ($data != null && isset($data)) {

                $data['image'] = $this->common->getImage($this->folder_cast, $data['image'], 'profile', $data['storage_type']);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_by_category(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'category_id' => 'required|numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $category_id = $request['category_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? 0;
            $page_no = $request->page_no ?? 1;

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($user_parent_control_status == 1) {

                $video_data = Video::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$category_id', category_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$category_id', category_id)")->latest()->get();
            } else {

                $video_data = Video::where('status', 1)->whereIn('video_type', [1, 6, 7])->whereRaw("FIND_IN_SET('$category_id', category_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->whereIn('video_type', [2, 6, 7])->whereRaw("FIND_IN_SET('$category_id', category_id)")->latest()->get();
            }

            $this->common->add_url_to_array(1, $video_data);
            $this->common->rent_price_list($video_data);
            for ($i = 0; $i < count($video_data); $i++) {

                $video_sub_type = 0;
                if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                    $video_sub_type = 1;
                }

                $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $user_parent_control_status);
                $video_data[$i]['sub_video_type'] = $video_sub_type;
                $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
            }

            $this->common->add_url_to_array(2, $tvshow_data);
            $this->common->rent_price_list($tvshow_data);
            for ($i = 0; $i < count($tvshow_data); $i++) {

                $show_sub_type = 0;
                if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                    $show_sub_type = 2;
                }

                $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $user_parent_control_status);
                $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
            }

            $video_data = $video_data->toArray();
            $tvshow_data = $tvshow_data->toArray();

            $fin_array = array_merge($video_data, $tvshow_data);

            usort($fin_array, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $data = $paginator->items();

            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_by_language(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'language_id' => 'required|numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $language_id = $request['language_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? 0;
            $page_no = $request->page_no ?? 1;

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($user_parent_control_status == 1) {

                $video_data = Video::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$language_id', language_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$language_id', language_id)")->latest()->get();
            } else {

                $video_data = Video::where('status', 1)->whereIn('video_type', [1, 6, 7])->whereRaw("FIND_IN_SET('$language_id', language_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->whereIn('video_type', [2, 6, 7])->whereRaw("FIND_IN_SET('$language_id', language_id)")->latest()->get();
            }

            $this->common->add_url_to_array(1, $video_data);
            $this->common->rent_price_list($video_data);
            for ($i = 0; $i < count($video_data); $i++) {

                $video_sub_type = 0;
                if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                    $video_sub_type = 1;
                }

                $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $user_parent_control_status);
                $video_data[$i]['sub_video_type'] = $video_sub_type;
                $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
            }

            $this->common->add_url_to_array(2, $tvshow_data);
            $this->common->rent_price_list($tvshow_data);
            for ($i = 0; $i < count($tvshow_data); $i++) {

                $show_sub_type = 0;
                if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                    $show_sub_type = 2;
                }

                $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $user_parent_control_status);
                $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
            }

            $video_data = $video_data->toArray();
            $tvshow_data = $tvshow_data->toArray();

            $fin_array = array_merge($video_data, $tvshow_data);

            usort($fin_array, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);

            $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $response['data'] = $paginator->items();

            if (count($response['data']) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $response['data'], $response['pagination']);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_by_cast(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'cast_id' => 'required|numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $cast_id = $request['cast_id'];
            $user_id = $request['user_id'] ?? 0;
            $device_id = $request['device_id'] ?? 0;
            $page_no = $request->page_no ?? 1;

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            if ($user_parent_control_status == 1) {

                $video_data = Video::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$cast_id', cast_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->where('video_type', 7)->whereRaw("FIND_IN_SET('$cast_id', cast_id)")->latest()->get();
            } else {

                $video_data = Video::where('status', 1)->whereIn('video_type', [1, 6, 7])->whereRaw("FIND_IN_SET('$cast_id', cast_id)")->latest()->get();
                $tvshow_data = TVShow::where('status', 1)->whereIn('video_type', [2, 6, 7])->whereRaw("FIND_IN_SET('$cast_id', cast_id)")->latest()->get();
            }

            $this->common->add_url_to_array(1, $video_data);
            $this->common->rent_price_list($video_data);
            for ($i = 0; $i < count($video_data); $i++) {

                $video_sub_type = 0;
                if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                    $video_sub_type = 1;
                }

                $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $user_parent_control_status);
                $video_data[$i]['sub_video_type'] = $video_sub_type;
                $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
            }

            $this->common->add_url_to_array(2, $tvshow_data);
            $this->common->rent_price_list($tvshow_data);
            for ($i = 0; $i < count($tvshow_data); $i++) {

                $show_sub_type = 0;
                if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                    $show_sub_type = 2;
                }

                $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $user_parent_control_status);
                $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
            }

            $video_data = $video_data->toArray();
            $tvshow_data = $tvshow_data->toArray();

            $fin_array = array_merge($video_data, $tvshow_data);

            usort($fin_array, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);

            $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $response['data'] = $paginator->items();

            if (count($response['data']) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $response['data'], $response['pagination']);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function content_by_channel(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'channel_id' => 'required|numeric',
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $channel_id = $request['channel_id'];
            $user_id = $request['user_id'] ?? 0;
            $is_kids_profile = $request['is_kids_profile'] ?? 0;
            $page_no = $request->page_no ?? 1;

            $video_data = Video::where('status', 1)->where('video_type', 6)->where('channel_id', $channel_id)->latest()->get();
            $tvshow_data = TVShow::where('status', 1)->where('video_type', 6)->where('channel_id', $channel_id)->latest()->get();

            $this->common->add_url_to_array(1, $video_data);
            $this->common->rent_price_list($video_data);
            for ($i = 0; $i < count($video_data); $i++) {

                $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], 1, $video_data[$i]['id']);
                $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], 1, $video_data[$i]['id']);
                $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], 1, $video_data[$i]['id'], $is_kids_profile);
                $video_data[$i]['sub_video_type'] = 1;
                $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], 1, $video_data[$i]['id'], 0);
            }

            $this->common->add_url_to_array(2, $tvshow_data);
            $this->common->rent_price_list($tvshow_data);
            for ($i = 0; $i < count($tvshow_data); $i++) {

                $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], 2, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], 2, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], 2, $tvshow_data[$i]['id'], $is_kids_profile);
                $tvshow_data[$i]['sub_video_type'] = 2;
                $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], 2, $tvshow_data[$i]['id'], 0);
            }

            $video_data = $video_data->toArray();
            $tvshow_data = $tvshow_data->toArray();

            $fin_array = array_merge($video_data, $tvshow_data);

            usort($fin_array, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);

            $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $response['data'] = $paginator->items();

            if (count($response['data']) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $response['data'], $response['pagination']);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_continue_watching(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'is_kids_profile' => 'required|numeric',
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
                'episode_id' => 'numeric',
                'stop_time' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $is_kids_profile = $request['is_kids_profile'];
            $video_type = $request['video_type'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $video_id = $request['video_id'];
            $episode_id = $request['episode_id'] ?? 0;
            $stop_time = $request['stop_time'];

            $data = Video_Watch::where('user_id', $user_id)->where('is_kids_profile', $is_kids_profile)->where('video_type', $video_type)->where('video_id', $video_id)->orderBy('id', 'desc')->latest()->first();
            if ($data != null && isset($data)) {

                if ($video_type == 2) {
                    Video_Watch::where('id', $data['id'])->update(['episode_id' => $episode_id, 'stop_time' => $stop_time, 'status' => '1']);
                } else if (($video_type == 6 || $video_type == 7) && $sub_video_type == 2) {
                    Video_Watch::where('id', $data['id'])->update(['episode_id' => $episode_id, 'stop_time' => $stop_time, 'status' => '1']);
                } else {
                    Video_Watch::where('id', $data['id'])->update(['stop_time' => $stop_time, 'status' => '1']);
                }
                return $this->common->API_Response(200, __('api_msg.added_continue_watching'));
            } else {

                $insert = new Video_Watch();
                $insert['user_id'] = $user_id;
                $insert['is_kids_profile'] = $is_kids_profile;
                $insert['video_type'] = $video_type;
                $insert['sub_video_type'] = $sub_video_type;
                $insert['video_id'] = $video_id;
                $insert['episode_id'] = $episode_id;
                $insert['stop_time'] = $stop_time;
                $insert['status'] = 1;
                if ($insert->save()) {
                    return $this->common->API_Response(200, __('api_msg.added_continue_watching'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function remove_continue_watching(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'is_kids_profile' => 'required|numeric',
                'video_type' => 'required|numeric',
                'video_id' => 'required|numeric',
                'sub_video_type' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $is_kids_profile = $request['is_kids_profile'];
            $video_type = $request['video_type'];
            $video_id = $request['video_id'];
            $sub_video_type = $request['sub_video_type'] ?? 0;

            Video_Watch::where('user_id', $user_id)->where('is_kids_profile', $is_kids_profile)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->delete();
            return $this->common->API_Response(200, __('api_msg.remove_continue_watching'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_remove_like(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $video_type = $request['video_type'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $video_id = $request['video_id'];

            $data = Like::where('user_id', $user_id)->where('video_id', $video_id)->where('sub_video_type', $sub_video_type)->where('video_type', $video_type)->first();
            if ($data != null && isset($data)) {

                $data->delete();
                if ($video_type == 1 || (in_array($video_type, [6, 7]) && $sub_video_type == 1)) {

                    Video::where('id', $video_id)->decrement('total_like', 1);
                } elseif ($video_type == 2 || (in_array($video_type, [6, 7]) && $sub_video_type == 2)) {

                    TVShow::where('id', $video_id)->decrement('total_like', 1);
                } elseif ($video_type == 8) {

                    Shorts::where('id', $video_id)->decrement('total_like', 1);
                }
                return $this->common->API_Response(200, __('api_msg.content_unliked'));
            } else {

                $insert = new Like();
                $insert['user_id'] = $user_id;
                $insert['video_type'] = $video_type;
                $insert['sub_video_type'] = $sub_video_type;
                $insert['video_id'] = $video_id;
                $insert['status'] = 1;
                if ($insert->save()) {

                    if ($video_type == 1 || (in_array($video_type, [6, 7]) && $sub_video_type == 1)) {

                        Video::where('id', $video_id)->increment('total_like', 1);
                    } elseif ($video_type == 2 || (in_array($video_type, [6, 7]) && $sub_video_type == 2)) {

                        TVShow::where('id', $video_id)->increment('total_like', 1);
                    } elseif ($video_type == 8) {

                        Shorts::where('id', $video_id)->increment('total_like', 1);
                        if ($sub_video_type == 3) {
                            Shorts_Episode::where('id', $episode_id)->increment('total_like', 1);
                        }
                    }
                    return $this->common->API_Response(200, __('api_msg.content_liked'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_remove_bookmark(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'is_kids_profile' => 'required|numeric',
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $is_kids_profile = $request['is_kids_profile'];
            $video_type = $request['video_type'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $video_id = $request['video_id'];

            $data = Bookmark::where('user_id', $user_id)->where('is_kids_profile', $is_kids_profile)->where('video_id', $video_id)->where('sub_video_type', $sub_video_type)->where('video_type', $video_type)->first();
            if ($data != null && isset($data)) {

                $data->delete();
                return $this->common->API_Response(200, __('api_msg.bookmark_removed'));
            } else {

                $insert = new Bookmark();
                $insert['user_id'] = $user_id;
                $insert['is_kids_profile'] = $is_kids_profile;
                $insert['video_type'] = $video_type;
                $insert['sub_video_type'] = $sub_video_type;
                $insert['video_id'] = $video_id;
                $insert['status'] = 1;
                if ($insert->save()) {
                    return $this->common->API_Response(200, __('api_msg.content_bookmarked'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_video_view(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
                'episode_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $video_type = $request['video_type'];
            $video_id = $request['video_id'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $episode_id = $request['episode_id'] ?? 0;

            $data = View::where('user_id', $user_id)->where('video_type', $video_type)->where('video_id', $video_id)->where('sub_video_type', $sub_video_type)->where('episode_id', $episode_id)->first();
            if ($data != null) {

                return $this->common->API_Response(200, __('api_msg.content_already_viewed'));
            } else {

                $insert = new View();
                $insert['user_id'] = $user_id;
                $insert['video_type'] = $video_type;
                $insert['sub_video_type'] = $sub_video_type;
                $insert['video_id'] = $video_id;
                $insert['episode_id'] = $episode_id;
                $insert['status'] = 1;

                if ($insert->save()) {
                    if ($video_type == 1 || (in_array($video_type, [6, 7]) && $sub_video_type == 1)) {

                        Video::where('id', $video_id)->increment('total_view', 1);
                    } else if ($video_type == 2 || (in_array($video_type, [6, 7]) && $sub_video_type == 2)) {

                        TVShow::where('id', $video_id)->increment('total_view', 1);
                        TVShow_Video::where('show_id', $video_id)->where('id', $episode_id)->increment('total_view', 1);
                    } else if ($video_type == 8) {

                        Shorts::where('id', $video_id)->increment('total_view', 1);
                        if ($sub_video_type == 3) {
                            Shorts_Episode::where('show_id', $video_id)->where('id', $episode_id)->increment('total_view', 1);
                        }
                    }
                    return $this->common->API_Response(200, __('api_msg.view_added'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_bookmark_video(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $device_id = $request['device_id'] ?? "";
            $page_no = $request->page_no ?? 1;
            $data = array();

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            $All_Video = Bookmark::where('user_id', $user_id)->where('is_kids_profile', $user_parent_control_status)->where('status', 1)->latest()->get();

            foreach ($All_Video as $key => $value) {

                if ($value['video_type'] == 1) {

                    $Video = Video::where('id', $value['video_id'])->where('video_type', 1)->where('status', 1)->first();
                    if ($Video != null && isset($Video)) {

                        $this->common->add_url_to_array(1, array($Video));
                        $this->common->rent_price_list(array($Video));
                        $Video['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $Video['rent_buy'] = $this->common->is_rent_buy($user_id, $Video['video_type'], 0, $Video['id']);
                        $Video['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $Video['video_type'], 0, $Video['id']);
                        $Video['is_bookmark'] = $this->common->is_bookmark($user_id, $Video['video_type'], 0, $Video['id'], $user_parent_control_status);
                        $Video['sub_video_type'] = 0;
                        $Video['stop_time'] = $this->common->get_stop_time($user_id, $Video['video_type'], 0, $Video['id'], 0);
                        $data[] = $Video;
                    }
                } elseif ($value['video_type'] == 2) {

                    $Video = TVShow::where('id', $value['video_id'])->where('video_type', 2)->where('status', 1)->first();
                    if ($Video != null && isset($Video)) {

                        $this->common->add_url_to_array(2, array($Video));
                        $this->common->rent_price_list(array($Video));
                        $Video['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $Video['rent_buy'] = $this->common->is_rent_buy($user_id, $Video['video_type'], 0, $Video['id']);
                        $Video['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $Video['video_type'], 0, $Video['id']);
                        $Video['is_bookmark'] = $this->common->is_bookmark($user_id, $Video['video_type'], 0, $Video['id'], $user_parent_control_status);
                        $Video['sub_video_type'] = 0;
                        $Video['stop_time'] = $this->common->get_stop_time($user_id, $Video['video_type'], 0, $Video['id'], 0);
                        $data[] = $Video;
                    }
                } elseif ($value['video_type'] == 5 || $value['video_type'] == 6 || $value['video_type'] == 7) {

                    if ($value['sub_video_type'] == 1) {

                        $Video = Video::where('id', $value['video_id'])->where('status', 1)->where('video_type', $value['video_type'])->first();
                        if ($Video != null && isset($Video)) {

                            $this->common->add_url_to_array(1, array($Video));
                            $this->common->rent_price_list(array($Video));
                            $Video['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $Video['rent_buy'] = $this->common->is_rent_buy($user_id, $Video['video_type'], 1, $Video['id']);
                            $Video['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $Video['video_type'], 1, $Video['id']);
                            $Video['is_bookmark'] = $this->common->is_bookmark($user_id, $Video['video_type'], 1, $Video['id'], $user_parent_control_status);
                            $Video['sub_video_type'] = 1;
                            $Video['stop_time'] = $this->common->get_stop_time($user_id, $Video['video_type'], 1, $Video['id'], 0);
                            $data[] = $Video;
                        }
                    } elseif ($value['sub_video_type'] == 2) {

                        $Video = TVShow::where('id', $value['video_id'])->where('video_type', $value['video_type'])->where('status', 1)->first();
                        if ($Video != null && isset($Video)) {

                            $this->common->add_url_to_array(2, array($Video));
                            $this->common->rent_price_list(array($Video));
                            $Video['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $Video['rent_buy'] = $this->common->is_rent_buy($user_id, $Video['video_type'], 2, $Video['id']);
                            $Video['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $Video['video_type'], 2, $Video['id']);
                            $Video['is_bookmark'] = $this->common->is_bookmark($user_id, $Video['video_type'], 2, $Video['id'], $user_parent_control_status);
                            $Video['sub_video_type'] = 2;
                            $Video['stop_time'] = $this->common->get_stop_time($user_id, $Video['video_type'], 2, $Video['id'], 0);
                            $data[] = $Video;
                        }
                    }
                } elseif ($value['video_type'] == 8) {

                    $Shorts = Shorts::where('id', $value['video_id'])->where('video_type', 8)->where('status', 1)->first();
                    if ($Shorts != null && isset($Shorts)) {

                        $this->common->add_url_to_array(1, array($Shorts));
                        $Shorts['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $Shorts['is_bookmark'] = $this->common->is_bookmark($user_id, $Shorts['video_type'], 0, $Shorts['id'], $user_parent_control_status);
                        $data[] = $Shorts;
                    }
                }
            }

            $currentItems = array_slice($data, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($currentItems, count($data), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $data = $paginator->items();

            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_comment(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
                'comment' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $insert = new Comment();
            $insert['comment_id'] = $request['comment_id'] ?? 0;
            $insert['user_id'] = $request['user_id'];
            $insert['video_type'] = $request['video_type'];
            $insert['sub_video_type'] = $request['sub_video_type'] ?? 0;
            $insert['video_id'] = $request['video_id'];
            $insert['comment'] = $request['comment'];
            $insert['status'] = 1;
            if ($insert->save()) {
                return $this->common->API_Response(200, __('api_msg.comment_added'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit_comment(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'comment_id' => 'required|numeric',
                'comment' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $comment = Comment::where('user_id', $request['user_id'])->where('id', $request['comment_id'])->first();
            if ($comment != null) {

                $comment->update(['comment' => $request['comment']]);
                return $this->common->API_Response(200, __('api_msg.comment_edited'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function delete_comment(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'comment_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $comment = Comment::where('user_id', $request['user_id'])->where('id', $request['comment_id'])->first();
            if ($comment != null) {

                $comment->delete();
                return $this->common->API_Response(200, __('api_msg.comment_deleted'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_comment(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'video_type' => 'required|numeric',
                'sub_video_type' => 'numeric',
                'video_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $video_type = $request['video_type'];
            $sub_video_type = $request['sub_video_type'] ?? 0;
            $video_id = $request['video_id'];
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Comment::where('comment_id', 0)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('status', 1)->with('user')->orderBy('id', 'DESC');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            if (count($data) > 0) {

                foreach ($data as $value) {

                    $value['user_name'] = "";
                    $value['full_name'] = "";
                    $value['user_image'] = "";
                    if ($value['user'] != null) {
                        $value['user_name'] = $value['user']['user_name'];
                        $value['full_name'] = $value['user']['full_name'];
                        if ($value['user']['image_type'] == 1) {
                            $value['user_image'] = $this->common->getImage($this->folder_user, $value['user']['image'], 'profile', $value['user']['storage_type']);
                        } else if ($value['user']['image_type'] == 2) {
                            $value['user_image'] = $this->common->getAvatarImage($value['user']['image'], $this->folder_avatar);
                        }
                    }
                    unset($value['user']);

                    $value['is_reply'] = 0;
                    $value['total_reply'] = 0;
                    $reply = Comment::where('comment_id', $value['id'])->where('status', 1)->count();
                    if ($reply != 0) {
                        $value['is_reply'] = 1;
                        $value['total_reply'] = $reply;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_replay_comment(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'comment_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $comment_id = $request->comment_id;
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Comment::where('comment_id', $comment_id)->where('status', 1)->with('user')->orderBy('id', 'DESC');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            if (count($data) > 0) {

                foreach ($data as $value) {

                    $value['user_name'] = "";
                    $value['user_image'] = "";
                    if ($value['user'] != null) {
                        $value['user_name'] = $value['user']['full_name'];
                        if ($value['user']['image_type'] == 1) {
                            $value['user_image'] = $this->common->getImage($this->folder_user, $value['user']['image'], 'profile', $value['user']['storage_type']);
                        } else if ($value['user']['image_type'] == 2) {
                            $value['user_image'] = $this->common->getAvatarImage($value['user']['image'], $this->folder_avatar);
                        }
                    }
                    unset($value['user']);

                    $value['is_reply'] = 0;
                    $value['total_reply'] = 0;
                    $reply = Comment::where('comment_id', $value['id'])->count();
                    if ($reply != 0) {
                        $value['is_reply'] = 1;
                        $value['total_reply'] = $reply;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_video_by_season_id(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'show_id' => 'required|numeric',
                'season_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $show_id = $request['show_id'];
            $season_id = $request['season_id'];
            $user_id = $request['user_id'] ?? 0;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            $show_data = TVShow::where('id', $show_id)->first();
            $data = TVShow_Video::where('season_id', $season_id)->where('show_id', $show_id)->where('status', 1)->orderBy('sort_order', 'asc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $this->common->add_url_to_array(3, $data);

                $sub_video_type = 0;
                if ($show_data['video_type'] != 2) {
                    $sub_video_type = 2;
                }

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$i]['is_rent'] = $show_data['is_rent'];
                    $data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $show_data['video_type'], $sub_video_type, $show_id);
                    $data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $show_data['video_type'], $sub_video_type, $show_id);
                    $data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $show_data['video_type'], $sub_video_type, $show_id, $data[$i]['id']);
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function search_content(Request $request)
    {
        try {
            $name = $request->input('name', '');
            $user_id = $request->input('user_id', 0);
            $page_no = $request->input('page_no', 1);
            $device_id = $request['device_id'] ?? "";

            $user_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            $video_data = [];
            $tvshow_data = [];

            if ($name != "") {
                $category_data = Category::where('name', $name)->where('status', 1)->latest()->get();
                $language_data = Language::where('name', $name)->where('status', 1)->latest()->get();

                $category_ids = $category_data->pluck('id')->toArray();
                $language_ids = $language_data->pluck('id')->toArray();
            } else {
                $category_ids = [];
                $language_ids = [];
            }

            // Video
            $video_data = Video::whereIn('video_type', $user_status == 1 ? [7] : [1, 6, 7])
                ->where('status', 1)
                ->where(function ($query) use ($name, $category_ids, $language_ids) {
                    $query->where('name', 'LIKE', "%{$name}%")
                        ->orWhere(function ($query) use ($category_ids) {
                            foreach ($category_ids as $category_id) {
                                $query->orWhereRaw("FIND_IN_SET(?, category_id)", [$category_id]);
                            }
                        })
                        ->orWhere(function ($query) use ($language_ids) {
                            foreach ($language_ids as $language_id) {
                                $query->orWhereRaw("FIND_IN_SET(?, language_id)", [$language_id]);
                            }
                        });
                })
                ->orderBy('id', 'DESC')->latest()->get();

            $this->common->add_url_to_array(1, $video_data);
            $this->common->rent_price_list($video_data);
            for ($i = 0; $i < count($video_data); $i++) {

                $video_sub_type = 0;
                if ($video_data[$i]['video_type'] == 6 || $video_data[$i]['video_type'] == 7) {
                    $video_sub_type = 1;
                }

                $video_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $video_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id']);
                $video_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], $user_status);
                $video_data[$i]['sub_video_type'] = $video_sub_type;
                $video_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $video_data[$i]['video_type'], $video_sub_type, $video_data[$i]['id'], 0);
            }

            // Show
            $tvshow_data = TVShow::whereIn('video_type', $user_status == 1 ? [7] : [2, 6, 7])->where('status', 1)
                ->where(function ($query) use ($name, $category_ids, $language_ids) {
                    $query->where('name', 'LIKE', "%{$name}%")
                        ->orWhere(function ($query) use ($category_ids) {
                            foreach ($category_ids as $category_id) {
                                $query->orWhereRaw("FIND_IN_SET(?, category_id)", [$category_id]);
                            }
                        })
                        ->orWhere(function ($query) use ($language_ids) {
                            foreach ($language_ids as $language_id) {
                                $query->orWhereRaw("FIND_IN_SET(?, language_id)", [$language_id]);
                            }
                        });
                })
                ->orderBy('id', 'DESC')->latest()->get();

            $this->common->add_url_to_array(2, $tvshow_data);
            $this->common->rent_price_list($tvshow_data);
            for ($i = 0; $i < count($tvshow_data); $i++) {

                $show_sub_type = 0;
                if ($tvshow_data[$i]['video_type'] == 6 || $tvshow_data[$i]['video_type'] == 7) {
                    $show_sub_type = 2;
                }

                $tvshow_data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                $tvshow_data[$i]['rent_buy'] = $this->common->is_rent_buy($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id']);
                $tvshow_data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], $user_status);
                $tvshow_data[$i]['sub_video_type'] = $show_sub_type;
                $tvshow_data[$i]['stop_time'] = $this->common->get_stop_time($user_id, $tvshow_data[$i]['video_type'], $show_sub_type, $tvshow_data[$i]['id'], 0);
            }

            $merged_data = array_merge($video_data->toArray(), $tvshow_data->toArray());
            usort($merged_data, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $currentItems = array_slice($merged_data, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($merged_data, count($merged_data), $this->page_limit, $page_no);
            $more_page = $this->common->more_page($page_no, $paginator->lastPage());

            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $data = $currentItems;

            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_continue_watching(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $device_id = $request['device_id'] ?? "";
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            // Check Parent Control
            $user_parent_control_status = $this->common->check_user_parent_control_status($user_id, $device_id);
            $data = Video_Watch::where('user_id', $user_id)->where('is_kids_profile', $user_parent_control_status)->where('status', 1)->latest()->orderBy('id', 'desc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $final_array = [];
                for ($j = 0; $j < count($data); $j++) {

                    if ($data[$j]['video_type'] == 1) {

                        $content_data = Video::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', 1)->first();
                        if ($content_data != null && isset($content_data)) {

                            $this->common->add_url_to_array(1, array($content_data));
                            $this->common->rent_price_list(array($content_data));

                            $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                            $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                            $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                            $content_data['sub_video_type'] = 0;
                            $content_data['stop_time'] = $data[$j]['stop_time'];
                            $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);
                            $final_array[] = $content_data;
                        }
                    } else if ($data[$j]['video_type'] == 2) {

                        $content_data = TVShow::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', 2)->first();
                        if ($content_data != null && isset($content_data)) {

                            $this->common->add_url_to_array(2, array($content_data));
                            $this->common->rent_price_list(array($content_data));

                            $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 0, $content_data['id']);
                            $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 0, $content_data['id']);
                            $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 0, $content_data['id'], $user_parent_control_status);
                            $content_data['sub_video_type'] = 0;
                            $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);

                            $episode = [];
                            $episode_data = TVShow_Video::where('id', $data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                            if ($episode_data != null && isset($episode_data)) {

                                $this->common->add_url_to_array(3, array($episode_data));
                                $episode = $episode_data->toArray();
                            }
                            $content_data['stop_time'] = $data[$j]['stop_time'];
                            $content_data['episode'] = $episode;

                            $final_array[] = $content_data;
                        }
                    } else if ($data[$j]['video_type'] == 6 || $data[$j]['video_type'] == 7) {

                        if ($data[$j]['sub_video_type'] == 1) {

                            $content_data = Video::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', $data[$j]['video_type'])->first();
                            if ($content_data != null && isset($content_data)) {

                                $this->common->add_url_to_array(1, array($content_data));
                                $this->common->rent_price_list(array($content_data));

                                $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 1, $content_data['id']);
                                $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 1, $content_data['id']);
                                $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 1, $content_data['id'], $user_parent_control_status);
                                $content_data['sub_video_type'] = 1;
                                $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);
                                $content_data['stop_time'] = $data[$j]['stop_time'];

                                $final_array[] = $content_data;
                            }
                        } else if ($data[$j]['sub_video_type'] == 2) {

                            $content_data = TVShow::where('id', $data[$j]['video_id'])->where('status', 1)->where('video_type', $data[$j]['video_type'])->first();
                            if ($content_data != null && isset($content_data)) {

                                $this->common->add_url_to_array(2, array($content_data));
                                $this->common->rent_price_list(array($content_data));

                                $content_data['is_buy'] = $this->common->is_any_package_buy($user_id);
                                $content_data['rent_buy'] = $this->common->is_rent_buy($user_id, $content_data['video_type'], 2, $content_data['id']);
                                $content_data['rent_expiry_date'] = $this->common->rent_expiry_date($user_id, $content_data['video_type'], 2, $content_data['id']);
                                $content_data['is_bookmark'] = $this->common->is_bookmark($user_id, $content_data['video_type'], 2, $content_data['id'], $user_parent_control_status);
                                $content_data['sub_video_type'] = 2;
                                $content_data['category_name'] = $this->common->get_category_name_by_ids($content_data['category_id']);

                                $episode = [];
                                $episode_data = TVShow_Video::where('id', $data[$j]['episode_id'])->where('show_id', $content_data['id'])->where('status', 1)->first();
                                if ($episode_data != null && isset($episode_data)) {

                                    $this->common->add_url_to_array(3, array($episode_data));
                                    $episode = $episode_data->toArray();
                                }
                                $content_data['stop_time'] = $data[$j]['stop_time'];
                                $content_data['episode'] = $episode;

                                $final_array[] = $content_data;
                            }
                        }
                    }
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $final_array, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_notification(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $NotiIds = Read_Notification::where('user_id', $user_id)->pluck('notification_id')->toArray();
            $data = Notification::whereNotIn('id', $NotiIds)->orderBy('id', 'desc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data = $data->take($total_page)->offset($offset)->get();
            if (count($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_notification, 'normal');

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function read_notification(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'notification_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $data['user_id'] = $request['user_id'];
            $data['notification_id'] = $request['notification_id'];
            Read_Notification::insertGetId($data);
            return $this->common->API_Response(200, __('api_msg.notification_read'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_remove_kids_mode(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'device_id' => 'required',
                'kids_mode' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $device_id = $request['device_id'];
            $kids_mode = $request['kids_mode'] ?? 0;

            $data = Device_Sync::where('user_id', $user_id)->where('device_id', $device_id)->first();
            if ($data != null && isset($data)) {

                $data['kids_mode'] = $kids_mode;
                $data->save();

                return $this->common->API_Response(200, __('api_msg.status_changed'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create_razorpay_order(Request $request)
    {
        try {
            $request->validate([
                'price' => 'required|numeric|min:1',
            ]);

            $price = $request['price'] * 100;
            $setting = Setting_Data();
            $payment = Payment_Option::where('id', 3)->first();

            $payload = [
                'amount' => $price,
                'currency' => $setting['currency'] ?? 'INR',
                'receipt' => 'receipt#' . uniqid(),
                'payment_capture' => 1
            ];

            $response = Http::withBasicAuth(
                $payment['key_1'],
                $payment['key_2']
            )->post('https://api.razorpay.com/v1/orders', $payload);

            if ($response->successful()) {
                return $this->common->API_Response(200, __('api_msg.order_created_successfully'), $response->json());
            }
            return $this->common->API_Response(400, __('api_msg.failed_to_create_order'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_shorts_list(Request $request)
    {
        try {
            $shorts_id = $request['shorts_id'] ?? 0;
            $user_id = $request['user_id'] ?? 0;
            $page_no = $request['page_no'] ?? 1;
            $page_size = 0;
            $more_page = false;

            $data = Shorts::where('status', 1)->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [$shorts_id])->orderBy('total_view', 'desc')->orderBy('total_like', 'desc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $this->common->add_url_to_array(4, $data);
                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$i]['is_bookmark'] = $this->common->is_bookmark($user_id, $data[$i]['video_type'], 0, $data[$i]['id'], 0);
                    $data[$i]['is_user_like'] = $this->common->is_like($user_id, $data[$i]['video_type'], 0, $data[$i]['id']);
                    $data[$i]['category_name'] = $this->common->get_category_name_by_ids($data[$i]['category_id']);
                    $data[$i]['total_comment'] = $this->common->total_comment($data[$i]['video_type'], 0, $data[$i]['id']);
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_shorts_episode(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'shorts_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $shorts_id = $request['shorts_id'];
            $season_id = $request['season_id'] ?? 0;
            $user_id = $request['user_id'] ?? 0;

            if ($season_id == 0) {
                $data = Shorts_Episode::where('show_id', $shorts_id)->where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();
            } else {
                $data = Shorts_Episode::where('show_id', $shorts_id)->where('season_id', $season_id)->where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();
            }

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['thumbnail'] = $this->common->getImage($this->folder_content, $data[$i]['thumbnail'], 'portrait', $data[$i]['storage_type']);
                    if ($data[$i]['video_upload_type'] == 'server_video') {
                        $data[$i]['video_320'] = $this->common->getVideo($this->folder_content, $data[$i]['video_320'], $data[$i]['video_storage_type']);
                    }

                    $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
