<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Cast;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Common;
use App\Models\Download;
use App\Models\Language;
use App\Models\Rent_Price_List;
use App\Models\Type;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

// Video Type = 1-Video, 2-Show, 3-Language, 4-Category, 5-Upcoming, 6- Channel, 7- Kids
// Video Upload Type = server_video, external, youtube, live_stream_url
// Trailer Type = server_video, external, youtube
// Subtitle Type = server_video, external

class VideoController extends Controller
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

            $type = Type::where('id', $type_id)->where('status', 1)->first();
            if ($type == null) {
                return view('errors.404');
            }
            $producer = Producer_Data();
            $params['type'] = $type;
            $params['releases_type'] = Type::whereIn('type', [1, 6, 7])->where('status', 1)->get();
            $params['channel_list'] = Channel::where('status', 1)->latest()->get();

            $input_search = $request['input_search'];
            $input_premimum = $request['input_premimum'];
            $input_rent = $request['input_rent'];
            $input_status = $request['input_status'];

            $query = Video::where('producer_id', $producer['id'])->where('type_id', $type['id']);

            if ($input_search != null) {
                $query->where('name', 'LIKE', "%{$input_search}%");
            }
            if ($input_rent != null && $input_rent != 0) {
                $query->where('is_rent', 1);
            }
            if ($input_premimum != null && $input_premimum != 'all') {
                $query->where('is_premium', $input_premimum);
            }
            if ($input_status != null && $input_status != 'all') {
                $query->where('status', $input_status);
            }
            $params['result'] = $query->latest()->paginate(20)->appends([
                'input_search' => $input_search,
                'input_rent' => $input_rent,
                'input_premimum' => $input_premimum,
                'input_status' => $input_status,
            ]);

            $this->common->imageNameToUrl($params['result'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->videoNameToUrl($params['result'], 'video_320', $this->folder_content);

            return view('producer.video.index', $params);
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
            $params['cast'] = Cast::where('status', 1)->get();
            $params['rent_price_list'] = Rent_Price_List::where('status', 1)->get();

            return view('producer.video.add', $params);
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
                'video_upload_type' => 'required',
                'subtitle_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
                'is_download' => 'required',
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
            $messages = [];
            if ($request['video_upload_type'] == "server_video") {
                $rules['video_320'] = 'required';
            } elseif ($request['video_upload_type'] == "live_stream_url") {
                $rules['video_url_320'] = 'required';
                $messages['video_url_320.required'] = __('label.the_live_stream_url_is_required');
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

            $producer = Producer_Data();
            $insert = new Video();
            $insert['type_id'] = $request['type_id'];
            $insert['video_type'] = $request['video_type'];
            $insert['channel_id'] = isset($request['channel_id']) ? $request['channel_id'] : 0;
            $insert['producer_id'] = $producer['id'];
            $insert['category_id'] = implode(',', $request['category_id']);
            $insert['language_id'] = implode(',', $request['language_id']);
            $insert['cast_id'] = isset($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
            $insert['name'] = $request['name'];
            $insert['storage_type'] = Storage_Type();
            $file = $request->file('thumbnail');
            $insert['thumbnail'] = "";
            if (isset($file) && $file != null) {
                $insert['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'vid_', $insert['storage_type']);
            } elseif ($request['thumbnail_tmdb']) {
                $url = $request['thumbnail_tmdb'];
                $insert['thumbnail'] = $this->common->URLSaveInImage($url, $this->folder_content, 'vid_', $insert['storage_type']);
            }
            $file1 = $request->file('landscape');
            $insert['landscape'] = "";
            if (isset($file1) && $file1 != null) {
                $insert['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'vid_', $insert['storage_type']);
            } elseif ($request['landscape_tmdb']) {
                $url = $request['landscape_tmdb'];
                $insert['landscape'] = $this->common->URLSaveInImage($url, $this->folder_content, 'vid_', $insert['storage_type']);
            }
            $insert['description'] = $request['description'] ?? "";

            // Video (320, 480, 720, 1080)
            $insert['video_storage_type'] = Storage_Type();
            $insert['video_upload_type'] = $request['video_upload_type'];
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

                $array = explode('.', $insert['video_320']);
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
            // Trailer
            $insert['trailer_storage_type'] = Storage_Type();
            $insert['trailer_type'] = $request['trailer_type'] ?? '';
            if ($insert['trailer_type'] == "server_video") {

                $trailer = $request['trailer'] ?? '';
                if ($insert['trailer_storage_type'] == 1) {
                    $insert['trailer_url'] = $trailer;
                } else {
                    $insert['trailer_url'] = $trailer != null ? $this->common->saveImage($trailer, $this->folder_content, 'vid_', $insert['trailer_storage_type']) : "";
                }
            } else {
                $insert['trailer_url'] = $request['trailer_url'] ?? '';
            }
            // Subtitle
            $insert['subtitle_storage_type'] = Storage_Type();
            $insert['subtitle_type'] = $request['subtitle_type'] ?? '';
            $insert['subtitle_lang_1'] = $request['subtitle_lang_1'] ?? '';
            $insert['subtitle_lang_2'] = $request['subtitle_lang_2'] ?? '';
            $insert['subtitle_lang_3'] = $request['subtitle_lang_3'] ?? '';
            if ($insert['subtitle_type'] == "server_video") {

                $subtitle_1 = $request['subtitle_1'] ?? '';
                $subtitle_2 = $request['subtitle_2'] ?? '';
                $subtitle_3 = $request['subtitle_3'] ?? '';
                if ($insert['subtitle_storage_type'] == 1) {

                    $insert['subtitle_1'] = $subtitle_1;
                    $insert['subtitle_2'] = $subtitle_2;
                    $insert['subtitle_3'] = $subtitle_3;
                } else {

                    $insert['subtitle_1'] = $subtitle_1 != null ? $this->common->saveImage($subtitle_1, $this->folder_content, 'vid_', $insert['subtitle_storage_type']) : "";
                    $insert['subtitle_2'] = $subtitle_2 != null ? $this->common->saveImage($subtitle_2, $this->folder_content, 'vid_', $insert['subtitle_storage_type']) : "";
                    $insert['subtitle_3'] = $subtitle_3 != null ? $this->common->saveImage($subtitle_3, $this->folder_content, 'vid_', $insert['subtitle_storage_type']) : "";
                }
            } else {
                $insert['subtitle_1'] = $request['subtitle_url_1'] ?? '';
                $insert['subtitle_2'] = $request['subtitle_url_2'] ?? '';
                $insert['subtitle_3'] = $request['subtitle_url_3'] ?? '';
            }
            $insert['release_date'] = $request['release_date'] ?? "";
            $insert['is_premium'] = $request['is_premium'];
            $insert['is_title'] = $request['is_title'];
            $insert['is_download'] = $request['video_upload_type'] == "server_video" ? $request['is_download'] : 0;
            $insert['is_comment'] = $request['is_comment'];
            $insert['is_like'] = $request['is_like'];
            $insert['is_rent'] = $request['is_rent'];
            $insert['price'] = $request['price'] ?? 0;
            $insert['rent_day'] = $request['rent_day'] ?? 0;
            $insert['total_view'] = 0;
            $insert['total_like'] = 0;
            $insert['status'] = 1;

            if ($insert->save()) {

                // Send Notification
                $sub_video_type = 0;
                if ($insert->video_type == 1) {
                    $check = $this->common->NotificationConfiguration('add_movies');
                } else if ($insert->video_type == 5) {
                    $check = $this->common->NotificationConfiguration('add_upcoming_content');
                    $sub_video_type = 1;
                } else if ($insert->video_type == 6) {
                    $check = $this->common->NotificationConfiguration('add_channel_content');
                    $sub_video_type = 1;
                } else if ($insert->video_type == 7) {
                    $check = $this->common->NotificationConfiguration('add_kids_content');
                    $sub_video_type = 1;
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

                return response()->json(['status' => 200, 'success' => __('label.success_add_video')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_video')]);
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

            $params['data'] = Video::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['data']['thumbnail'] = $this->common->getImage($this->folder_content, $params['data']['thumbnail'], 'portrait', $params['data']['storage_type']);
                $params['data']['landscape'] = $this->common->getImage($this->folder_content, $params['data']['landscape'], 'landscape', $params['data']['storage_type']);


                $params['channel'] = Channel::where('status', 1)->latest()->get();
                $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['language'] = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['cast'] = Cast::where('status', 1)->get();
                $params['rent_price_list'] = Rent_Price_List::where('status', 1)->get();

                return view('producer.video.edit', $params);
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
                'video_upload_type' => 'required',
                'subtitle_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
                'is_download' => 'required',
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
            $messages = [];
            if ($request['video_upload_type'] != "server_video") {
                $rules['video_url_320'] = 'required';
                if ($request['video_upload_type'] == 'live_stream_url') {
                    $messages['video_url_320.required'] = __('label.the_live_stream_url_is_required');
                } elseif ($request['video_upload_type'] == "vdocipher_id") {
                    $messages['video_url_320.required'] = __('label.vdocipher_id_is_required');
                }
            }
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $video = Video::where('id', $request->id)->first();
            if (isset($video->id)) {

                $producer = Producer_Data();

                $video['video_type'] = $request['video_type'];
                $video['channel_id'] = $request['channel_id'] ?? 0;
                $video['producer_id'] = $producer['id'];
                $video['category_id'] = implode(',', $request['category_id']);
                $video['language_id'] = implode(',', $request['language_id']);
                $video['cast_id'] = isset($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
                $video['name'] = $request['name'];

                // Image
                $storage_type = Storage_Type();
                $file = $request->file('thumbnail');
                if (isset($file) && $file != null) {
                    $video['storage_type'] = $storage_type;
                    $video['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'vid_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                } elseif ($request['thumbnail_tmdb']) {
                    $video['storage_type'] = $storage_type;
                    $url = $request['thumbnail_tmdb'];
                    $video['thumbnail'] = $this->common->URLSaveInImage($url, $this->folder_content, 'vid_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                }
                $file1 = $request->file('landscape');
                if (isset($file1) && $file1 != null) {
                    $video['storage_type'] = $storage_type;
                    $video['landscape'] = $this->common->saveImage($file1, $this->folder_content, 'vid_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_landscape']), $request['old_storage_type']);
                } elseif ($request['landscape_tmdb']) {
                    $video['storage_type'] = $storage_type;
                    $url = $request['landscape_tmdb'];
                    $video['landscape'] = $this->common->URLSaveInImage($url, $this->folder_content, 'vid_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_landscape']), $request['old_storage_type']);
                }
                $video['description'] = $request['description'] ?? "";

                // Videos (320, 420, 720, 1080)
                $video['video_upload_type'] = $request['video_upload_type'];
                $video_storage_type = Storage_Type();
                if ($request['video_upload_type'] == "server_video") {

                    if ($request['video_upload_type'] == $request['old_video_upload_type']) {

                        if ($request['video_320']) {

                            if ($video_storage_type == 1) {
                                $video['video_320'] = $request['video_320'];
                            } else {
                                $video['video_320'] = $this->common->saveImage($request['video_320'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);

                            $array = explode('.', $video['video_320']);
                            $video['video_extension'] = end($array);
                            $video['video_storage_type'] = $video_storage_type;
                        }
                        if ($request['video_480']) {

                            $video['video_storage_type'] = $video_storage_type;
                            if ($video_storage_type == 1) {
                                $video['video_480'] = $request['video_480'];
                            } else {
                                $video['video_480'] = $this->common->saveImage($request['video_480'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                        }
                        if ($request['video_720']) {

                            $video['video_storage_type'] = $video_storage_type;

                            if ($video_storage_type == 1) {
                                $video['video_720'] = $request['video_720'];
                            } else {
                                $video['video_720'] = $this->common->saveImage($request['video_720'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                        }
                        if ($request['video_1080']) {

                            $video['video_storage_type'] = $video_storage_type;

                            if ($video_storage_type == 1) {
                                $video['video_1080'] = $request['video_1080'];
                            } else {
                                $video['video_1080'] = $this->common->saveImage($request['video_1080'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);
                        }
                    } else {
                        if ($request['video_320']) {

                            if ($video_storage_type == 1) {
                                $video['video_320'] = $request['video_320'];
                            } else {
                                $video['video_320'] = $this->common->saveImage($request['video_320'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);

                            $array = explode('.', $video['video_320']);
                            $video['video_extension'] = end($array);
                            $video['video_storage_type'] = $video_storage_type;
                        } else {
                            $video['video_320'] = "";
                        }
                        if ($request['video_480']) {

                            $video['video_storage_type'] = $video_storage_type;

                            if ($video_storage_type == 1) {
                                $video['video_480'] = $request['video_480'];
                            } else {
                                $video['video_480'] = $this->common->saveImage($request['video_480'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                        } else {
                            $video['video_480'] = "";
                        }
                        if ($request['video_720']) {

                            $video['video_storage_type'] = $video_storage_type;

                            if ($video_storage_type == 1) {
                                $video['video_720'] = $request['video_720'];
                            } else {
                                $video['video_720'] = $this->common->saveImage($request['video_720'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                        } else {
                            $video['video_720'] = "";
                        }
                        if ($request['video_1080']) {

                            $video['video_storage_type'] = $video_storage_type;

                            if ($video_storage_type == 1) {
                                $video['video_1080'] = $request['video_1080'];
                            } else {
                                $video['video_1080'] = $this->common->saveImage($request['video_1080'], $this->folder_content, 'vid_', $video_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);
                        } else {
                            $video['video_1080'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_480'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_720'], $request['old_video_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_1080'], $request['old_video_storage_type']);

                    $video['video_storage_type'] = $video_storage_type;
                    if ($request['video_url_320']) {

                        $array = explode('.', $request['video_url_320']);
                        $array1 = explode('?', end($array));
                        if (isset($array1) && $array1 != null) {
                            $video['video_extension'] = isset($array1) ? reset($array1) : "";
                        } else {
                            $video['video_extension'] = "";
                        }

                        $video['video_320'] = $request['video_url_320'];
                    }
                    $video['video_480'] = $request['video_url_480'] ?? '';
                    $video['video_720'] = $request['video_url_720'] ?? '';
                    $video['video_1080'] = $request['video_url_1080'] ?? '';
                }
                $video['video_duration'] = isset($request['video_duration']) ? Time_To_Milliseconds($request['video_duration']) : 0;

                // Trailer
                $trailer_storage_type = Storage_Type();
                $video['trailer_type'] = $request['trailer_type'] ?? '';
                if ($request['trailer_type'] == "server_video") {

                    if ($request['trailer_type'] == $request['old_trailer_type']) {

                        if ($request['trailer']) {

                            $video['trailer_storage_type'] = $trailer_storage_type;
                            if ($trailer_storage_type == 1) {
                                $video['trailer_url'] = $request['trailer'];
                            } else {
                                $video['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'vid_', $trailer_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        }
                    } else {
                        if ($request['trailer']) {

                            $video['trailer_storage_type'] = $trailer_storage_type;
                            if ($trailer_storage_type == 1) {
                                $video['trailer_url'] = $request['trailer'];
                            } else {
                                $video['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'vid_', $trailer_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        } else {
                            $video['trailer_url'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                    $video['trailer_storage_type'] = $trailer_storage_type;
                    $video['trailer_url'] = $request['trailer_url'] ?? '';
                }

                // Subtitle
                $subtitle_storage_type = Storage_Type();
                $video['subtitle_type'] = $request['subtitle_type'] ?? '';
                $video['subtitle_lang_1'] =  $request['subtitle_lang_1'] ?? '';
                $video['subtitle_lang_2'] =  $request['subtitle_lang_2'] ?? '';
                $video['subtitle_lang_3'] =  $request['subtitle_lang_3'] ?? '';
                if ($request['subtitle_type'] == "server_video") {

                    if ($request['subtitle_type'] == $request['old_subtitle_type']) {
                        if ($request['subtitle_1']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_1'] = $request['subtitle_1'];
                            } else {
                                $video['subtitle_1'] = $this->common->saveImage($request['subtitle_1'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                        }
                        if ($request['subtitle_2']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_2'] = $request['subtitle_2'];
                            } else {
                                $video['subtitle_2'] = $this->common->saveImage($request['subtitle_2'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                        }
                        if ($request['subtitle_3']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_3'] = $request['subtitle_3'];
                            } else {
                                $video['subtitle_3'] = $this->common->saveImage($request['subtitle_3'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);
                        }
                    } else {
                        if ($request['subtitle_1']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_1'] = $request['subtitle_1'];
                            } else {
                                $video['subtitle_1'] = $this->common->saveImage($request['subtitle_1'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                        } else {
                            $video['subtitle_1'] = "";
                        }
                        if ($request['subtitle_2']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_2'] = $request['subtitle_2'];
                            } else {
                                $video['subtitle_2'] = $this->common->saveImage($request['subtitle_2'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                        } else {
                            $video['subtitle_2'] = "";
                        }
                        if ($request['subtitle_3']) {

                            $video['subtitle_storage_type'] = $subtitle_storage_type;
                            if ($subtitle_storage_type == 1) {
                                $video['subtitle_3'] = $request['subtitle_3'];
                            } else {
                                $video['subtitle_3'] = $this->common->saveImage($request['subtitle_3'], $this->folder_content, 'vid_', $subtitle_storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);
                        } else {
                            $video['subtitle_3'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_1'], $request['old_subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_2'], $request['old_subtitle_storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $request['old_subtitle_3'], $request['old_subtitle_storage_type']);

                    $video['subtitle_storage_type'] = $subtitle_storage_type;
                    $video['subtitle_1'] = $request['subtitle_url_1'] ?? '';
                    $video['subtitle_2'] = $request['subtitle_url_2'] ?? '';
                    $video['subtitle_3'] = $request['subtitle_url_3'] ?? '';
                }

                $video['release_date'] = $request['release_date'] ?? '';
                $video['is_premium'] = $request['is_premium'];
                $video['is_title'] = $request['is_title'];
                $video['is_download'] = $request['video_upload_type'] == "server_video" ? $request['is_download'] : 0;
                $video['is_comment'] = $request['is_comment'];
                $video['is_like'] = $request['is_like'];
                $video['is_rent'] = $request['is_rent'];
                $video['price'] = $video['is_rent'] != 0 ? $request['price'] : 0;
                $video['rent_day'] = $video['is_rent'] != 0 ? $request['rent_day'] : 0;

                if ($video->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_video')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_video')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_video')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id, $type)
    {
        try {

            $video = Video::where('id', $id)->first();
            if ($video != null) {

                $this->common->deleteImageToFolder($this->folder_content, $video['thumbnail'], $video['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['landscape'], $video['storage_type']);

                $this->common->deleteImageToFolder($this->folder_content, $video['video_320'], $video['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['video_480'], $video['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['video_720'], $video['video_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['video_1080'], $video['video_storage_type']);

                $this->common->deleteImageToFolder($this->folder_content, $video['trailer_url'], $video['trailer_storage_type']);

                $this->common->deleteImageToFolder($this->folder_content, $video['subtitle_1'], $video['subtitle_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['subtitle_2'], $video['subtitle_storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $video['subtitle_3'], $video['subtitle_storage_type']);
                $video->delete();

                return redirect()->route('producer.video.index', ['type_id' => $type])->with('success', __('label.content_delete'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $data = Video::where('id', $request->id)->first();
            if ($data) {
                $data['status'] = $data['status'] === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'id' => $data->id, 'Status' => $data->status]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function videoReleases(Request $request)
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

            $video = Video::where('id', $request['id'])->first();
            if (isset($video['id'])) {

                $video['type_id'] = $request['type_id'];
                $video['video_type'] = $check_type['type'];
                $video['channel_id'] = $check_type?->type == 6 ? $request->channel_id : 0;

                if ($video->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.video_released')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_video')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_video')]);
            }
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

                $url = 'https://api.themoviedb.org/3/search/movie?api_key=' . $tmdb_api_key . '&language=en-US&page=1&include_adult=false&query=' . $tmdbTitle;
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

                $url = 'https://api.themoviedb.org/3/movie/' . $tmdbID . '?api_key=' . $tmdb_api_key . '&append_to_response=credits&language=en-US';
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
                if (isset($movies['title']) && $movies['title'] != null) {
                    $Title = $movies['title'];
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
                if (isset($movies['release_date']) && $movies['release_date'] != null) {
                    $Release_Date = $movies['release_date'];
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
