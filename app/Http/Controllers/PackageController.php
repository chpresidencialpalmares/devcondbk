<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Package;
use App\Models\Unit;

//use App\Http\Controllers\Controller;


class PackageController extends Controller
{
    //
    public function getMyPackages(Request $request) {
        $array = ['error' => ''];
        
        $property = $request->input('property');
        if ($property) {
            $user = auth()->user();
            $unit = Unit::where('id', $property)->where('id_owner', $user['id'])->count();
            if ($unit > 0) {
                $packages = Package::where('id_unit', $property)
                    ->orderBy('datecreated', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get();
                foreach ($packages as $packageKey => $packageValue) {
                    $packages[$packageKey]['datecreated'] = date('d/m/Y', strtotime($packageValue['datecreated']));
                    
                }
                $array['list'] = $packages;
            } else {
                $array['error'] = 'Esta unidade não é sua';
            }
        } else {
            $array['error'] = 'A propriedade é necessária';
        }
        return $array;
    }
}
