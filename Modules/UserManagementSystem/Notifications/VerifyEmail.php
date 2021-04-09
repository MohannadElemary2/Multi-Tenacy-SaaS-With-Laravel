<?php

namespace Modules\UserManagementSystem\Notifications;

use App\Enums\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels, InteractsWithQueue;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    private $verificationEmailUrl;
    private $domain;
    private $companyName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($domain, $companyName)
    {
        $this->queue = QueuesNames::EMAILS;
        $this->domain       = $domain;
        $this->companyName  = $companyName;
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
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->verificationEmailUrl);
        }

        $name = $this->companyName ?? $notifiable->name;

        return (new MailMessage)
            ->markdown('usermanagementsystem::mails.verifyemail', [
                'name'            => $name,
                'username'        => $notifiable->name,
                'email'           => $notifiable->email,
                'verificationUrl' => $verificationUrl,
            ])
            ->subject($name . __('usermanagementsystem/emails.verify_email_subject'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $data = [
            'id'   => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ];

        $this->verificationEmailUrl = URL::temporarySignedRoute(
            'client.domain.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            $data,
            false
        );

        $parsedUrl = parse_url($this->verificationEmailUrl);
        $urlParams = $parsedUrl['query'];

        if ($this->domain) {
            $verificationEmailUrl = config('app.dashboard_http_protocol') . $this->domain . '.' . config('app.dashboard_verify_email_link') . '/' .
                $data['id'] . '/' . $data['hash'] . '?' . $urlParams;
        } else {
            $verificationEmailUrl = config('app.dashboard_http_protocol') . config('app.dashboard_verify_email_link')  . '/' .
                $data['id'] . '/' . $data['hash'] . '?' . $urlParams;
        }

        return $verificationEmailUrl;
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
