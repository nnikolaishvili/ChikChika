<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeeklyReport;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendWeeklyReportNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users should get information about the amount of users, X - followed, Y - following from the previous week';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::withCount(['followers' => function (Builder $builder) {
            $builder->where('follower_user.created_at', '>', now()->subWeek());
        }, 'followings' => function(Builder $builder) {
            $builder->where('follower_user.created_at', '>', now()->subWeek());
        }])->verified()->get();

        foreach ($users as $user) {
            $user->notify(new WeeklyReport($user->followers_count, $user->followings_count));
        }
    }
}
