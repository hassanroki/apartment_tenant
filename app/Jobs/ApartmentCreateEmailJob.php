<?php

namespace App\Jobs;

use App\Mail\ApartmentCreateMail;
use App\Models\Apartment;
use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ApartmentCreateEmailJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;
    public Apartment $apartment;

    /**
     * Create a new job instance.
     */
    public function __construct(Apartment $apartment)
    {
        //
        $this->apartment = $apartment;
    }

    /**
     * Execute the job.
     */
    // Faster Way
    // public function handle(): void
    // {
    //     // How to do
    //     $tenants = Tenant::all();
    //     foreach ($tenants as $tenant) {

    //         try {
    //             Mail::to($tenant->email)->send(new ApartmentCreateMail($this->apartment));
    //         } catch (Throwable $e) {
    //             Log::error("Failed to send mail to {$tenant->email}: " . $e->getMessage());
    //         }
    //     }
    // }

    // Test so, slow way
    public function handle(): void
    {
        $delay = 0;

        Tenant::chunk(10, function ($tenants) use (&$delay) {
            foreach ($tenants as $tenant) {
                Mail::to($tenant->email)
                    ->later(now()->addSeconds($delay), new ApartmentCreateMail($this->apartment));

                $delay += 20; // increment globally
            }
        });
    }
}
