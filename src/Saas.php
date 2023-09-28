<?php

namespace Savannabits\Saas;

use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Saas
{
    public static function getSettingsNavLabel(): string
    {
        return "Settings";
    }

    /**
     * @throws Throwable
     */
    public static function updateExchangeRates(string $baseCurrency = 'KES'): void
    {
        DB::transaction(function () use ($baseCurrency) {
            $rates = Currency::rates()->latest()->base($baseCurrency)->get();
            foreach (Models\Currency::get() as $model) {
                $model->updateQuietly([
                    'exchange_base_currency' => $baseCurrency,
                    'exchange_rate' => floatval(collect($rates)->get($model->code) ?? 1.0),
                    'last_forex_update' => now()
                ]);
            }
        });
    }

    public static function exchangeCurrency(string $from, string $to='KES', float|int $amount = 1, bool $useAPI = true): float|int
    {
        if ($useAPI) {
            try {
                return Currency::convert()->from($from)->to($to)->amount($amount)->get();
            } catch (Throwable $e) {
                Log::error($e);
                Log::info("API fetching failed. Using fallback");
            }
        }
        $a = Models\Currency::whereCode($from)->first()?->exchange_rate ?: 1.0;
        $b = Models\Currency::whereCode($to)->first()?->exchange_rate ?: 1.0;
        return (floatval($b)/floatval($a)) * $amount;
    }
}
