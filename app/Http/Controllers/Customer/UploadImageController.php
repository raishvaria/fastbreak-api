<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $filename = NULL;

        if ($request->picture) {
            $image_parts = explode(";base64,", $request->picture);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = base_path('public/uploads/mobile_api') . '/' . str_random(32) . '. ' . $image_type;

            file_put_contents($filename, $image_base64);
        }

        return response()->json(['file_name' => $filename]);
    }
}
