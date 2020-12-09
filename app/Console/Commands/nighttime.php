<?php

namespace App\Console\Commands;

use App\Models\HotelRoom;
use App\Models\Tamagotchi;
use Illuminate\Console\Command;

class nighttime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nighttime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nighttime description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
        $this->tamagotchisFighting();
        return "Nighttime!!!";
    }
}
