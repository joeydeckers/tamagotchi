<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelRoom;
use Illuminate\Support\Facades\DB;

class HotelRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function index($id)
    {
        return DB::table('hotel_rooms')->where('owner_id', $id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return HotelRoom::create([
            'size' => $request['size'],
            'type' => $request['type'],
            'owner_id' => $request['owner_id'],
        ], 200);
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        $hotel_room = HotelRoom::findOrFail($id);

        if($hotel_room['owner_id'] != $request['owner_id']){
            return response()->json([
                'error' => 'you are not the owner',
            ], 400);
        }
        $hotel_room->update($request->all());
        return $hotel_room;

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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(request $request, $id)
    {
        $hotel_room = HotelRoom::findOrFail($id);

        if($hotel_room['owner_id'] != $request['owner_id']){
            return response()->json([
                'error' => 'you are not the owner',
            ], 400);
        }

        $hotel_room->delete();

        return response()->json([
            'message' => 'Room deleted!',
        ], 200);
    }
}
