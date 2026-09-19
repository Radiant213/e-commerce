<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || str_contains(config('app.url'), 'https://') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Use modern custom dropdowns across all Filament forms, filters, and tables
        \Filament\Forms\Components\Select::configureUsing(function (\Filament\Forms\Components\Select $select): void {
            $select->native(false);
        });

        \Filament\Tables\Filters\SelectFilter::configureUsing(function (\Filament\Tables\Filters\SelectFilter $filter): void {
            $filter->native(false);
        });

        \Filament\Tables\Columns\SelectColumn::configureUsing(function (\Filament\Tables\Columns\SelectColumn $column): void {
            $column->native(false);
        });
    }
}
