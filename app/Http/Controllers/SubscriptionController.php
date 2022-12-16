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
                'site_id' => 'required|exists:sites,id',
                'email' => 'required|email'
            ]);

            if($validator->fails()){
                return $this->json(false, null, $validator->errors()->all());
            }


            // We might run several db operations
            DB::beginTransaction();


            // If email had been submitted before for subscription to any site,
            // there will be an existing record
            // Otherwise, we'll create it
            $subscriber = Subscriber::firstOrCreate([
                'email' => $request->post('email')
            ]);


            // Get the site being subscribed to
            $site = Site::whereId($request->post('site_id'))->first();


            // Get any existing record for the subscription to the site by the subscriber
            $existing_subscriber = $site->subscribers()
                ->where('subscribers.id', $subscriber->id)
                ->first();

            
            if(!$existing_subscriber){
                // This is a first subscription to that particular site
                // Create an active subscription record for the subscriber for the site
                $site->subscribers()->attach($subscriber, [
                    'status' => Subscription::STATUS_ACTIVE
                ]);
            }else{
                // User has subscribed to that particular site before
                // The subscription will either be active or not

                // Check if the subscription is active
                if($existing_subscriber->pivot->status == Subscription::STATUS_ACTIVE){
                    // Nothing we can do
                    return $this->json(false, Lang::get('app.already_subscribed'));
                }else{
                    // Reactivate the subscription
                    $site->subscribers()->updateExistingPivot($subscriber->id, [
                        'status' => Subscription::STATUS_ACTIVE
                    ]);
                }
            }

            // Done
            DB::commit();

            return $this->json(true, Lang::get('app.subscription_successful'));

        }catch(Exception $e){
            DB::rollBack();

            return $this->json(false, Lang::get('app.server_error'));
        }

    }
}
