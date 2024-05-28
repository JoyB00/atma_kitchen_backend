<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Http\Request;
use onesignal\client\api\DefaultApi;
use onesignal\client\Configuration;
use onesignal\client\model\Filter;
use onesignal\client\model\Notification;
use onesignal\client\model\StringMap;

class NotificationController extends Controller
{
    public function sendNotificationToMobile(Request $request)
    {
        $data = $request->all();
        $userId = $data['user_id'];
        $message = $data['message'];
        $title = $data['title'];

        $APP_ID = '2fe3a6db-c004-424c-9b92-099391f7f88b';
        $APP_KEY_TOKEN = 'ZWM0MzQyYWEtMmJlMS00YzE4LWEyMDAtM2JjNmI1OTQ5YzU0';

        $config = Configuration::getDefaultConfiguration()
            ->setAppKeyToken($APP_KEY_TOKEN);

        $apiInstance = new DefaultApi(
            new GuzzleHttp\Client(),
            $config
        );

        $content = new StringMap();
        $content->setEn($message);

        $notification = new Notification();
        $notification->setAppId($APP_ID);
        $notification->setContents($content);

        $userFilter = new Filter();
        $userFilter->setField('tag');
        $userFilter->setKey('user_id');
        $userFilter->setRelation('=');
        $userFilter->setValue($userId);
        $notification->setFilters([$userFilter]);

        return $apiInstance->createNotification($notification);
    }
}
