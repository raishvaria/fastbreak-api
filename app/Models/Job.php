<?php

namespace App\Models;

use App\Http\Requests\JobRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }

    public static function createFromRequest(JobRequest $request)
    {
        $filename = null;
        $picture = null;
        if ($request->picture) {
            $picture = self::getFileName($request->picture);
            $request->picture->move(base_path('public/uploads'), $filename);
            $filename = '/uploads/' . $filename;
        }

        $output = self::getCoordinates($request->pickup_address);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;

        $output = self::getCoordinates($request->address);
        $latitude2 = $output->results[0]->geometry->location->lat;
        $longitude2 = $output->results[0]->geometry->location->lng;

        $distance = self::haversineGreatCircleDistance($latitude, $longitude, $latitude2, $longitude2);
        $distance = round($distance);

        if ($distance <= 8) {
            $delivery_charges = 6;
        } else {
            $delivery_charges = $distance * 1.35;
        }

        $data = $request->validated();
        $data['picture'] = $filename;
        $data['user_id'] = $request->user()->id;
        $data['distance'] = $distance;
        $data['delivery_charges'] = $delivery_charges;

        return Job::create($data);
    }

    public static function updateFromRequest(JobRequest $request, Job $job)
    {
        $filename = null;
        $picture = null;
        if ($request->picture) {
            $picture = self::getFileName($request->picture);
            $request->picture->move(base_path('public/uploads'), $filename);
            $filename = '/uploads/' . $filename;
        }

        $output = self::getCoordinates($request->pickup_address);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;

        $output = self::getCoordinates($request->address);
        $latitude2 = $output->results[0]->geometry->location->lat;
        $longitude2 = $output->results[0]->geometry->location->lng;

        $distance = self::haversineGreatCircleDistance($latitude, $longitude, $latitude2, $longitude2);
        $distance = round($distance);

        if ($distance <= 8) {
            $delivery_charges = 6;
        } else {
            $delivery_charges = $distance * 1.35;
        }

        $data = $request->validated();
        $data['picture'] = $filename;
        $data['user_id'] = $request->user()->id;
        $data['distance'] = $distance;
        $data['delivery_charges'] = $delivery_charges;
        $job->update($data);

        return $job;
    }

    public static function getCoordinates($address)
    {
        $url = "https://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false&key=AIzaSyC7OwxnnzJL_9Bz--xGrJCfuHqrg18jyHM";
        $url = preg_replace("/ /", "%20", $url);
        $geocode = file_get_contents($url);
        $output = json_decode($geocode);
        return $output;
    }

    public static function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public static function getFileName($file)
    {
        return str_random(32) . '.' . $file->extension();
    }
}
