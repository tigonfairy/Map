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
       SaleAgent::chunk(500,function($sales) {
          foreach ($sales as $sale) {
              dd($sale);
              $product = Product::find($sale->product_id);
              $sale->code = $product->code;
              $sale->save();
          }
       });

    }
}
