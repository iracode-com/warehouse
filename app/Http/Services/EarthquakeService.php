<?php

namespace App\Http\Services;

use SimpleXMLElement;
use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Helper\Str;
use App\Models\Webgis\PastEarthquake;

class EarthquakeService
{
    public static function getAllEarthquakesFromGeophysic()
    {
        $response = Http::get(config('geophysic.earthquake_url'));
        $data = [];
        if($response->status() == 200 || $response->status() == '200'){
            $data_from_xml = (array)new SimpleXMLElement($response->body());
            $data = array_filter($data_from_xml['item'], function ($item) {
                return !isset($item->licence) || !$item->licence;
            });
            $data = array_map(function ($item) {
                return (array)$item;
            }, $data);
        }
        return $data;
    }
    public static function sendEarthquakeMessageToBale($data)
    {
        try {
            $Magnitude                   = $data['mag'];
            $Location                    = $data['reg1'];
            $LocalDateTime               = $data['date'];
            $Latitude                    = StringService::convertPersianNumbersToEnglish(trim(str_replace('N','',$data['lat']),' '));
            $Longitude                   = StringService::convertPersianNumbersToEnglish(trim(str_replace('E','',$data['long']),' '));
            $Depth                       = $data['dep'];
            $NearCity1                   = $data['reg1'];
            $Distanc1                    = $data['dis1'];
            $NearCity2                   = $data['reg2'];
            $Distanc2                    = $data['dis2'];
            $NearCity3                   = $data['reg3'];
            $Distanc3                    = $data['dis3'];

            $message = new Str();
            $message
                ->line('#️⃣#فوری')
                ->line('<icon>  زلزله ای به بزرگی <mag> ریشتر در عمق <dep> کیلومتری از سطح زمین در *<reg>* رخ داد.', 2)
                ->line('⏱ زمان رخ داد زلزله:')
                ->line('🗓 <jdate>')
                ->line('🗒 <date>', 2)
                ->line('🗺 موقعیت روی مرکز زلزله:')
                ->line('<addr>')
                ->replace('<mag>', Str::of($Magnitude)->convertNumber())
                ->replace('<dep>', Str::of($Depth)->convertNumber())
                ->replace('<reg>', Str::of($Location)->trim())
                ->replace('<jdate>', $LocalDateTime)
                ->replace('<date>', DateTimeService::convertGeophysicDatetimeToGregorian($LocalDateTime))
                ->replace('<addr>', Str::of($Longitude . ' و ' . $Latitude)->trim())
                ->tap(function ($message) use ($Magnitude) {
                    $reg = (float) Str::of($Magnitude)->convertNumber()->toString();

                    if ($reg >= 6.0) {
                        $message->replace('<icon>', '🔴');
                    } elseif ($reg >= 5.0) {
                        $message->replace('<icon>', '🟠');
                    } elseif ($reg >= 4.0) {
                        $message->replace('<icon>', '🟡');
                    } elseif ($reg >= 3.0) {
                        $message->replace('<icon>', '🟢');
                    } else {
                        $message->replace('<icon>', '🔵');
                    }
                })->append("\n \n", '📣 @ZelzeleNegar');

            $token = config('api.BALE_TOKEN');

            
            $bale_response = Http::post("https://tapi.bale.ai/bot{$token}/sendMessage", [
                'chat_id'      => '5808675490',
                'text'         => $message->toString(),
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => 'مشاهده در گوگل مپ', 'url' => 'https://maps.google.com/?q=' . utf8_encode($Latitude) . ',' . utf8_encode($Longitude)],
                        ]
                    ]
                ], JSON_UNESCAPED_UNICODE),
            ]);
            info('send_res',[$bale_response]);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }
    public static function syncEarthquakesWithDatabase($earthquake_id,$earthquake_data)
    {
        $found_earthquake = PastEarthquake::where('earthquake_id', $earthquake_id)->first();
        if (!$found_earthquake) {
            PastEarthquake::create([
                'earthquake_id' => $earthquake_id,
                'data' => $earthquake_data
            ]);
            // If not exists in database , return must be true to send message to bale
            return true;
        }
        // If exists in database , return must be false because message already sent
        return false;
    }
}
