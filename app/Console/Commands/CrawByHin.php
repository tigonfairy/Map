<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\RelatedProduct;
use App\Models\Link;
use App\Models\Category;
use App\Models\Cronjob;
use Illuminate\Console\Command;
use Goutte\Client;
use DB;
use App\Crawler\Functions;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CrawByHin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $config;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = [
            'proxy' => [
                'http' => '125.212.225.51:4628'
            ]
        ];

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function crawlItem($url,$url_id = null)
    {
        try{
            if($url_id != null) {
                Link::where('id',$url_id)->update(['status' => 1]);
            } else {
                $url = 'https://www.amazon.com'.$url;
            }

            $client = new Client($this->config);
            $crawler = $client->request('GET', $url);
            $dataCreate = [];
            $array_images = [];
            $count = 0;
            $title= null;
            $product_parent_id = 0;
            if($crawler->filter('#productTitle')->count())
            {
                $title = Functions::removeSpecialChar($crawler->filter('#productTitle')->text());
                $dataCreate['name'] = $title;
            }

            if($crawler->filter('#priceblock_ourprice')->count())
            {
                $price = substr(Functions::removeSpecialChar($crawler->filter('#priceblock_ourprice')->text()), 1);
                $dataCreate['price'] = floatval($price);
            }


            if($crawler->filter('#wayfinding-breadcrumbs_feature_div > ul')->count())
            {
                $categories = str_replace(' ','',Functions::removeSpecialChar($crawler->filter('#wayfinding-breadcrumbs_feature_div > ul')->text()));
            } else {
                $categories = null;
            }
            $dataCreate['categories'] = $categories;

            if($crawler->filter('#prodDetails')->count()) {

                $informations = $crawler->filter('#prodDetails')->html();
            } else {
                $informations = null;
            }

            $dataCreate['informations'] = $informations;

            foreach ($crawler->filter('#altImages > ul .a-declarative .a-button-text img') as $node){

                $domElement = new Crawler($node);
                $text = $domElement->attr('src');
                if($text!=''){
                    $array_images[] = str_replace('SS40','SL1200',$text);
                }
            }
            if($array_images){
                $dataCreate['image_details'] = json_encode($array_images);
            }

            if($crawler->filter('#landingImage')->count())
            {
                $images = $crawler->filter('#landingImage')->attr('data-old-hires');
            } else {
                $images = null;
            }
            $dataCreate['images'] = $images;

            if($crawler->filter('#productDescription > p')->count())
            {
                $descriptions = trim($crawler->filter('#productDescription > p')->text());
            } else {
                $descriptions = null;
            }
            $dataCreate['description'] = $descriptions;

            if($crawler->filter('.a-icon-star > span')->count())
            {
                $voting = trim($crawler->filter('.a-icon-star > span')->text());
            } else {
                $voting = null;
            }
            $dataCreate['voting'] = $voting;


            $array_url = explode('/',$url);
            $asin = $array_url[5];
            $dataCreate['asin'] = $asin;

            if($title!=null){
                $count = Product::where('name',$title)->count();
            }

            if($count == 0){
                if(!empty($dataCreate) and $title != null)
                {
                    $dataCreate['amazone_url'] = $url;
                    $product = Product::create($dataCreate);
                    $productId = $product->id;

                    $this->line('Success with '.$title);

                    file_put_contents(storage_path().'/logs/crawProducts.log','Success with '. $title . ' - link'. $url . ' at ' . Carbon::now()->toDateTimeString() . PHP_EOL
                        ,FILE_APPEND | LOCK_EX);

                    foreach ($crawler->filter('#sponsored-products-dp_feature_div .a-carousel-viewport ol li .sp_dpOffer > a') as $node){

                        $domElement = new Crawler($node);
                        $link = $domElement->attr('href');
                        if(isset($link)) {
                            $this->crawl_related($productId,$link);

                        }
                    }
                }
            } else {
                $product = Product::where('name',$title)->first();
                if(isset($product)){
                    $productId = $product->id;
                    foreach ($crawler->filter('#sponsored-products-dp_feature_div .a-carousel-viewport ol li .sp_dpOffer > a') as $node){

                        $domElement = new Crawler($node);
                        $link = $domElement->attr('href');
                        if(isset($link)) {

                            $this->crawl_related($productId,$link);

                        }
                    }
                }
            }

            foreach ($crawler->filter('#sponsored-products-dp_feature_div .a-carousel-viewport ol li .sp_dpOffer > a') as $node){
                    $domElement = new Crawler($node);
                    $link = $domElement->attr('href');
                    if(isset($link)) {
                        $this->crawlItem($link);
                    }
            }


        }catch (\Exception $ex){
             Log::info($ex->getMessage());
            Cronjob::where('name',$this->signature)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
            file_put_contents(storage_path().'/logs/crawProducts.log',$ex->getMessage() . PHP_EOL
                ,FILE_APPEND | LOCK_EX);
            file_put_contents(storage_path().'/logs/crawProducts.log',"ended at link: ".$url. " at " .Carbon::now()->toDateTimeString() . PHP_EOL,FILE_APPEND | LOCK_EX);
        }




    }

    public function crawlBestSeller()
    {
        $client = new Client($this->config);
        $urls = Link::where('status','0')->get();


        try {
            foreach ($urls as $key => $value){
                $link = $value['link'];
                $id = $value['id'];
                $this->crawlItem($link,$id);
            }
//            $crawler->filter('.a-link-normal')->each(function ($node) use ($client) {
//                $link = $node->attr('href');
//                $this->crawlItem($link);
//            });

        } catch (\Exception $ex)
        {
            Log::info($ex->getMessage());
            Cronjob::where('name',$this->signature)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
            file_put_contents(storage_path().'/logs/crawProducts.log',$ex->getMessage() . PHP_EOL
                ,FILE_APPEND | LOCK_EX);
            file_put_contents(storage_path().'/logs/crawProducts.log',"ended at ".Carbon::now()->toDateTimeString() . PHP_EOL,FILE_APPEND | LOCK_EX);


        }
    }

    public function crawl_related($productId,$url)
    {
        $url = 'https://www.amazon.com'.$url;
        try {

            $client = new Client($this->config);
            $crawler = $client->request('GET', $url);
            $dataCreate = [];
            $array_images=[];
            $count = 0;
            $title = null;
            if ($crawler->filter('#productTitle')->count()) {
                $title = Functions::removeSpecialChar($crawler->filter('#productTitle')->text());
                $dataCreate['name'] = $title;
            }

            if ($crawler->filter('#priceblock_ourprice')->count()) {
                $price = substr(Functions::removeSpecialChar($crawler->filter('#priceblock_ourprice')->text()), 1);
                $dataCreate['price'] = floatval($price);
            }

            if($crawler->filter('#wayfinding-breadcrumbs_feature_div > ul')->count())
            {
                $categories = str_replace(' ','',Functions::removeSpecialChar($crawler->filter('#wayfinding-breadcrumbs_feature_div > ul')->text()));
            } else {
                $categories = null;
            }
            $dataCreate['categories'] = $categories;

            foreach ($crawler->filter('#altImages > ul .a-declarative .a-button-text img') as $node){

                $domElement = new Crawler($node);
                $text = $domElement->attr('src');
                if($text!=''){
                    $array_images[] = str_replace('SS40','SL1200',$text);
                }
            }

            if($crawler->filter('#prodDetails')->count()) {

                $informations = $crawler->filter('#prodDetails')->html();
            } else {
                $informations = null;
            }

            $dataCreate['informations'] = $informations;

            if($array_images){
                $dataCreate['image_details'] = json_encode($array_images);
            }

            if ($crawler->filter('#landingImage')->count()) {
                $images = $crawler->filter('#landingImage')->attr('data-old-hires');
            } else {
                $images = null;
            }
            $dataCreate['images'] = $images;


            if ($crawler->filter('#productDescription > p')->count()) {
                $descriptions = trim($crawler->filter('#productDescription > p')->text());
            } else {
                $descriptions = null;
            }
            $dataCreate['description'] = $descriptions;

            if ($crawler->filter('.a-icon-star > span')->count()) {
                $voting = trim($crawler->filter('.a-icon-star > span')->text());
            } else {
                $voting = null;
            }
            $dataCreate['voting'] = $voting;

            if ($title != null) {
                $count = Product::where('name', $title)->count();
            }

            if ($count == 0) {
                if (!empty($dataCreate) and $title != null) {
                    $dataCreate['amazone_url'] = $url;
                    $product = Product::create($dataCreate);
                    file_put_contents(storage_path().'/logs/crawProducts.log','Success with '. $title . ' - link'. $url . ' at ' . Carbon::now()->toDateTimeString() . PHP_EOL
                        ,FILE_APPEND | LOCK_EX);
                    $data_related = [
                        'product_id' => $productId,
                        'related_product_id' => $product->id
                    ];
                    RelatedProduct::firstOrCreate($data_related);
                    $this->line('Success with ' . $title);
                }
            }
        } catch (\Exception $ex) {
             Log::info($ex->getMessage());
            Cronjob::where('name',$this->signature)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
            file_put_contents(storage_path().'/logs/crawProducts.log',$ex->getMessage() . PHP_EOL
                ,FILE_APPEND | LOCK_EX);
            file_put_contents(storage_path().'/logs/crawProducts.log',"ended at link ". $url. ' at ' .Carbon::now()->toDateTimeString() . PHP_EOL,FILE_APPEND | LOCK_EX);
        }
    }


    public function handle()
    {
        $this->line(Carbon::now()->toDateTimeString());
        file_put_contents(storage_path().'/logs/crawProducts.log',"started at ".Carbon::now()->toDateTimeString() . PHP_EOL,FILE_APPEND | LOCK_EX);
        $cron_job = Cronjob::create([
            'name' => $this->signature,
            'status' => '1',
            'started_at' => Carbon::now()->toDateTimeString()
        ]);
        $this->crawlBestSeller();
        $cron_job_id = $cron_job->id;
        Cronjob::where('id',$cron_job_id)->update([
            'status' => '2',
            'ended_at' => Carbon::now()->toDateTimeString()
        ]);
        $this->line(Carbon::now()->toDateTimeString());
        file_put_contents(storage_path().'/logs/crawProducts.log',"ended at ".Carbon::now()->toDateTimeString() . PHP_EOL,FILE_APPEND | LOCK_EX);
    }

}






