<?php

namespace Modules\UserManagementSystem\Notifications;

use App\Enums\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels, InteractsWithQueue;


    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    private $resetPasswordEmailUrl;
    private $domain;
    private $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $domain = null)
    {
        $this->queue = QueuesNames::EMAILS;
        $this->token = $token;
        $this->domain = $domain;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetPasswordUrl = $this->resetPasswordUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->resetPasswordEmailUrl);
        }

        return (new MailMessage)
            ->markdown('usermanagementsystem::mails.reset_password_email', [
                'name'            => $notifiable->name,
                'resetPasswordUrl' => $resetPasswordUrl,
            ])
            ->subject(__('usermanagementsystem/emails.reset_password_subject'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetPasswordUrl($notifiable)
    {
        $id  = $notifiable->getKey();
        $token = $this->token;
        $urlParams = "token=$token&id=$id";

        if ($this->domain) {
            $resetPasswordEmailUrl = config('app.dashboard_http_protocol') . $this->domain . '.' . config('app.dashboard_base_url') . '/' . config('app.dashboard_reset_password_route') . '?' . $urlParams;
        } else {
            $resetPasswordEmailUrl = config('app.dashboard_http_protocol') . config('app.dashboard_base_url') . '/' . config('app.dashboard_reset_password_route') . '?' . $urlParams;
        }

        return $resetPasswordEmailUrl;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
