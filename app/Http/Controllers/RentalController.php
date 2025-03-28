<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Area;
use App\Models\AreaDisabledDay;

use App\Models\RentalArea;
use App\Models\RentalDisableDay;
               

use App\Models\Rental;
use App\Models\Unit;

class RentalController extends Controller
{
    public function getRentals() {
        $array = ['error' => '', 'list' => []];
        //$daysHelper = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];

        $areas = RentalArea::where('allowed', 1)->get();

        foreach($areas as $area) {
            /* trecho de codigo para fazer texto que explica dias da semana disponiveis para locação
            $dayList = explode(',', $area['days']);

            $dayGroups = [];

            //Adicionando o primeiro dia
            $lastDay = intval(current($dayList));
            $dayGroups[] = $daysHelper[$lastDay];
            array_shift($dayList);
            
            //Adicionando dias relevantes
            foreach($dayList as $day) {

                if(intval($day) != $lastDay+1) {
                    $dayGroups[] = $daysHelper[$lastDay];
                    $dayGroups[] = $daysHelper[$lastDay];
                }

                $lastDay = intval($day);
            }
            
            //Adicionando o último dia
            $dayGroups[] = $daysHelper[end($dayList)];

            //Juntando as datas
            $dates = '';
            $close = 0;

            foreach($dayGroups as $group) {
                if($close === 0) {
                    $dates .= $group;
                }else {
                    $dates .= '-'.$group.',';
                }

                $close = 1 - $close;
            }  

            $dates = explode(',', $dates);
            array_pop($dates);

            // Adicionando o TIME
            $start = date('H:i', strtotime($area['start_time']));
            $end = date('H:i', strtotime($area['end_time']));

            foreach($dates as $dKey => $dValue) {
                $dates[$dKey] .= ' '.$start.' ás '.$end;
            } 
            */
            $array['list'][] = [
                'id' => $area['id'],
                'photos' => asset('storage/'.$area['photos']),
                'title' => $area['title'],
                'description' => $area['description'],
                'capacity' => $area['capacity'],
                'price' => $area['price'], 
                //'dates' => $dates
            ];

        }

        return $array;
    }

    public function setRental($id, Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            //'time' => 'required|date_format:H:i:s',
            'property' => 'required'
        ]);

        if(!$validator->fails()) {

            $date = $request->input('date');
            //$time = $request->input('time');
            $property = $request->input('property');

            $unit = Unit::find($property);
            $area = RentalArea::find($id);

            if($unit && $area) {

                $can = true;
                /*
                $weekday = date('w', strtotime($date));

                //Verificar se está dentro daa disponibilidade padrão
                $allowedDays = explode(',', $area['days']);

                if(!in_array($weekday, $allowedDays)) {
                    $can = false;
                } else {
                    $start = strtotime($area['start_time']);
                    $end = strtotime('-1 hour', strtotime($area['end_time']));
                    $revtime = strtotime($time);

                    if($revtime < $start || $revtime > $end) {
                        $can = false;
                    }
                }
                */
                //Verificar se está fora dos disabledDays
                /* 
                //original
                $existingDisabledDay = AreaDisabledDay::where('id_area', $id)
                    ->where('day', $date)
                    ->count();
                */
                //select count(*) as aggregate from `rentaldisableday` where  `id_rentalarea` = 1  and `initialdate` <= '2025-03-28'  and `finaldate` >= '2025-03-28'
                $existingDisabledDay = RentalDisableDay::where('id_rentalarea', $id)
                ->where('initialdate', '<=',  $date)
                ->where('finaldate', '>=',  $date)
                ->count();

                if($existingDisabledDay > 0) {
                    $can = false;
                }

                //Verificar se não existe outra reserva no mesmo dia/hora
                	
                /*
                //original
                $existingRentals = Rental::where('id_area', $id)
                    ->where('rental_date', $date.' '.$time)
                    ->count();

                if($existingRentals > 0) {
                    $can = false;
                }
                */

                $existingRentals = Rental::where('id_rentalarea', $id)
                    ->where('dateevent', $date)
                    ->count();

                if($existingRentals > 0) {
                    $can = false;
                }
                //$user = auth()->user();
                if($can) {
                    //id_unit	dateevent	id_rentalarea	status	datecreated	user
                    $newRental = new Rental();
                    $newRental->id_unit = $property;
                    $newRental->dateevent = $date;
                    $newRental->id_rentalarea = $id;
                    $newRental->status = 'reserved';
                    $newRental->datecreated = date('Y-m-d H:i:s');
                    $newRental->user = auth()->user();
                    
                    $newRental->save();

                } else {
                    $array['error'] = 'Reserva não permitida neste dia';
                    return $array;
                }

            }else {
                $array['error'] = 'Dados incorretos';
                return $array;
            }

        }else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    public function getDisabledDates($id) {
        $array = ['error' => '', 'list' => []];

        $area = RentalArea::find($id);

        if($area) {
        // Dias Disabled padrão
        $disabledDays = RentalDisableDay::where('id_rentalarea', $id)->get();

        foreach($disabledDays as $disabledDay) {
            $array['list'][] = $disabledDay['day'];
        }

        //Dias disabled atraves do allowed
        $allowedDays = explode(',', $area['days']);
        $offDays = [];

        for($q=0;$q<7;$q++) {
            if(!in_array($q, $allowedDays)) {
                $offDays[] = $q;
            }
            
        }

        // Listar os dias proibidos 3 mes para frente
        $start = time();
        $end = strtotime('+5 months');
        $current = $start;
        $keep = true;

        for(
            $current = $start;
            $current < $end;
            $current = strtotime('+1 day', $current)
        ) {
            $wd = date('w', $current);
            if(in_array($wd, $offDays)) {
                $array['list'][] = date('Y-m-d', $current);
            }
        }
        
        }else {
            $array['error'] = 'Area inexistente';
            return $array;
        }


        return $array;
    }
    /*   
    public function getTimes($id, Request $request) {
        $array = ['error' => '', 'list' => []];

        $validator = Validator::make($request->all(),[
            'date' => 'required|date_format:Y-m-d'
        ]);

        if(!$validator->fails()) {
            $date = $request->input('date');
            $area = RentalArea::find($id);

            if($area) {

                $can = true;

                // Verificar se é dia disabled
                $existingDisabledDay = AreaDisabledDay::where('id_area',$id)
                    ->where('day', $date )->count();
                
                if($existingDisabledDay > 0) {
                    $can = false;
                }

                //Verificar se é dia permitido
                $allowedDays = explode(',', $area['days']);
                $weekday = date('w', strtotime($date));

                if(!in_array($weekday, $allowedDays)) {
                    $can = false;
                }

                if($can) {
                    $start = strtotime($area['start_time']);
                    $end = strtotime($area['end_time']);
                    $times = [];

                    for(
                        $lastTime = $start;
                        $lastTime < $end;
                        $lastTime = strtotime('+1 hour', $lastTime)
                    ) {
                        $times[] = $lastTime;
                    }

                    $timeList = [];
                    foreach($times as $time) {
                        $timeList[] = [
                            'id' => date('H:i:s', $time),
                            'title' => date('H:i', $time). ' - '.date('H:i', strtotime('+1 hour', $time))
                        ];
                    }

                    //Removendo as reservas
                    $rentals = Rental::where('id_area', $id)
                        ->whereBetween('rental_date', [
                            $date.' 00:00:00',
                            $date.' 23:59:59'
                        ])
                        ->get();

                        $toRemove = [];

                        foreach($rentals as $rental) {
                            $time = date('H:i:s', strtotime($rental['rental_date']));
                            $toRemove[] = $time;
                        }

                        foreach($timeList as $timeItem) {
                            if(!in_array($timeItem['id'], $toRemove)) {
                                $array['list'][] = $timeItem;
                            }
                        }

                }

            }else {
                $array['error'] = 'Area inexistente';
                return $array;
            }
        }else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }
    */
    public function getMyRentals(Request $request) {
        $array = ['error' => '', 'list' => []];

        $property = $request->input('property');

        if($property) {

            $unit = Unit::find($property);

            if($unit) {

                $rentals = Rental::where('id_unit', $property)
                    ->orderBy('rental_date','DESC')
                    ->get();

                    foreach($rentals as $rental) {
                        $area = RentalArea::find($rental['id_area']);

                        $daterev = date('d/m/Y H:i', strtotime($rental['rental_date']));
                        $afterTime = date('H:i', strtotime('+1 hour',strtotime($rental['rental_date'])));
                        $daterev .= ' á '.$afterTime;

                        $array['list'][] = [
                            'id' => $rental['id'],
                            'id_area' => $rental['id_area'],
                            'title' => $area['title'],
                            'photos' => asset('storage/'.$area['photos']),
                            'datereserved' => $daterev
                        ];

                    }
            }else {
                $array['error'] = 'Propriedade inexistente';
                return $array;
            }

        }else {
            $array['error'] = 'Propriedade não enviada';
            return $array;
        }

        return $array;
    }

    public function delMyRental($id) {
        $array = ['error' => ''];

        $user = auth()->user();
        $rental = Rental::find($id);
        if($rental) {

            $unit = Unit::where('id', $rental['id_unit'])
                ->where('id_owner', $user['id'])
                ->count();
            
            if($unit > 0) {
                Rental::find($id)->delete();
            } else {
                $array['error'] = 'Esta reserva não é sua';
                return $array;
            }

        }else {
            $array['error'] = 'Reserva inexistente';
            return $array;
        }

        return $array;
    }
}


/*
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    //
}

*/