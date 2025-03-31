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

                //Verificar se não existe outra reserva no mesmo dia
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

    public function getDisabledDates($id)
    {
        $array = ['error' => '', 'list' => []];
    
        $area = RentalArea::find($id);
        $arealimitdate = $area->rentallimitdate; // Pegue o limite da área
    
        if ($area) {
            // Dias Disabled padrão
            $disabledDays = RentalDisableDay::where('id_rentalarea', $id)->get();
    
            foreach ($disabledDays as $disabledDay) {
                $start = $disabledDay->initialdate;
                $end = $disabledDay->finaldate;
    
                // Garantir que finaldate não seja maior que rentallimitdate
                if ($end > $arealimitdate) {
                    $end = $arealimitdate; // Ajusta finaldate para rentallimitdate
                }
    
                // Gerar o intervalo de datas entre initialdate e finaldate
                $dates = [];
    
                // Se a data final for maior que a data inicial
                if ($start <= $end) {
                    // Gera o intervalo de datas
                    $currentDate = \Carbon\Carbon::parse($start);
                    while ($currentDate <= \Carbon\Carbon::parse($end)) {
                        $dates[] = $currentDate->format('Y-m-d'); // Adiciona a data ao array
                        $currentDate->addDay(); // Adiciona um dia ao loop
                    }
                }
    
                // Adiciona as datas desabilitadas ao array
                $array['list'] = array_merge($array['list'], $dates);
            }
        }
    
        return $array;
    }
  
    public function getMyRentals(Request $request) {
        $array = ['error' => '', 'list' => []];

        $property = $request->input('property');

        if($property) {

            $unit = Unit::find($property);

            if($unit) {

                $rentals = Rental::where('id_unit', $property)
                    ->orderBy('dateevent','DESC')
                    ->get();

                    foreach($rentals as $rental) {
                        $area = RentalArea::find($rental['id_rentalarea']);

                        $daterev = date('d/m/Y', strtotime($rental['dateevent']));
                        //$afterTime = date('H:i', strtotime('+1 hour',strtotime($rental['dateevent'])));
                        //$daterev .= ' á '.$afterTime;

                        $array['list'][] = [
                            'id' => $rental['id'],
                            'id_area' => $rental['id_rentalarea'],
                            'title' => $area['title'],
                            'photos' => asset('storage/'.$area['photos']),
                            'datereserved' => $daterev,
                            'user' => $rental['user'], 
                            'status' => $rental['status']
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

