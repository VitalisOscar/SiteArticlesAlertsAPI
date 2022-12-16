<?php

namespace App\Console\Commands;

use App\Mail\NewArticleAlertMail;
use App\Models\Post;
use App\Models\Site;
use App\Models\Subscriber;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailsToSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:send_alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to a site\'s subscribers with a new post\'s info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Fetching sites...");

        // Get all sites
        $sites = Site::all();

        // for each saved site
        foreach($sites as $site){
            // Send the site's new posts to all subscribers
            $this->sendSitePostsToAllSubscribers($site);
        }

        $this->info("All done!");

        return 0;
    }

    /**
     * Send the new posts of a site to all the site's subscribers
     * @param Site $site
     */
    function sendSitePostsToAllSubscribers($site){
        $this->info("Fetching new posts for site: " . $site->name .'...');

        // Get new posts for the site
        $posts = $site->posts()->newlyPublished()->get();

        if(count($posts) > 0){
            // Get the site's active subscribers
            $subscribers = $site->subscribers()
                ->wherePivot('status', Subscription::STATUS_ACTIVE)
                ->get();


            // Go through each post
            foreach($posts as $post){
                $this->info("Preparing to alert subscribers about post: " . $post->title .'...');

                // Send to each subscriber
                foreach($subscribers as $subscriber){
                    $this->sendPostToSubscriber($subscriber, $post);
                }

                // Mark the post as sent
                $post->status = Post::STATUS_SENT;
                $post->save();

            } // Done for each post

        } else {
            $this->line("No new posts found for the site");
        }
    }

    /**
     * Send a post to a single subscriber
     * @param Subscriber $subscriber
     * @param Post $post
     */
    function sendPostToSubscriber($subscriber, $post){
        // First, ensure we are not sending the post more than once
        if($subscriber->alreadyReceivedPost($post)){
            return;
        }

        // Add the post to the list of the subscriber's received posts
        $subscriber->received_posts()->attach($post);

        // Send an email notification to the subscriber via queue
        Mail::queue(new NewArticleAlertMail($post, $subscriber));
    }
}
