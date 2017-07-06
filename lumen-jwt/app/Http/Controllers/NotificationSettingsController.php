<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Repositories\NotificationSettingInterface;

class NotificationSettingsController extends Controller
{
    protected $notificationSettings;

    public function __construct(NotificationSettingInterface $notificationSettings)
    {
        $this->notificationSettings = $notificationSettings;
    }

    public function getNotificationSetting(int $type)
    {
        try {
            $player = Auth::user();
            if(! $player) {
                return ['code' => 400, 'message' => 'Error'];
            }

            $notificationSetting = $player->notificationSettings()->where('type', $type)->first();

            if(! $notificationSetting) {
                $notificationSetting = $this->notificationSettings->save(['player_id' => $player->id, 'type' => $type]);
            }

            $levelIds    = $notificationSetting->levels->pluck('id')->toArray();
            $districtIds = $notificationSetting->districts->pluck('id')->toArray();
            $positionIds = $notificationSetting->positions->pluck('id')->toArray();

            $results['levelIds']    = $levelIds;
            $results['districtIds'] = $districtIds;
            $results['positionIds'] = $positionIds;
            $results['cityId']      = $notificationSetting->city_id;

            return $results;
        } catch (Exception $e) {
            info('There is an exception is add finding Players');
            return;
        }

        return ['code' => 200, 'message' => 'Notification Setting is saved successfully!'];
    }
    public function saveNotificationSetting(Request $request)
    {
        try {

            $player = Auth::user();
            if(! $player) {
                return ['code' => 400, 'message' => 'Error'];
            }
            
            $type                = $request->input('type');
            $cityId              = $request->input('cityId');
            $notificationSetting = $player->notificationSettings()->where('type', $type)->first();

            if(! $notificationSetting) {
                $notificationSetting = $this->notificationSettings->save(['player_id' => $player->id, 'type' => $type]);
            }

            $notificationSetting = $this->notificationSettings->save(['id' => $notificationSetting->id, 'city_id' => $cityId]);

            $levelIds    = $request->input('levelIds');
            $districtIds = $request->input('districtIds');
            $positionIds = $request->input('positionIds');

            $notificationSetting->levels()->sync($levelIds);
            $notificationSetting->districts()->sync($districtIds);
            $notificationSetting->positions()->sync($positionIds);

        } catch (Exception $e) {
            info('There is an exception is add finding Players');
            return response()->json(['error'], $e->getStatusCode());
        }

        return ['code' => 200, 'message' => 'Notification Setting is saved successfully!'];
    }
}