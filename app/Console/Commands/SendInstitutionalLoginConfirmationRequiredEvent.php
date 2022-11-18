<?php

namespace App\Console\Commands;

use App\Events\InstitutionalLoginConfirmationRequired;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Models\Activity;

class SendInstitutionalLoginConfirmationRequiredEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sgc:send-institutional-login-confirmation-required-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send institutional login confirmation required event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $yesterday = date('Y-m-d', strtotime('-1 days'));

        /**
         * @var Collection<int, Activity> $activities
         */
        $activities = Activity::where('subject_type', \App\Models\Employee::class)
            ->where('event', 'created')
            ->whereDate('created_at', '=', $yesterday)
            ->get();

        foreach ($activities as $item) {
            /**
             * @var Employee $employee
             */
            $employee = $item->subject;
            /**
             * @var User $user
             */
            $user = $item->causer;

            if ($employee !== null) {
                InstitutionalLoginConfirmationRequired::dispatch($user, $employee);
            }
        }

        return 0;
    }
}
