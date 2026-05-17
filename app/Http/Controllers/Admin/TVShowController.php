<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Common;
use App\Models\Language;
use App\Models\Producer;
use App\Models\Rent_Price_List;
use App\Models\Season;
use App\Models\TVShow;
use App\Models\TVShow_Video;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;

// Video Type = 1-Video, 2-Show, 3-Language, 4-Category, 5-Upcoming, 6-Channel, 7-Kids, 8-Shorts
// Video Upload Type = server_video, external, youtube
// Subtitle Type = server_video, external
// Trailer Type = server_video, external, youtube

class TVShowController extends Controller
{
    private $folder_content = "content";
    private $folder_cast = "cast";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['releases_type'] = Type::whereIn('type', [2, 6, 7])->where('status', 1)->get();
            $params['channel_list'] = Channel::where('status', 1)->latest()->get();
            $params['producer'] = Producer::where('status', 1)->latest()->get();

            $input_search = $request['input_search'];
            $input_rent = $request['input_rent'];
            $input_producer = $request['input_producer'];
            $input_status = $request['input_status'];

            $query = TVShow::where('type_id', $params['type']['id']);

            if ($input_search != null) {
                $query->where('name', 'LIKE', "%{$input_search}%");
            }
            if ($input_rent != null && $input_rent != 0) {
                $query->where('is_rent', 1);
            }
            if ($input_producer != null && $input_producer != 0) {
                $query->where('producer_id', $input_producer);
            }
            if ($input_status != null && $input_status != 'all') {
                $query->where('status', $input_status);
            }
            $params['result'] = $query->latest()->paginate(20)->appends([
                'input_search' => $input_search,
                'input_rent' => $input_rent,
                'input_status' => $input_status,
                'input_producer' => $input_producer,
            ]);

            $this->common->imageNameToUrl($params['result'], 'thumbnail', $this->folder_content, 'portrait');

            return view('admin.tv_show.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create($type_id)
    {
        try {

            $type = Type::where('id', $type_id)->where('status', 1)->first();
            if ($type == null) {
                return view('errors.404');
            }
            $params['type'] = $type;
            $params['channel'] = Channel::where('status', 1)->latest()->get();
            $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['language'] = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['producer'] = Producer::get();
            $params['cast'] = Cast::where('status', 1)->get();
            $params['rent_price_list'] = Rent_Price_List::where('status', 1)->get();

            return view('admin.tv_show.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'type_id' => 'required',
                'video_type' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'is_title' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'is_rent' => 'required',
            ];
            if ($request['video_type'] == 6) {
                $rules['channel_id'] = 'required';
            }
            if ($request['is_rent'] == 1) {
                $rules['price'] = 'required|numeric|min:1';
                $rules['rent_day'] = 'required|numeric|min:1';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $insert = new TVShow();
            $insert['type_id'] = $request['type_id'];
            $insert['video_type'] = $request['video_type'];
            $insert['channel_id'] = $request['channel_id'] ?? 0;
            $insert['producer_id'] = $request['producer_id'] ?? 0;
            $insert['category_id'] = implode(',', $request['category_id']);
            $insert['language_id'] = implode(',', $request['language_id']);
            $insert['cast_id'] = is_array($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
            $insert['name'] = $request['name'];

            $insert['storage_type'] = Storage_Type();
            $file = $request->file('thumbnail');
            $insert['thumbnail'] = "";
            if (isset($file) && $file != null) {
                $insert['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'show_', $insert['storage_type']);
            } elseif ($request['thumbnail_tmdb']) {
                $url = $request['thumbnail_tmdb'];
                $insert['thumbnail'] = $this->common->URLSaveInImage($url, $this->folder_content, 'show_', $insert['storage_type']);
            }
            $file1 = $request->file('landscape');
            $insert['landscape'] = "";
            if (isset($file1) && $file1 != null) {
                $insert['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'show_', $insert['storage_type']);
            } elseif ($request['landscape_tmdb']) {
                $url = $request['landscape_tmdb'];
                $insert['landscape'] = $this->common->URLSaveInImage($url, $this->folder_content, 'show_', $insert['storage_type']);
            }

            // Trailer
            $insert['trailer_storage_type'] = Storage_Type();
            $insert['trailer_type'] = $request['trailer_type'] ?? '';
            $trailer = $request['trailer'] ?? '';
            if ($request['trailer_type'] == "server_video") {

                if ($insert['trailer_storage_type'] == 1) {
                    $insert['trailer_url'] = $trailer;
                } else {
                    $insert['trailer_url'] = $trailer != null ? $this->common->saveImage($trailer, $this->folder_content, 'vid_', $insert['trailer_storage_type']) : "";
                }
            } else {
                $insert['trailer_url'] = $trailer;
            }

            $insert['description'] = $request['description'] ?? "";
            $insert['release_date'] = $request['release_date'] ?? "";
            $insert['is_title'] = $request['is_title'];
            $insert['is_comment'] = $request['is_comment'];
            $insert['is_like'] = $request['is_like'];
            $insert['is_rent'] = $request['is_rent'];
            $insert['price'] = $request['price'] ?? 0;
            $insert['rent_day'] = $request['rent_day'] ?? 0;
            $insert['total_view'] = 0;
            $insert['status'] = 1;

            if ($insert->save()) {

                // Send Notification
                $sub_video_type = 0;
                if ($insert->video_type == 2) {
                    $check = $this->common->NotificationConfiguration('add_tvshow');
                } else if ($insert->video_type == 5) {
                    $check = $this->common->NotificationConfiguration('add_upcoming_content');
                    $sub_video_type = 2;
                } else if ($insert->video_type == 6) {
                    $check = $this->common->NotificationConfiguration('add_channel_content');
                    $sub_video_type = 2;
                } else if ($insert->video_type == 7) {
                    $check = $this->common->NotificationConfiguration('add_kids_content');
                    $sub_video_type = 2;
                }
                if (isset($check) && $check['status'] == 1 && $check['send_notification'] == 1) {

                    $imageURL = $this->common->getImage($this->folder_content, $insert->thumbnail, 'normal', $insert->storage_type);
                    $noti_array = array(
                        'id' => $insert->id,
                        'name' => $insert->name,
                        'image' => $imageURL,
                        'type_id' => $insert->type_id,
                        'video_type' => $insert->video_type,
                        'sub_video_type' => $sub_video_type,
                        'description' => String_Cut($insert->description, 90),
                    );
                    $this->common->sendNotification($noti_array);
                }

                return response()->json(['status' => 200, 'success' => __('label.success_add_tvshow')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_tvshow')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id, $type_id)
    {
        try {
            $params['type_id'] = $type_id;
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if (!$params['type']) {
                return view('errors.404');
            }

            $params['data'] = TVshow::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['data']['thumbnail'] = $this->common->getImage($this->folder_content, $params['data']['thumbnail'], 'portrait', $params['data']['storage_type']);
                $params['data']['landscape'] = $this->common->getImage($this->folder_content, $params['data']['landscape'], 'landscape', $params['data']['storage_type']);

                $params['channel'] = Channel::where('status', 1)->latest()->get();
                $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['language'] = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['producer'] = Producer::get();
                $params['cast'] = Cast::where('status', 1)->get();
                $params['rent_price_list'] = Rent_Price_List::where('status', 1)->get();

                return view('admin.tv_show.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'category_id' => 'required',
                'language_id' => 'required',
                'is_title' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'is_rent' => 'required',
            ];
            if ($request['video_type'] == 6) {
                $rules['channel_id'] = 'required';
            }
            if ($request['is_rent'] == 1) {
                $rules['price'] = 'required|numeric|min:1';
                $rules['rent_day'] = 'required|numeric|min:1';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $TVShow = TVShow::where('id', $request['id'])->first();
            if (isset($TVShow->id)) {

                $TVShow['type_id'] = $request['type_id'];
                $TVShow['video_type'] = $request['video_type'];
                $TVShow['channel_id'] = $request['channel_id'] ?? 0;
                $TVShow['producer_id'] = $request['producer_id'] ?? 0;
                $TVShow['category_id'] = implode(',', $request['category_id']);
                $TVShow['language_id'] = implode(',', $request['language_id']);
                $TVShow['cast_id'] = is_array($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
                $TVShow['name'] = $request['name'];

                $file = $request->file('thumbnail');
                $file1 = $request->file('landscape');
                $storage_type = Storage_Type();
                if ($file != null && isset($file)) {

                    $TVShow['storage_type'] = $storage_type;
                    $TVShow['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'show_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                } elseif ($request['thumbnail_tmdb']) {

                    $url = $request['thumbnail_tmdb'];
                    $S_Name = $this->common->URLSaveInImage($url, $this->folder_content, 'show_', $storage_type);
                    $TVShow['thumbnail'] = $S_Name;
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                }
                if ($file1 != null && isset($file1)) {

                    $TVShow['storage_type'] = $storage_type;
                    $TVShow['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'show_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_landscape']), $request['old_storage_type']);
                } elseif ($request['landscape_tmdb']) {

                    $url = $request['landscape_tmdb'];
                    $S_Name = $this->common->URLSaveInImage($url, $this->folder_content, 'show_', $storage_type);
                    $TVShow['landscape'] = $S_Name;
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_landscape']), $request['old_storage_type']);
                }

                // Trailer
                $trailer_storage_type = Storage_Type();
                $TVShow['trailer_type'] = $request['trailer_type'] ?? '';
                if ($request['trailer_type'] == "server_video") {

                    if ($request['trailer_type'] == $request['old_trailer_type']) {

                        if ($request['trailer']) {

                            $TVShow['trailer_storage_type'] = $trailer_storage_type;
                            if ($trailer_storage_type == 1) {
                                $TVShow['trailer_url'] = $request['trailer'];
                            } else {
                                $TVShow['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'show_', $trailer_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        }
                    } else {
                        if ($request['trailer']) {

                            $TVShow['trailer_storage_type'] = $trailer_storage_type;
                            if ($trailer_storage_type == 1) {
                                $TVShow['trailer_url'] = $request['trailer'];
                            } else {
                                $TVShow['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'show_', $trailer_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        } else {
                            $TVShow['trailer_url'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_trailer']), $request['old_trailer_storage_type']);

                    $TVShow['trailer_storage_type'] = $trailer_storage_type;
                    $TVShow['trailer_url'] = $request['trailer_url'] ?? '';
                }

                $TVShow['description'] = $request['description'] ?? "";
                $TVShow['is_title'] = $request['is_title'];
                $TVShow['is_comment'] = $request['is_comment'];
                $TVShow['is_like'] = $request['is_like'];
                $TVShow['release_date'] =  $request['release_date'] ?? "";
                $TVShow['is_rent'] = $request['is_rent'];
                $TVShow['price'] = $TVShow['is_rent'] != 0 ? $request['price'] : 0;
                $TVShow['rent_day'] = $TVShow['is_rent'] != 0 ? $request['rent_day'] : 0;

                if ($TVShow->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_tvshow')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_tvshow')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_tvshow')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id, $type)
    {
        try {

            $TVShow = TVShow::where('id', $id)->first();
            if ($TVShow != null) {

                $this->common->deleteImageToFolder($this->folder_content, $TVShow['thumbnail'], $TVShow['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShow['landscape'], $TVShow['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShow['trailer_url'], $TVShow['trailer_storage_type']);

                $TVShowVideo = TVShow_Video::where('show_id', $TVShow['id'])->get();
                foreach ($TVShowVideo as $key => $value) {

                    $this->common->deleteImageToFolder($this->folder_content, $value['thumbnail'], $value['storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['landscape'], $value['storage_type']);

                    $this->common->deleteImageToFolder($this->folder_content, $value['video_320'], $value['video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['video_480'], $value['video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['video_720'], $value['video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['video_1080'], $value['video_storage_type']);

                    $this->common->deleteImageToFolder($this->folder_content, $value['subtitle_1'], $value['subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['subtitle_2'], $value['subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['subtitle_3'], $value['subtitle_storage_type']);
                    $value->delete();
                }
                $TVShow->delete();
                return redirect()->route('admin.tvshow.index', ['type_id' => $type])->with('success', __('label.content_delete'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {
            $data = TVShow::where('id', $request->id)->first();

            if ($data != null) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'id' => $data->id, 'status_code' => $data->status]);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function showReleases(Request $request)
    {
        try {
            $rules = [
                'id' => 'required',
                'type_id' => 'required',
            ];
            $check_type = Type::where('id', $request['type_id'])->first();
            if (isset($check_type['type']) && $check_type['type'] == 6) {
                $rules['channel_id'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $TVShow = TVShow::where('id', $request['id'])->first();
            if (isset($TVShow['id'])) {

                $TVShow['type_id'] = $request['type_id'];
                $TVShow['video_type'] = $check_type['type'];

                $TVShow['channel_id'] = 0;
                if ($check_type['type'] == 6) {
                    $TVShow['channel_id'] = $request['channel_id'];
                }

                if ($TVShow->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.tvshow_released')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_tvshow')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_tvshow')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // TVShow Video
    public function TVShowIndex(Request $request, $id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }
            $params['tvshow_id'] = $id;
            $params['season'] = Season::get();
            $params['sortorder_data'] = TVShow_Video::where('show_id', $id)->orderBy('sort_order', 'asc')->get();

            $input_search = $request['input_search'];
            $input_season = $request['input_season'];

            $query = TVShow_Video::where('show_id', $id);
            if ($input_search != null) {
                $query->where('name', 'LIKE', "%{$input_search}%");
            }
            if ($input_season != 0) {
                $query->where('season_id', $input_season);
            }
            $params['data'] = $query->orderBy('sort_order', 'asc')->paginate(20)->appends([
                'input_search' => $input_search,
                'input_season' => $input_season,
            ]);

            $this->common->imageNameToUrl($params['data'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->videoNameToUrl($params['data'], 'video_320', $this->folder_content);
            return view('admin.tv_show.ep_index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TVShowAdd($id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['tvshow_id'] = $id;
            $params['season'] = Season::get();
            return view('admin.tv_show.ep_add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TVShowSave(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'show_id' => 'required',
                'season_id' => 'required',
                'video_upload_type' => 'required',
                'subtitle_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
                'is_download' => 'required',
            ];
            $messages = [];
            if ($request['video_upload_type'] == "server_video") {
                $rules['video_320'] = 'required';
            } elseif ($request['video_upload_type'] == "vdocipher_id") {
                $rules['video_url_320'] = 'required';
                $messages['video_url_320.required'] = __('label.vdocipher_id_is_required');
            } else {
                $rules['video_url_320'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $insert = new TVShow_Video();
            $insert['show_id'] = $request['show_id'];
            $insert['season_id'] = $request['season_id'];
            $insert['name'] = $request['name'];

            // Image
            $insert['storage_type'] = Storage_Type();
            $insert['thumbnail'] = "";
            $insert['landscape'] = "";
            $file = $request->file('thumbnail');
            $file1 = $request->file('landscape');
            if (isset($file) && $file != null) {
                $insert['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'show_ep_', $insert['storage_type']);
            }
            if (isset($file1) && $file1 != null) {
                $insert['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'show_ep_', $insert['storage_type']);
            }
            $insert['description'] = $request['description'] ?? "";

            // Video (320, 480, 720, 1080)
            $insert['video_upload_type'] = $request['video_upload_type'];
            $insert['video_storage_type'] = Storage_Type();
            if ($insert['video_upload_type'] == "server_video") {

                $video_320 = $request['video_320'] ?? '';
                $video_480 = $request['video_480'] ?? '';
                $video_720 = $request['video_720'] ?? '';
                $video_1080 = $request['video_1080'] ?? '';
                if ($insert['video_storage_type'] == 1) {

                    $insert['video_320'] = $video_320;
                    $insert['video_480'] = $video_480;
                    $insert['video_720'] = $video_720;
                    $insert['video_1080'] = $video_1080;
                } else {

                    $insert['video_320'] = $this->common->saveImage($video_320, $this->folder_content, 'vid_', $insert['video_storage_type']);
                    $insert['video_480'] = $video_480 != null ? $this->common->saveImage($video_480, $this->folder_content, 'vid_', $insert['video_storage_type']) : "";
                    $insert['video_720'] = $video_720 != null ? $this->common->saveImage($video_720, $this->folder_content, 'vid_', $insert['video_storage_type']) : "";
                    $insert['video_1080'] = $video_1080 != null ? $this->common->saveImage($video_1080, $this->folder_content, 'vid_', $insert['video_storage_type']) : "";
                }

                $array = explode('.',  $insert['video_320']);
                $insert['video_extension'] = end($array);
            } else {

                $insert['video_320'] = $request['video_url_320'] ?? '';
                $insert['video_480'] = $request['video_url_480'] ?? '';
                $insert['video_720'] = $request['video_url_720'] ?? '';
                $insert['video_1080'] = $request['video_url_1080'] ?? '';

                $array = explode('.', $request['video_url_320']);
                $array1 = explode('?', end($array));
                if (isset($array1) && $array1 != null) {
                    $insert['video_extension'] = isset($array1) ? reset($array1) : "";
                } else {
                    $insert['video_extension'] = "";
                }
            }
            $insert['video_duration'] = isset($request['video_duration']) ? Time_To_Milliseconds($request['video_duration']) : 0;

            // Subtitle_1_2_3
            $insert['subtitle_storage_type'] = Storage_Type();
            $insert['subtitle_type'] = $request['subtitle_type'] ?? '';
            $insert['subtitle_lang_1'] = $request['subtitle_lang_1'] ?? '';
            $insert['subtitle_lang_2'] = $request['subtitle_lang_2'] ?? '';
            $insert['subtitle_lang_3'] = $request['subtitle_lang_3'] ?? '';
            if ($request->subtitle_type == "server_video") {

                $subtitle_1 = $request['subtitle_1'] ?? '';
                $subtitle_2 = $request['subtitle_2'] ?? '';
                $subtitle_3 = $request['subtitle_3'] ?? '';
                if ($insert['subtitle_storage_type'] == 1) {

                    $insert['subtitle_1'] = $subtitle_1;
                    $insert['subtitle_2'] = $subtitle_2;
                    $insert['subtitle_3'] = $subtitle_3;
                } else {

                    $insert['subtitle_1'] = $subtitle_1 != null ? $this->common->saveImage($subtitle_1, $this->folder_content, 'show_ep_', $insert['subtitle_storage_type']) : "";
                    $insert['subtitle_2'] = $subtitle_2 != null ? $this->common->saveImage($subtitle_2, $this->folder_content, 'show_ep_', $insert['subtitle_storage_type']) : "";
                    $insert['subtitle_3'] = $subtitle_3 != null ? $this->common->saveImage($subtitle_3, $this->folder_content, 'show_ep_', $insert['subtitle_storage_type']) : "";
                }
            } else {
                $insert['subtitle_1'] = $request['subtitle_url_1'] ?? '';
                $insert['subtitle_2'] = $request['subtitle_url_2'] ?? '';
                $insert['subtitle_3'] = $request['subtitle_url_3'] ?? '';
            }

            $insert['is_premium'] = $request->is_premium;
            $insert['is_title'] = $request->is_title;
            $insert['is_download'] = $request['video_upload_type'] == "server_video" ? $request['is_download'] : 0;
            $insert['total_view'] = 0;
            $insert['sort_order'] = $this->common->getSortOrder($insert['show_id']);
            $insert['status'] = 1;

            if ($insert->save()) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_episode')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_episode')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TVShowedit($id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['data'] = TVShow_Video::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['season'] = Season::get();
                $params['data']['thumbnail'] = $this->common->getImage($this->folder_content, $params['data']['thumbnail'], 'portrait', $params['data']['storage_type']);
                $params['data']['landscape'] = $this->common->getImage($this->folder_content, $params['data']['landscape'], 'landscape', $params['data']['storage_type']);

                return view('admin.tv_show.ep_edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TVShowUpdate(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'show_id' => 'required',
                'season_id' => 'required',
                'video_upload_type' => 'required',
                'subtitle_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
                'is_download' => 'required',
            ];
            $messages = [];
            if ($request['video_upload_type'] != "server_video") {
                $rules['video_url_320'] = 'required';
                if ($request['video_upload_type'] == "vdocipher_id") {
                    $messages['video_url_320.required'] = __('label.vdocipher_id_is_required');
                }
            }
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $TVShowVideo = TVShow_Video::where('id', $request->id)->first();
            if (isset($TVShowVideo->id)) {

                $TVShowVideo['season_id'] = $request['season_id'];
                $TVShowVideo['name'] = $request['name'];
                // Image
                $file = $request->file('thumbnail');
                $file1 = $request->file('landscape');
                $storage_type = Storage_Type();
                if ($file != null) {
                    $TVShowVideo['storage_type'] = $storage_type;
                    $TVShowVideo['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'show_ep_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                }
                if ($file1 != null) {
                    $TVShowVideo['storage_type'] = $storage_type;
                    $TVShowVideo['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'show_ep_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_landscape']), $request['old_storage_type']);
                }
                $TVShowVideo['description'] = $request['description'] ?? "";

                // Videos
                $video_storage_type = Storage_Type();
                $TVShowVideo['video_upload_type'] = $request['video_upload_type'];
                if ($request['video_upload_type'] == "server_video") {

                    if ($request['video_upload_type'] == $request['old_video_upload_type']) {

                        if ($request['video_320']) {

                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_320'] = $request['video_320'];
                            } else {
                                $TVShowVideo['video_320'] = $this->common->saveImage($request['video_320'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);

                            $array = explode('.', $TVShowVideo['video_320']);
                            $TVShowVideo['video_extension'] = end($array);
                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                        }
                        if ($request['video_480']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_480'] = $request['video_480'];
                            } else {
                                $TVShowVideo['video_480'] = $this->common->saveImage($request['video_480'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                        }
                        if ($request['video_720']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_720'] = $request['video_720'];
                            } else {
                                $TVShowVideo['video_720'] = $this->common->saveImage($request['video_720'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                        }
                        if ($request['video_1080']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_1080'] = $request['video_1080'];
                            } else {
                                $TVShowVideo['video_1080'] = $this->common->saveImage($request['video_1080'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);
                        }
                    } else {
                        if ($request['video_320']) {

                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_320'] = $request['video_320'];
                            } else {
                                $TVShowVideo['video_320'] = $this->common->saveImage($request['video_320'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);

                            $array = explode('.', $TVShowVideo['video_320']);
                            $TVShowVideo['video_extension'] = end($array);
                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                        } else {
                            $TVShowVideo['video_320'] = "";
                        }
                        if ($request['video_480']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_480'] = $request['video_480'];
                            } else {
                                $TVShowVideo['video_480'] = $this->common->saveImage($request['video_480'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                        } else {
                            $TVShowVideo['video_480'] = "";
                        }
                        if ($request['video_720']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_720'] = $request['video_720'];
                            } else {
                                $TVShowVideo['video_720'] = $this->common->saveImage($request['video_720'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                        } else {
                            $TVShowVideo['video_720'] = "";
                        }
                        if ($request['video_1080']) {

                            $TVShowVideo['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $TVShowVideo['video_1080'] = $request['video_1080'];
                            } else {
                                $TVShowVideo['video_1080'] = $this->common->saveImage($request['video_1080'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);
                        } else {
                            $TVShowVideo['video_1080'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);

                    if ($request['video_url_320']) {

                        $array = explode('.', $request['video_url_320']);
                        $array1 = explode('?', end($array));
                        if (isset($array1) && $array1 != null) {
                            $TVShowVideo['video_extension'] = isset($array1) ? reset($array1) : "";
                        } else {
                            $TVShowVideo['video_extension'] = "";
                        }

                        $TVShowVideo['video_320'] = $request['video_url_320'];
                    }
                    $TVShowVideo['video_480'] = $request['video_url_480'] ?? '';
                    $TVShowVideo['video_720'] = $request['video_url_720'] ?? '';
                    $TVShowVideo['video_1080'] = $request['video_url_1080'] ?? '';
                }
                $TVShowVideo['video_duration'] = isset($request->video_duration) ? Time_To_Milliseconds($request->video_duration) : 0;

                // Subtitle
                $subtitle_storage_type = Storage_Type();
                $TVShowVideo['subtitle_type'] = $request['subtitle_type'] ?? '';
                $TVShowVideo['subtitle_lang_1'] =  $request['subtitle_lang_1'] ?? '';
                $TVShowVideo['subtitle_lang_2'] =  $request['subtitle_lang_2'] ?? '';
                $TVShowVideo['subtitle_lang_3'] =  $request['subtitle_lang_3'] ?? '';
                if ($request['subtitle_type'] == "server_video") {

                    if ($request['subtitle_type'] == $request['old_subtitle_type']) {

                        if ($request['subtitle_1']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_1'] = $request['subtitle_1'];
                            } else {
                                $TVShowVideo['subtitle_1'] = $this->common->saveImage($request['subtitle_1'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                        }
                        if ($request['subtitle_2']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_2'] = $request['subtitle_2'];
                            } else {
                                $TVShowVideo['subtitle_2'] = $this->common->saveImage($request['subtitle_2'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                        }
                        if ($request['subtitle_3']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_3'] = $request['subtitle_3'];
                            } else {
                                $TVShowVideo['subtitle_3'] = $this->common->saveImage($request['subtitle_3'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);
                        }
                    } else {
                        if ($request['subtitle_1']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_1'] = $request['subtitle_1'];
                            } else {
                                $TVShowVideo['subtitle_1'] = $this->common->saveImage($request['subtitle_1'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                        } else {
                            $TVShowVideo['subtitle_1'] = "";
                        }
                        if ($request['subtitle_2']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_2'] = $request['subtitle_2'];
                            } else {
                                $TVShowVideo['subtitle_2'] = $this->common->saveImage($request['subtitle_2'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                        } else {
                            $TVShowVideo['subtitle_2'] = "";
                        }
                        if ($request['subtitle_3']) {

                            $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {

                                $TVShowVideo['subtitle_3'] = $request['subtitle_3'];
                            } else {
                                $TVShowVideo['subtitle_3'] = $this->common->saveImage($request['subtitle_3'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);
                        } else {
                            $TVShowVideo['subtitle_3'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);

                    $TVShowVideo['subtitle_storage_type'] = $subtitle_storage_type;
                    $TVShowVideo['subtitle_1'] = $request['subtitle_url_1'] ?? '';
                    $TVShowVideo['subtitle_2'] = $request['subtitle_url_2'] ?? '';
                    $TVShowVideo['subtitle_3'] = $request['subtitle_url_3'] ?? '';
                }

                $TVShowVideo['is_premium'] = $request->is_premium;
                $TVShowVideo['is_title'] = $request->is_title;
                $TVShowVideo['is_download'] = $request['video_upload_type'] == "server_video" ? $request['is_download'] : 0;
                if ($TVShowVideo->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_episode')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_episode')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_episode')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TVShowDelete($show_id, $id, $type_id)
    {
        try {

            $TVShowVideo = TVShow_Video::where('id', $id)->first();
            if ($TVShowVideo != null) {

                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['thumbnail'], $TVShowVideo['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['landscape'], $TVShowVideo['storage_type']);

                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['video_320'], $TVShowVideo['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['video_480'], $TVShowVideo['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['video_720'], $TVShowVideo['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['video_1080'], $TVShowVideo['video_storage_type']);

                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['subtitle_1'], $TVShowVideo['subtitle_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['subtitle_2'], $TVShowVideo['subtitle_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $TVShowVideo['subtitle_3'], $TVShowVideo['subtitle_storage_type']);

                $TVShowVideo->delete();

                return redirect()->route('admin.tvshow.episode.index', ['id' => $show_id, 'type_id' => $type_id])->with('success', __('label.episode_delete'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // Sortable
    public function TVShowSortable(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    TVShow_Video::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // TMDb
    public function SerachName($txtVal)
    {
        try {
            $tmdbTitle = $txtVal;
            $tmdb_api_key = TMDB_API_Key();

            if (strlen($tmdbTitle) >= 3 && $tmdb_api_key != "" && isset($tmdb_api_key) && $tmdb_api_key != null) {

                $url = 'https://api.themoviedb.org/3/search/tv?api_key=' . $tmdb_api_key . '&language=en-US&page=1&include_adult=false&query=' . $tmdbTitle;
                $response = Http::get($url);
                $Status = $response->getStatusCode();
                $Data = $response->json();

                if ($Status == 200) {
                    return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'data' => $Data]);
                }
            } else {
                return response()->json(['status' => 400, 'success' => __('label.enter_tmdb_key')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function GetData($tmdbID)
    {
        try {

            $tmdb_api_key = TMDB_API_Key();
            if ($tmdb_api_key != "" && isset($tmdb_api_key) && $tmdb_api_key != null) {

                $url = 'https://api.themoviedb.org/3/tv/' . $tmdbID . '?api_key=' . $tmdb_api_key . '&append_to_response=credits&language=en-US';
                $response = Http::get($url);
                $Status = $response->getStatusCode();
                $movies = $response->json();
                $storage_type = Storage_Type();

                // Category
                $C_Id = [];
                $C_Insert_Data = [];
                if (isset($movies['genres']) && $movies['genres'] != null && count($movies['genres']) > 0) {

                    for ($i = 0; $i < count($movies['genres']); $i++) {

                        $Category = Category::where(DB::raw('lower(name)'), strtolower($movies['genres'][$i]['name']))->first();
                        if (!empty($Category)) {

                            $C_Id[] = $Category['id'];
                        } else {

                            $insert = new Category();
                            $insert['name'] = $movies['genres'][$i]['name'];
                            $insert['storage_type'] = $storage_type;
                            $insert['image'] = "";
                            $insert['sort_order'] = 0;
                            $insert['status'] = 1;
                            $insert->save();

                            $C_Id[] = $insert['id'];
                            $C_Insert_Data[] = $insert;
                        }
                    }
                }
                $params['C_Id'] = $C_Id;
                $params['C_Insert_Data'] = $C_Insert_Data;

                // Language
                $L_Id = [];
                $L_Insert_Data = [];
                if (isset($movies['spoken_languages']) && $movies['spoken_languages'] != null && count($movies['spoken_languages']) > 0) {

                    for ($i = 0; $i < count($movies['spoken_languages']); $i++) {

                        $Language = Language::where(DB::raw('lower(name)'), strtolower($movies['spoken_languages'][$i]['english_name']))->first();
                        if (!empty($Language)) {

                            $L_Id[] = $Language['id'];
                        } else {

                            $insert = new Language();
                            $insert['name'] = $movies['spoken_languages'][$i]['english_name'];
                            $insert['storage_type'] = $storage_type;
                            $insert['image'] = "";
                            $insert['sort_order'] = 0;
                            $insert['status'] = 1;
                            $insert->save();

                            $L_Id[] = $insert['id'];
                            $L_Insert_Data[] = $insert;
                        }
                    }
                }
                $params['L_Id'] = $L_Id;
                $params['L_Insert_Data'] = $L_Insert_Data;

                // Cast
                $Cast_Id = [];
                $Cast_Insert_Data = [];
                if (isset($movies['credits']['cast']) && $movies['credits']['cast'] != null && count($movies['credits']['cast']) > 0) {

                    for ($i = 0; $i < count($movies['credits']['cast']); $i++) {

                        $CastData = Cast::where(DB::raw('lower(name)'), strtolower($movies['credits']['cast'][$i]['name']))->first();
                        if (!empty($CastData)) {

                            $Cast_Id[] = $CastData['id'];
                        } else {

                            $insert = new Cast();
                            $insert['name'] = $movies['credits']['cast'][$i]['name'];
                            $insert['storage_type'] = $storage_type;
                            $castImage = "";
                            if ($movies['credits']['cast'][$i]['profile_path'] != null) {
                                $img_url = 'https://image.tmdb.org/t/p/original' . $movies['credits']['cast'][$i]['profile_path'];
                                $castImage = $this->common->URLSaveInImage($img_url, $this->folder_cast, 'cast_', $storage_type);
                            }
                            $insert['image'] = $castImage;
                            $insert['type'] = "Actor";
                            $insert['personal_info'] = $movies['credits']['cast'][$i]['character'];
                            $insert['status'] = 1;
                            $insert->save();

                            $Cast_Id[] = $insert['id'];
                            $Cast_Insert_Data[] = $insert;
                        }
                        if ($i == 9) {
                            break;
                        }
                    }
                }
                $params['Cast_Id'] = $Cast_Id;
                $params['Cast_Insert_Data'] = $Cast_Insert_Data;

                // Poster
                $Thumbnail = "";
                if (isset($movies['poster_path']) && $movies['poster_path'] != null) {
                    $img_url = 'https://image.tmdb.org/t/p/original' . $movies['poster_path'];
                    $Thumbnail = $img_url;
                }
                $params['Thumbnail'] = $Thumbnail;

                // Title
                $Title = "";
                if (isset($movies['name']) && $movies['name'] != null) {
                    $Title = $movies['name'];
                }
                $params['Title'] = $Title;

                // Description
                $Description = "";
                if (isset($movies['overview'])) {
                    $Description = $movies['overview'];
                }
                $params['Description'] = $Description;

                // Release Date
                $Release_Date = date('Y-m-d');
                if (isset($movies['first_air_date']) && $movies['first_air_date'] != null) {
                    $Release_Date = $movies['first_air_date'];
                }
                $params['Release_Date'] = $Release_Date;

                return response()->json(['status' => 200, 'data' => $params]);
            } else {
                return response()->json(['status' => 400, 'success' => __('label.enter_tmdb_key')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
