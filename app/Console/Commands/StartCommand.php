<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatMail;
use App\Models\StatArticle;


class StartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $commentCount = Comment::whereDate('created_at', Carbon::today())->count();
       $articleCount = StatArticle::all()->count();
       StatArticle::whereNotNull('id')->delete();
       Mail::to('mamedovamadinaa8@gmail.com')->send(new StatMail($commentCount, $articleCount));
    }

}
