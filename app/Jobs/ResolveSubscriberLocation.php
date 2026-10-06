<?php

namespace App\Jobs;

use App\Models\Suscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class ResolveSubscriberLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tiempo máximo (en segundos) para resolver la geolocalización.
     */
    public int $timeout = 30;

    /**
     * Crea una nueva instancia del job.
     *
     * @param  int  $subscriberId  Id del suscriptor a geolocalizar.
     */
    public function __construct(public int $subscriberId)
    {
    }

    /**
     * Resuelve la geolocalización del suscriptor a partir de su IP.
     */
    public function handle(): void
    {
        $subscriber = Suscriber::find($this->subscriberId);

        if (!$subscriber) {
            Log::warning('ResolveSubscriberLocation: no se encontró el suscriptor', [
                'subscriber_id' => $this->subscriberId,
            ]);
            return;
        }

        try {
            $position = Location::get($subscriber->ip);

            if ($position) {
                $subscriber->country = $position->countryName ?? $subscriber->country;
                $subscriber->city = $position->cityName ?? $subscriber->city;
                $subscriber->latitud = $position->latitude ?? $subscriber->latitud;
                $subscriber->longitude = $position->longitude ?? $subscriber->longitude;
                $subscriber->save();
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo resolver la geolocalización del suscriptor: ' . $e->getMessage(), [
                'subscriber_id' => $this->subscriberId,
            ]);
        }
    }
}
