<?php

use App\Http\Controllers\Controller;
use onesignal\client\api\DefaultApi;
use onesignal\client\Configuration;
use onesignal\client\model\ExportPlayersRequestBody;
use onesignal\client\model\FilterExpressions;
use onesignal\client\model\GetNotificationRequestBody;
use onesignal\client\model\Notification;
use onesignal\client\model\Player;
use onesignal\client\model\Segment;
use onesignal\client\model\StringMap;
use onesignal\client\model\UpdatePlayerTagsRequestBody;

class NotificationController extends Controller
{
    public function sendNotificationToMobile($userId, $title, $message)
    {
        $APP_ID = '2fe3a6db-c004-424c-9b92-099391f7f88b';
        $APP_KEY_TOKEN = 'ZWM0MzQyYWEtMmJlMS00YzE4LWEyMDAtM2JjNmI1OTQ5YzU0';

        $config = Configuration::getDefaultConfiguration()
            ->setAppKeyToken($APP_KEY_TOKEN)->setAppId($APP_ID);

        $apiInstance = new DefaultApi(
            new GuzzleHttp\Client(),
            $config
        );
    }
}
