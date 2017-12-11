<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SaleAgent;
use App\Models\User;
use Illuminate\Console\Command;
use Hash;

class Hin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add admin';

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
        //update code saleagent
       SaleAgent::where('code','')->chunk(1,function($sales) {
               foreach ($sales as $sale) {
                  try{
                      $product = Product::find(intval($sale->product_id));
                      if($product and $product->level ==1 ) {
//                          dd($product->code);
                          $sale->code = $product->code;
                          $sale->save();

                          $this->line('Success:'. $sale->id);
                      }
                  }catch (\Exception $ex) {
                      $this->line($ex->getTraceAsString());
                      die;
                  }


               }


       });

    }
}
