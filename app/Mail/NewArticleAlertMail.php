<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewArticleAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Post */
    protected $post;

    /** @var Subscriber */
    protected $subscriber;

    public function __construct($post, $subscriber)
    {
        $this->post = $post;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->post->title)
            ->to($this->subscriber->email)
            ->view('emails.new_article_alert')
            ->with([
                'post' => $this->post,
                'subscriber' => $this->subscriber,
            ]);
    }
}
