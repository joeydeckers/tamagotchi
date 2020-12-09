<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\HotelRoom;
use App\Models\Tamagotchi;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        //todo checks maken

        $tamagotchi_ids = $request['tamagotchi_ids'];
        $tamagotchi_ids_filterd = explode(",", $tamagotchi_ids);
        $hotel_room = HotelRoom::findOrFail($request['hotel_room_id']);

        $count = count($tamagotchi_ids_filterd);

        if($hotel_room['size'] < $count || $hotel_room['tamagotchi_count'] == $hotel_room['size']){
            return response()->json([
                'error' => 'Room is full!'
            ], 400);
        }

        foreach ($tamagotchi_ids_filterd as $tamagotchi_id){
            $tamagotchi = Tamagotchi::findOrFail($tamagotchi_id);

            $hotel_room->tamagotchi_count++;
            $hotel_room->save();
            $tamagotchi->hotel_room_id = $hotel_room['id'];
            $tamagotchi->in_hotel = 1;
            $tamagotchi->save();

             Booking::create([
                'room_id' => $hotel_room['id'],
                'tamagotchi_id' => $tamagotchi['id'],
            ]);

            if($hotel_room['tamagotchi_count'] == $hotel_room['size']){
                $hotel_room->booked = 1;
                $hotel_room->save();
            }
        }
        $this->nightTime();
        $this->tamagotchisFighting();
        return response()->json([
            'message' => 'Booking created successfully!'
        ], 200);
    }

    public function nightTime(){
        $tamagotchis = Tamagotchi::all();
        foreach($tamagotchis as $tamagotchi){
            $this->specificNightTime($tamagotchi);
            if($tamagotchi['in_hotel']){
                $tamagotchi->level++;
                if($tamagotchi->boredom >= 70){
                    $tamagotchi->health = $tamagotchi->health -20;
                }
                if($tamagotchi->health <= 0){
                    $tamagotchi->dead = 1;
                }
                $tamagotchi->save();
            }else{
                $tamagotchi->health = $tamagotchi->health -20;
                $tamagotchi->boredom = $tamagotchi->boredom + 20;
                if($tamagotchi->health <= 0){
                    $tamagotchi->dead = 1;
                }
                $tamagotchi->save();
            }
        }
    }

    public function specificNightTime(Tamagotchi $tamagotchi){
        $tamagotchiRoom = HotelRoom::find($tamagotchi['hotel_room_id']);

        switch($tamagotchiRoom['type']){
            case 'relax':
                $tamagotchi["coins"] = $tamagotchi["coins"] -10;
                $tamagotchi["health"] = $tamagotchi["health"] +20;
                $tamagotchi["boredom"] = $tamagotchi["boredom"] -10;
                break;
            case 'game':
                $tamagotchi["coins"] = $tamagotchi["coins"] -20;
                $tamagotchi["boredom"] = 0;
                break;
            case 'working':
                $tamagotchi["coins"] = $tamagotchi["coins"] + random_int(10, 60);
                $tamagotchi["boredom"] = $tamagotchi["boredom"] +20;
                break;
        }
        $tamagotchi->save();
    }

    public function tamagotchisFighting(){
        $allFightingRooms = HotelRoom::where('type', 'fighting')->get();
        $winnerId = 0;

        foreach ($allFightingRooms as $fightingRoom){
            $tamagotchisInRoom = Tamagotchi::where('hotel_room_id', $fightingRoom['id']);
            if($tamagotchisInRoom->count() >= 2){
                $winner = $tamagotchisInRoom->inRandomOrder()->first();
                $winnerId = $winner['id'];
                $winner['coins'] = $winner["coins"] + 20;
                $winner['level'] = $winner["level"] + 1;
                $winner->save();

                $losers = Tamagotchi::where('id', '!=', $winner["id"])->get();

                foreach ($losers as $loser){
                    $loser['coins'] = $loser["coins"] - 20;
                    $loser['health'] = $loser["health"] - 30;
                    $loser->save();
                }
            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
