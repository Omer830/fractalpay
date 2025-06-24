<?php

namespace Modules\Wallet\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Models\Schedule;
use Modules\Wallet\Enums\TransactionStatus;
use Modules\Wallet\Services\CommitmentsService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessSchedulePayments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'command:processSchedulePayments';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedules = Schedule::where('next_run_date', '<=', NOW())
                                ->where(function ($query) {
                                    $query->where('end_date', '>=', NOW())
                                        ->orWhereNull('end_date');
                                })
                                ->where('schedule_status', TransactionStatus::ACTIVE->value)
                                ->get();
        $index = 1;
        foreach ($schedules as $schedule) {
         
            CommitmentsService::processCommitment($schedule);
            
            $index++;
        }

    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
