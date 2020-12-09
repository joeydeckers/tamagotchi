<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamagotchi;
use Illuminate\Support\Facades\Validator;

class TamagotchiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('api')->user();

        return Tamagotchi::where('owner_id', $user->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $rules = [
            'name' => 'required',
            'age' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        return Tamagotchi::create([
            'name' => $request['name'],
            'age' => $request['age'],
            'coins' => 100,
            'health' => 100,
            'boredom' => 0,
            'dead' => 0,
            'owner_id' => $user = auth()->guard('api')->user()->id,
            'level' => 1,
            'in_hotel' => 0
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function destroy($id)
    {
        $user = auth()->guard('api')->user();

        $tamagotchi = Tamagotchi::find($id);

        if(is_null($tamagotchi)){
            return response()->json([
                'error' => 'Tamagotchi not found!'
            ], 400);
        }

        if($tamagotchi['owner_id'] != $user->id){
            return response()->json([
                'error' => 'you are not the owner',
            ], 400);
        }

        $tamagotchi->delete();

        return response()->json([
            'message' => 'Tamagotchi deleted!',
        ], 200);
    }
}
