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

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels, InteractsWithQueue;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    private $clientEmailUrl;
    private $domain;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($domain = null)
    {
        $this->queue = QueuesNames::EMAILS;
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
        $clientUrl = $this->clientUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->clientEmailUrl);
        }

        return (new MailMessage)
            ->markdown('usermanagementsystem::mails.password_changed_email', [
                'name'            => $notifiable->name,
                'clientUrl' => $clientUrl
            ])
            ->subject(__('usermanagementsystem/emails.password_changed_subject'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function clientUrl($notifiable)
    {
        if ($this->domain) {
            $clientEmailUrl = config('app.dashboard_http_protocol') . $this->domain . '.' . config('app.dashboard_base_url');
        } else {
            $clientEmailUrl = config('app.dashboard_http_protocol') . config('app.dashboard_base_url');
        }

        return $clientEmailUrl;
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
