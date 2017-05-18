<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use App\Models\AddressGeojson;
use Illuminate\Console\Command;

class GetGeoJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Data';
    protected $config;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */



    public function handle()
    {
        $this->line(Carbon::now()->toDateTimeString());

        $data = file_get_contents(public_path().'/datajson/DiaphanHuyen.json');
        $features = json_decode($data,true);
        $features = $features['features'];

        foreach ($features as $feature) {

            $properties = $feature['properties'];
            $province = $properties['Ten_Tinh'];
            $district = $properties['Ten_Huyen'];

            $geometry = $feature['geometry'];
            $coordinates = $geometry['coordinates'][0];
            $coordinates = json_encode($coordinates);
            AddressGeojson::forceCreate([
                'province' => $province,
                'district' => $district,
                'coordinates' => $coordinates,
            ]);
        }

        $this->line(Carbon::now()->toDateTimeString());
    }

}
