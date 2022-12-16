<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Subscriber;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class SubscriptionController extends Controller
{
    function subscribeToSite(Request $request){
        try{
            // Validate request
            $validator = validator($request->post(), [
                'site' => 'required|exists:sites,id',
                'email' => 'required|email'
            ]);

            if($validator->fails()){
                return $this->json(false, null, $validator->errors()->all());
            }


            // We might run several db operations
            DB::beginTransaction();


            // Check if the email is already saved
            $subscriber = Subscriber::findOrCreate([
                'email' => $request->post('email')
            ]);


            // Get the site being subscribed to
            $site = Site::whereId($request->post('site'))->first();

            // Create an active subscription for the subscriber for the site
            $subscription = $site->subscribers()->attach($subscriber, [
                'status' => Subscription::STATUS_ACTIVE
            ]);

            DB::commit();

            return $this->json(true, Lang::get('app.subscription_successful'));

        }catch(Exception $e){
            DB::rollBack();

            return $this->json(false, Lang::get('app.server_error'));
        }

    }
}
