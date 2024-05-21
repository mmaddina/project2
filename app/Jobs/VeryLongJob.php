<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;
use App\Models\Comment;
use App\Models\Article;



class VeryLongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $comment, public $article)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('mamedovamadinaa8@gmail.com')->send(new CommentMail($this->comment, $this->article));
    }
}
