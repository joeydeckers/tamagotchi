<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamagotchi;

class TamagotchiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tamagotchi::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Tamagotchi::create([
            'name' => $request['name'],
            'age' => $request['age'],
            'coins' => 100,
            'health' => 100,
            'boredom' => 0,
            'dead' => $request['dead'],
            'owner_id' => $request['owner_id'],
            'level' => 1,
            'in_hotel' => 0
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
     * @return string
     */
    public function destroy(Request $request, $id)
    {
        $tamagotchi = Tamagotchi::findOrFail($id);

        if($tamagotchi['owner_id'] != $request['owner_id']){
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
