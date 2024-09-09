<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use App\Listeners\CustomSendEmailVerificationNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomSendEmailVerificationNotification::class, \App\Listeners\CustomSendEmailVerificationNotification::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->striped()
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });
    }
}
