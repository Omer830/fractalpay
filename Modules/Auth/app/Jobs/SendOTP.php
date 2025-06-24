<?php

namespace Modules\Auth\Jobs;

use Illuminate\Bus\Queueable;
use Modules\Auth\Models\User;
use Modules\Auth\Mail\SendOTPMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed|null
     */
    private mixed $email;
    /**
     * @var mixed|null
     */
    private mixed $phone;
    private mixed $OTP;
    private mixed $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $OTP, $email = null, $phone = null)
    {
        $this->user  = $user;
        $this->email = $email ?? null;
        $this->phone = $phone ?? null;
        $this->OTP   = $OTP;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Log::info('In Job Verify OTP');
        if($this->email) {
            Log::info('condition Passed');
            Mail::to($this->email)->send(new SendOTPMail($this->user, $this->OTP));
        }

        if($this->phone) {
            //Todo: SEND SMS OTP
        }


    }
}
