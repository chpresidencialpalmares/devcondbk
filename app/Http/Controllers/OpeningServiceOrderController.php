<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\OpeningServiceOrder;
use App\Models\Unit;


class OpeningServiceOrderController extends Controller
{
    //
    public function getMyOpeningServiceOrders(Request $request) {
        $array = ['error' => 'Not implemented yet'];
        
        $property = $request->input('property');
        if ($property) {
            $unit = Unit::where('id', $property)->where('id_owner', $user['id'])->count();
            if ($unit > 0) {
                $openingServiceOrders = OpeningServiceOrder::where('id_unit', $property)
                    ->orderBy('datecreated', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get();
                foreach ($openingServiceOrders as $openingServiceOrderKey => $openingServiceOrderValue) {
                    $openingServiceOrders[$openingServiceOrderKey]['datecreated'] = date('d/m/Y', strtotime($openingServiceOrderValue['datecreated']));
                    $photoList = [];
                    $photos = explode(',', $openingServiceOrderValue['photos']);
                    foreach ($photos as $photo) {
                        if (!empty($photo)) {
                            $photoList[] = asset('storage/' . $photo);
                        }
                    }
                    $openingServiceOrders[$openingServiceOrderKey]['photos'] = $photoList;
                }
                $array['list'] = $openingServiceOrders;
            } else {
                $array['error'] = 'Esta unidade não é sua';
            }
        } else {
            $array['error'] = 'A propriedade é necessária';
        }
        return $array;

        
    }
}
