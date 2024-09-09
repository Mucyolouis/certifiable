<?php

namespace App\Filament\Pages\Auth;

use App\Settings\MailSettings;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Password;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    protected static string $view = 'filament-panels::pages.auth.password-reset.request-password-reset';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->label('Email'),
            ]);
    }

    public function request(): void
    {
        try {
            $this->rateLimit(3);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();

        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $data,
            function (CanResetPassword $user, string $token): void {
                $settings = app(MailSettings::class);
                $settings->loadMailSettingsToConfig();

                $notification = new ResetPasswordNotification($token);
                $notification->createUrlUsing(function ($notifiable, string $token) {
                    return Filament::getResetPasswordUrl($token, $notifiable);
                });

                $user->notify($notification);
            },
        );

        if ($status === Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->success()
                ->send();

            $this->form->fill();
        } else {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();
        }
    }
}