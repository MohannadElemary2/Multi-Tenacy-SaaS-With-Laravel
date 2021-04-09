<?php

namespace App\Traits;

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\Notification;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\Log;

trait PushNotifications
{
    /**
     * @param  $users
     * @param  $notificationTitle
     * @param  $notificationBody
     * @param array $data
     * @author Mohannad Elemary
     */
    public function sendNotificationToTokens($users, $notificationTitle, $notificationBody, $data = [])
    {
        try {
            list($enTokens) = $this->getUsersTokens($users);
            if ($enTokens) {
                dispatch(
                    new SendNotificationJob(
                        $enTokens,
                        $notificationTitle,
                        $notificationBody,
                        $data
                    )
                );
            }
        } catch (\Exception $exception) {
            // Log::warning('push notification error ' . $exception->getMessage());
        }
    }

    /**
     * @param $users
     * @return array[]
     * @author Mohannad Elemary
     */
    public function getUsersTokens($users)
    {
        $enTokens = [];

        foreach ($users as $user) {
            foreach ($user->firebaseTokens as $token) {
                if ($token->lang == 'en') {
                    $enTokens[] = $token->token;
                }
            }
        }
        return array($enTokens);
    }

    /**
     * @param  $pushTokens
     * @param  $title
     * @param  $body
     * @param  $data
     * @return array
     * @throws FirebaseException
     * @throws MessagingException
     * @author Mohannad Elemary
     */
    public function sendUsersNotification($pushTokens, $title, $body, $data)
    {
        $deletedTokens = [];

        foreach (array_chunk($pushTokens, 500, true) as $tokens) {
            $invalidTokens = $this->sendNotification($tokens, $title, $body, $data);
            if ($invalidTokens) {
                $deletedTokens = array_merge($deletedTokens, $invalidTokens);
            }
        }

        return $deletedTokens;
    }

    /**
     * Send push notification using firebase
     *
     * @param  $tokens
     * @param  $title
     * @param  $body
     * @param  $data
     * @return array|string[]
     * @throws FirebaseException
     * @throws MessagingException
     * @author Mohannad Elemary
     */
    public function sendNotification($tokens, $title, $body, $data)
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body));

        if ($data) {
            $message = $message->withData($data);
        }

        $result = app(Messaging::class)->sendMulticast($message, $tokens);

        $deletedTokens = [];

        if ($result->hasFailures()) {
            $deletedTokens = $result->failures()->invalidTokens();
            // Log::warning('invalid tokens ' . json_encode($result->failures()->invalidTokens()));
        }


        // Log::info('success tokens ' . json_encode($result->successes()->map(static function ($report) {
        //     return $report->target()->value();
        // })));

        return $deletedTokens;
    }
}
