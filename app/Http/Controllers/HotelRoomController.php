<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HotelRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return HotelRoom::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->guard('api')->user();

        if($user->isAdmin != 1){
            return response()->json([
                'error' => 'you are not admin!',
            ], 400);
        }

        $rules = [
            'size' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        return HotelRoom::create([
            'size' => $request['size'],
            'type' => $request['type'],
            'owner_id' => $user = auth()->guard('api')->user()->id,
        ], 200);
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

        $hotel_room = HotelRoom::find($id);

        if(is_null($hotel_room)){
            return response()->json([
                'error' => 'Room not found!'
            ], 400);
        }

        $user = auth()->guard('api')->user();

       if($user->isAdmin != 1){
           return response()->json([
               'error' => 'you are not admin!',
           ], 400);
       }

        if($hotel_room['owner_id'] != $user->id){
            return response()->json([
                'error' => 'you are not the owner',
            ], 400);
        }
        $hotel_room->update($request->all());
        return $hotel_room;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $user = auth()->guard('api')->user();

        if($user->isAdmin != 1){
            return response()->json([
                'error' => 'you are not admin!',
            ], 400);
        }

        $user = auth()->guard('api')->user();

        $hotel_room = HotelRoom::find($id);

        if(is_null($hotel_room)){
            return response()->json([
                'error' => 'Room not found!'
            ], 400);
        }

        if($hotel_room['owner_id'] != $user->id){
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
