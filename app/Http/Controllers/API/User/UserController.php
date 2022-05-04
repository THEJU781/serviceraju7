<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Http\Requests\UserRequest;
use Validator;
use Hash;
Use Auth;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\ServiceResource;
use Illuminate\Support\Facades\Password;
use App\Models\Booking;
use App\Models\ProviderDocument;
use App\Models\Documents;
use App\Models\HandymanRating;
use App\Models\ProviderSubscription;
use App\Http\Resources\API\HandymanRatingResource;
class UserController extends Controller
{

    public function register(UserRequest $request)
    {
        $input = $request->all();
                
        $password = $input['password'];
        $input['display_name'] = $input['first_name']." ".$input['last_name'];
        $input['user_type'] = isset($input['user_type']) ? $input['user_type'] : 'user';
        $input['password'] = Hash::make($password);

        if( in_array($input['user_type'],['handyman', 'provider']))
        {
            $input['status'] = isset($input['status']) ? $input['status']: 0;
        }
        $user = User::create($input);
        $user->assignRole($input['user_type']);
        $input['api_token'] = $user->createToken('auth_token')->plainTextToken;

        unset($input['password']);
        $message = trans('messages.save_form',['form' => $input['user_type'] ]);
        $user->api_token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'message' => $message,
            'data' => $user
        ];
        return comman_custom_response($response);
    }

    public function login()
    {      
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            
            $user = Auth::user();
            if(request('loginfrom') === 'vue-app'){
                if($user->user_type != 'user'){
                    $message = trans('auth.not_able_login');
                    return comman_message_response($message,400);
                }
            }
            if(request('player_id') != null){
                $user->player_id = request('player_id');
            }
            $user->save();
            
            $success = $user;
            $success['user_role'] = $user->getRoleNames();
            $success['api_token'] = $user->createToken('auth_token')->plainTextToken;
            $success['profile_image'] = getSingleMedia($user,'profile_image',null);
            $is_verify_provider = false;
            if($user->user_type == 'provider')
            {
                $is_verify_provider = verify_provider_document($user->id);
                $success['subscription'] = get_user_active_plan($user->id);

                if(is_any_plan_active($user->id) == 0 && $success['is_subscribe'] == 0 ){
                    $success['subscription'] = user_last_plan($user->id);
                }
                $success['is_subscribe'] = is_subscribed_user($user->id);

            }
            $success['is_verify_provider'] = (int) $is_verify_provider; 
            unset($success['media']);
            unset($user['roles']);

            return response()->json([ 'data' => $success ], 200 );
        }
        else{
            $message = trans('auth.failed');
            
            return comman_message_response($message,400);
        }
    }

    public function userList(Request $request)
    {
        $user_type = isset($request['user_type']) ? $request['user_type'] : 'handyman';

        $handyman_list = User::where('user_type',$user_type)->withTrashed();
        if($request->has('provider_id'))
        {
            $handyman_list = $handyman_list->where('provider_id',$request->provider_id);
        }
        if($request->has('city_id') && !empty($request->city_id))
        {
            $handyman_list = $handyman_list->where('city_id',$request->city_id);
        }
        if($request->has('status') && isset($request->status))
        {
            $handyman_list = $handyman_list->where('status',$request->status);
        }

        if($request->has('booking_id')){
            $booking_data = Booking::find($request->booking_id);

            $service_address = $booking_data->handymanByAddress;
            if($service_address != null)
            {
                $handyman_list = $handyman_list->where('service_address_id', $service_address->id);
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $handyman_list->count();
            }
        }

        $user_list = $handyman_list->paginate($per_page);

        $items = UserResource::collection($user_list);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }

    public function userDetail(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);
        $message = __('messages.detail');
        if(empty($user)){
            $message = __('messages.user_not_found');
            return comman_message_response($message,400);   
        }

        $service = [];
        if($user->user_type == 'provider')
        {
            $service = Service::where('provider_id',$id)->where('status',1)->orderBy('id','desc')->paginate(10);
            $service = ServiceResource::collection($service);
        }
        $user_detail = new UserResource($user);
        $handyman_rating = [];
        if($user->user_type == 'handyman'){
            $handyman_rating = HandymanRating::where('handyman_id',$id)->orderBy('id','desc')->paginate(10);
            $handyman_rating = HandymanRatingResource::collection($handyman_rating);
        }

        $response = [
            'data' => $user_detail,
            'service' => $service,
            'handyman_rating_review' => $handyman_rating
        ];
        return comman_custom_response($response);

    }

    public function changePassword(Request $request){
        $user = User::where('id',\Auth::user()->id)->first();

        if($user == "") {
            $message = __('messages.user_not_found');
            return comman_message_response($message,400);   
        }
           
        $hashedPassword = $user->password;

        $match = Hash::check($request->old_password, $hashedPassword);

        $same_exits = Hash::check($request->new_password, $hashedPassword);
        if ($match)
        {
            if($same_exits){
                $message = __('messages.old_new_pass_same');
                return comman_message_response($message,400);
            }

			$user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            
            $message = __('messages.password_change');
            return comman_message_response($message,200);
        }
        else
        {
            $message = __('messages.valid_password');
            return comman_message_response($message);
        }
    }

    public function updateProfile(Request $request)
    {   
        $user = \Auth::user();
        if($request->has('id') && !empty($request->id)){
            $user = User::where('id',$request->id)->first();
        }
        if($user == null){
            return comman_message_response(__('messages.no_record_found'),400);
        }

        $user->fill($request->all())->update();

        if(isset($request->profile_image) && $request->profile_image != null ) {
            $user->clearMediaCollection('profile_image');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user_data = User::find($user->id);
        
        $message = __('messages.updated');
        $user_data['profile_image'] = getSingleMedia($user_data,'profile_image',null);
        $user_data['user_role'] = $user->getRoleNames();
        unset($user_data['roles']);
        unset($user_data['media']);
        $response = [
            'data' => $user_data,
            'message' => $message
        ];
        return comman_custom_response( $response );
    }

    public function logout(Request $request){
        $user = Auth::user();

        if($request->is('api*')){
            $user->player_id = null;
            $user->save();
            return comman_message_response('Logout successfully');
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => __($response), 'status' => true], 200)
            : response()->json(['message' => __($response), 'status' => false], 400);
    }
    
    public function socialLogin(Request $request)
    {
        $input = $request->all();

        $user_data = User::where('email',$input['email'])->first();

        if($input['login_type'] === 'mobile'){
            $user_data = User::where('username',$input['username'])->where('login_type','mobile')->first();
        }
           
        
        if( $user_data != null ) {
            if( !isset($user_data->login_type) || $user_data->login_type  == '' ){
                if($request->login_type === 'google'){
                    $message = __('validation.unique',['attribute' => 'email' ]);
                } else {
                    $message = __('validation.unique',['attribute' => 'username' ]);
                }
                return comman_message_response($message,400);
            }
            $message = __('messages.login_success');
        } else {

            if($request->login_type === 'google')
            {
                $key = 'email';
                $value = $request->email;
            } else {
                $key = 'username';
                $value = $request->username;
            }
            
            $trashed_user_data = User::where($key,$value)->whereNotNull('login_type')->withTrashed()->first();
            
            if ($trashed_user_data != null && $trashed_user_data->trashed())
            {
                if($request->login_type === 'google'){
                    $message = __('validation.unique',['attribute' => 'email' ]);
                } else {
                    $message = __('validation.unique',['attribute' => 'username' ]);
                }
                return comman_message_response($message,400);
            }

            if($request->login_type === 'mobile' && $user_data == null ){
                $otp_response = [
                    'status' => true,
                    'is_user_exist' => false
                ];
                return comman_custom_response($otp_response);
            }

            $password = !empty($input['accessToken']) ? $input['accessToken'] : $input['email'];
            
            $input['user_type']  = "user";
            $input['display_name'] = $input['first_name']." ".$input['last_name'];
            $input['password'] = Hash::make($password);
            $input['user_type'] = isset($input['user_type']) ? $input['user_type'] : 'user';
            $user = User::create($input);
            $user->assignRole($input['user_type']);
    
            $user_data = User::where('id',$user->id)->first();
            $message = trans('messages.save_form',['form' => $input['user_type'] ]);
        }
        $user_data['api_token'] = $user_data->createToken('auth_token')->plainTextToken;
        $user_data['profile_image'] = getSingleMedia($user_data,'profile_image',null);
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $user_data
        ];
        return comman_custom_response($response);
    }

    public function userStatusUpdate(Request $request)
    {
        $user_id =  $request->id;
        $user = User::where('id',$user_id)->first();

        if($user == "") {
            $message = __('messages.user_not_found');
            return comman_message_response($message,400);
        }
        $user->status = $request->status;
        $user->save();

        $message = __('messages.update_form',['form' => __('messages.status') ]);
        $response = [
            'data' => new UserResource($user),
            'message' => $message
        ];
        return comman_custom_response($response);
    }
    public function contactUs(Request $request){
        \Mail::send('contactus.contact_email',
        array(
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'phone_no' => $request->get('phone_no'),
            'user_message' => $request->get('user_message'),
        ), function($message) use ($request)
        {
            $message->from($request->email);
            $message->to(env('MAIL_FROM_ADDRESS'));
        });
        return comman_message_response('Thank you for contact us!');
    }
}
