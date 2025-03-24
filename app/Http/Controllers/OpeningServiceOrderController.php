<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


use App\Models\OpeningServiceOrder;
use App\Models\Unit;

//use App\Http\Controllers\Controller;



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

    public function addOpeningServiceOrderFile(Request $request) {
        $array = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:jpg,png'
        ]);
        if (!$validator->fails()) {
            $file = $request->file('photo')->store('public');
            $file = explode('public/', $file);
            $array['photo'] = $file[1];
        } else {
            $array['error'] = $validator->errors()->first();
        }
        
        return $array;
    }
    
    public function setOpeningServiceOrder(Request $request) {
        $array = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'property' => 'required'
        ]);
        if (!$validator->fails()) {
            $title = $request->input('title');
            $property = $request->input('property');
            $list = $request->input('list');
            $newOpeningServiceOrder = new OpeningServiceOrder();
            $newOpeningServiceOrder->id_unit = $property;
            $newOpeningServiceOrder->title = $title;
            $newOpeningServiceOrder->status = 'IN_REVIEW';
            $newOpeningServiceOrder->datecreated = date('Y-m-d');
            $newOpeningServiceOrder->save();
            if ($list && is_array($list)) {
                foreach ($list as $listItem) {
                    $newOpeningServiceOrderItem = new OpeningServiceOrderItem();
                    $newOpeningServiceOrderItem->id_opening_service_order = $newOpeningServiceOrder->id;
                    $newOpeningServiceOrderItem->description = $listItem['description'];
                    $newOpeningServiceOrderItem->status = 'IN_REVIEW';
                    $newOpeningServiceOrderItem->save();
                    if (isset($listItem['photos']) && is_array($listItem['photos'])) {
                        $photos = [];
                        foreach ($listItem['photos'] as $photo) {
                            $url = explode('storage/', $photo);
                            $photos[] = $url[1];
                        }
                        $newOpeningServiceOrderItem->photos = implode(',', $photos);
                        $newOpeningServiceOrderItem->save();
                    }
                }
            }
        } else {
            $array['error'] = $validator->errors()->first();
        }
        return $array;
    }

}
