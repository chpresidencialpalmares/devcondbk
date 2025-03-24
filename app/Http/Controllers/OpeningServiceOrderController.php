<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\OpeningServiceOrder;
use App\Models\Unit;

class OpeningServiceOrderController extends Controller
{
    //
    public function getMyOpeningServiceOrders(Request $request) {
        $array = ['error' => ''];
        
        $property = $request->input('property');
        if ($property) {
            $user = auth()->user();
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
            /*
            $file = explode('public/', $file);
            $array['photo'] = $file[1];
            */
            $array['photo'] = asset(Storage::url($file));

        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        
        return $array;
    }
    
    public function setOpeningServiceOrder(Request $request) {
        $array = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'problemdescription' => 'required',
            'property' => 'required',
            'problemtype' => 'required',
            'sector' => 'required'
        ]);
        if (!$validator->fails()) {
            $problemdescription = $request->input('problemdescription');
            $property = $request->input('property');
            $list = $request->input('list');
            $problemtype = $request->input('problemtype');
            $sector = $request->input('sector');

            $newOpeningServiceOrder = new OpeningServiceOrder();

            $newOpeningServiceOrder->id_unit = $property;
            $newOpeningServiceOrder->problemdescription = $problemdescription;
            $newOpeningServiceOrder->status = 'IN_REVIEW';
            $newOpeningServiceOrder->datecreated = date('Y-m-d');

            $newOpeningServiceOrder->problemtype = $problemtype;
            $newOpeningServiceOrder->sector = $sector;


            //$newOpeningServiceOrder->save();
            if ($list && is_array($list)) {
                $photos = [];

                foreach ($list as $listItem) {
                    $url = explode('/', $listItem);
                    $photos[] = end($url);
                }
                $newOpeningServiceOrder->photos = implode(',', $photos);
            } else {
                $newOpeningServiceOrder->photos = '';
            }
            $newOpeningServiceOrder->save();
        
            /*
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
            */
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }

}
