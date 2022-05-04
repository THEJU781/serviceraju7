<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Slider;
use App\Models\User;
use App\Models\Setting;
use App\Models\AppSetting;
use App\Models\AppDownload;
use App\Models\ProviderType;
use App\Models\ProviderPayout;
use App\Models\PaymentGateway;
use App\Models\BookingRating;
use App\Models\HandymanRating;
use App\Models\HandymanType;
use App\Models\HandymanPayout;
use App\Models\BookingHandymanMapping;
use App\Models\ProviderServiceAddressMapping;
use App\Http\Resources\API\BookingResource;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\CategoryResource;
use App\Http\Resources\API\SliderResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\PaymentGatewayResource;
use App\Http\Resources\API\BookingRatingResource;
use App\Http\Resources\API\HandymanRatingResource;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardDetail(Request $request)
    {
        
        $per_page = 6;

        $slider = SliderResource::collection(Slider::where('status',1)->paginate($per_page));

        $category = CategoryResource::collection(Category::where('status',1)->where('is_featured',1)->orderBy('name','asc')->paginate($per_page));
        
        $service = Service::where('status',1);
        
        if ($request->has('city_id') && !empty($request->city_id)) {
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('city_id', $request->city_id);
            });
        }
        if(default_earning_type() === 'subscription'){
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1)->where('is_subscribe',1);
            });
        }else{
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }
        $service = $service->orderBy('id','desc')->paginate($per_page);
        
        $service = ServiceResource::collection($service);

        $provider = User::where('user_type','provider')->where('status',1)->where('is_subscribe',1);
            if ($request->has('city_id') && !empty($request->city_id)) {
                $provider = $provider->where('city_id', $request->city_id);
            }
        $provider = $provider->paginate($per_page);

        $provider = UserResource::collection($provider);

        $configurations = Setting::with('country')->get();

        $general_settings = AppSetting::first();
        $general_settings->site_logo = getSingleMedia(settingSession('get'),'site_logo',null);


        $paypal_configuration = false;        
        if ($request->has('latitude') && !empty($request->latitude) && $request->has('longitude') && !empty($request->longitude)) {
            $get_distance = getSettingKeyValue('DISTANCE','DISTANCE_RADIOUS');
            $get_unit = getSettingKeyValue('DISTANCE','DISTANCE_TYPE');
            
            $locations = Service::locationService($request->latitude,$request->longitude,$get_distance,$get_unit);
            $service_in_location = ProviderServiceAddressMapping::whereIn('provider_address_id',$locations)->get()->pluck('service_id');
            $service = Service::with('providerServiceAddress')->whereIn('id',$service_in_location)->get();
            $service = ServiceResource::collection($service);
        }
        $privacy_policy = Setting::where('type','privacy_policy')->where('key','privacy_policy')->first();
        
        $term_conditions = Setting::where('type','terms_condition')->where('key','terms_condition')->first();

        $payment_settings = PaymentGateway::where('status',1)->get();

        $payment_settings = PaymentGatewayResource::collection($payment_settings);

        $featured_service = Service::where('status',1)->where('is_featured',1);
        if(default_earning_type() === 'subscription'){
            $featured_service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1)->where('is_subscribe',1);
            });
        }else{
            $featured_service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }
        $featured_service = $featured_service->orderBy('id','desc')->paginate($per_page);
        $featured_service = ServiceResource::collection($featured_service);

        $discount_service = Service::where('discount','>',0);
        if(default_earning_type() === 'subscription'){
            $discount_service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1)->where('is_subscribe',1);
            });
        }else{
            $discount_service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }
        $discount_service = $discount_service->orderBy('discount','desc')->paginate($per_page);
      
        $discount_service = ServiceResource::collection($discount_service);

        $top_rated_service =BookingRatingResource::collection(BookingRating::orderBy('rating','desc')->limit(5)->get());

        $customer_review = null;

        $notification = 0;
        if($request->has('customer_id') && isset($request->customer_id)){
            $customer_review = BookingRating::with('customer','service')->where('customer_id',$request->customer_id)->get();
            if (!empty($customer_review))
            {
                $customer_review = BookingRatingResource::collection($customer_review);
            }
            $user = User::where('id',$request->customer_id)->first();
            $notification = count($user->unreadNotifications);
        }
        $response = [
           'status'         => true,
           'slider'         => $slider,
           'category'       => $category,
           'service'        => $service,
           'featured_service' => $featured_service,
           'provider'       => $provider,
           'configurations'  => $configurations,
           'generalsetting'  => $general_settings,
           'privacy_policy' => $privacy_policy,
           'term_conditions' => $term_conditions,
           'payment_settings' => $payment_settings,
           'customer_review' => $customer_review,
           'notification_unread_count' => $notification,
           'discount_service' => $discount_service,
           'top_rated_service' => $top_rated_service
        ];

        return comman_custom_response($response);
    }
    public function providerDashboard(Request $request){
        $provider = User::find(auth()->user()->id);
        $per_page = config('constant.PER_PAGE_LIMIT');
        $booking = Booking::myBooking();
        $total_booking = $booking->count();
        $service = Service::myService()->where('status',1);
        if ($request->has('city_id') && !empty($request->city_id)) {
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('city_id', $request->city_id);
            });
        }
        
        $service = $service->orderBy('id','desc')->paginate($per_page);
        $service = ServiceResource::collection($service);

        $category = CategoryResource::collection(Category::orderBy('name','asc')->paginate($per_page));
        
        $handyman = User::myUsers();
        $handyman = $handyman->paginate($per_page);

        $handyman = UserResource::collection($handyman);

      
        $providerEarning    = ProviderPayout::where('provider_id',$provider->id)->sum('amount') ?? 0;

        $revenuedata        = ProviderPayout::selectRaw('sum(amount) as total , DATE_FORMAT(created_at , "%m") as month' )
                                    ->whereYear('created_at',date('Y'))
                                    ->groupBy('month');
        $revenuedata= $revenuedata->get();
        $data['revenueData']    =    [];
        for($i = 1; $i <= 12; $i++ ){
            $revenueData = 0;
            foreach($revenuedata as $revenue){
                if((int)$revenue['month'] == $i){
                    
                    $data['revenueData'][] = [
                        $i => (int)$revenue['total']
                    ];
                    $revenueData++;
                }
            }
            if($revenueData == 0){
                $data['revenueData'][] = (object) [] ;
            }
        }

        $configurations = Setting::with('country')->get();
        $commission = ProviderType::where('id',$provider->providertype_id)->first();
        $notification = count($provider->unreadNotifications);
        $active_plan = get_user_active_plan($provider->id);
        if(is_any_plan_active($provider->id) == 0 && is_subscribed_user($provider->id) == 0 ){
            $active_plan = user_last_plan($provider->id);
        }
        $payment_settings = PaymentGatewayResource::collection(PaymentGateway::where('status',1)->get());

        $get_earning_type = default_earning_type();
        
        $response = [
            'status'         => true,
            'total_booking'  => $total_booking,
            'total_service'  => $service->count(),
            'total_handyman' => $handyman->count(),
            'service'        => $service,
            'category'       => $category,
            'handyman'       => $handyman,
            'total_revenue'  => $providerEarning,
            'monthly_revenue'=> $data,
            'configurations' => $configurations,
            'commission'     => $commission,
            'notification_unread_count' => $notification,
            'subscription'  => $active_plan,
            'is_subscribed' => is_subscribed_user($provider->id),
            'payment_settings' => $payment_settings,
            'earning_type' => $get_earning_type
         ];
 
         return comman_custom_response($response);
         
    }
    public function handymanDashboard(Request $request){
        $handyman = User::find(auth()->user()->id);
        $per_page = config('constant.PER_PAGE_LIMIT');
        $booking =  BookingHandymanMapping::with('bookings')->where('handyman_id',auth()->user()->id)->get();
        $upcomming = BookingHandymanMapping::with('bookings')->whereHas('bookings', function($bookings){
            $bookings->where('status','accept');
        })->orderBy('id','DESC')->get();
        $today_booking =  BookingHandymanMapping::with('bookings')->whereHas('bookings', function($bookings){
            $bookings->whereDate('date', Carbon::today());
        })->get();
        $handyman_rating = HandymanRating::where('handyman_id',auth()->user()->id)->orderBy('id','desc')->paginate(10);
        $handyman_rating = HandymanRatingResource::collection($handyman_rating);
        $commission = HandymanType::where('id',$handyman->handymantype_id)->first();
        $handymanEarning    = HandymanPayout::where('handyman_id',auth()->user()->id)->sum('amount') ?? 0;

        $revenuedata         = HandymanPayout::selectRaw('sum(amount) as total , DATE_FORMAT(created_at , "%m") as month' )
                                    ->whereYear('created_at',date('Y'))
                                    ->groupBy('month');
        $revenuedata= $revenuedata->get();
        $data['revenueData']    =    [];
        for($i = 1; $i <= 12; $i++ ){
            $revenueData = 0;
            foreach($revenuedata as $revenue){
                if((int)$revenue['month'] == $i){
                    
                    $data['revenueData'][] = [
                        $i => (int)$revenue['total']
                    ];
                    $revenueData++;
                }
            }
            if($revenueData == 0){
                $data['revenueData'][] = (object) [] ;
            }
        }

        $notification = count($handyman->unreadNotifications);
        $configurations = Setting::with('country')->get();
        $payment_settings = PaymentGatewayResource::collection(PaymentGateway::where('status',1)->get());
        $response = [
            'status'                        => true,
            'total_booking'                 => $booking->count(),
            'upcomming_booking'             => $upcomming->count(),
            'today_booking'                 => $today_booking->count(),
            'commission'                    => $commission,
            'handyman_reviews'              => $handyman_rating,
            'total_revenue'                 => $handymanEarning,
            'monthly_revenue'               => $data,
            'notification_unread_count'     => $notification,
            'configurations'                => $configurations,
            'payment_settings'              => $payment_settings
         ];
 
         return comman_custom_response($response);

    }
}