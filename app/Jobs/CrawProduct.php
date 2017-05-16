<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Product;
use App\Models\Category;
use App\Models\RelatedProduct;
use App\Models\Link;
use App\Models\Cronjob;
use Goutte\Client;
use App\Crawler\Functions;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Request;

class CrawProduct  implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $signature = 'crawProduct';
    protected $config;

    public function __construct()
    {
        $this->config = [
            'proxy' => [
                'http' => '125.212.225.51:4628'
            ]
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function crawlItem($url,$url_id = null)
    {

        try{
            if($url_id != null) {
                $link = Link::where('id',$url_id)->update(['status' => 1]);
            } else {
                $url = 'https://www.amazon.com'.$url;
            }

            $data = Link::find($url_id)->crawl_source()->get();

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

            if($asin == 'picassoRedirect.html') {
                $array_url = explode('%2F',$url);
                $asin_ = $array_url[2];
                $asin = explode('%3',$asin_);
                $dataCreate['asin'] = $asin[0];
            }

            if($title!=null){
                $count = Product::where('name',$title)->count();
            }

            if($count == 0){
                if(!empty($dataCreate) and $title != null)
                {
                    $dataCreate['amazone_url'] = $url;
                    $product = Product::create($dataCreate);
                    $productId = $product->id;

                    //save categories
                    $array_categories = explode('›',$product->categories);

                    for ($i = 0; $i < sizeof($array_categories) - 1; $i++) {

                        if($i == 0) {
                            $data = [
                                'name' => $array_categories[$i],
                                'parent_id' => '0'
                            ];
                            $category = Category::firstOrCreate($data);
                            $category_parent = $category->id;
                            if($category_parent == 0) {
                                $category = Category::where('name',$array_categories[$i])->first();
                                $category_parent = $category->id;
                            }
                        } else {
                            $category = Category::where('name',$array_categories[$i])->first();
                            $category_parent = $category->id;
                        }
                        $j = $i + 1;
                        $data_child = [
                            'name' => $array_categories[$j],
                            'parent_id' => $category_parent
                        ];
                        $category_child = Category::firstOrCreate($data_child);
                        $product_parent_id = $category_child->id;

                    }

                    //save categories, product_cid
                    Product::where('id',$productId)->update(['product_cid' => $product_parent_id]);

                    file_put_contents(storage_path().'/logs/crawProducts.log','Success with '. $title . ' - link'. $url . ' at ' . Carbon::now()->toDateTimeString() . PHP_EOL
                        ,FILE_APPEND | LOCK_EX);

                    // save related products
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
        $urls = Link::where('status','0')->get();

        try {
            foreach ($urls as $key => $value){
                $link = $value['link'];
                $id = $value['id'];
                $this->crawlItem($link,$id);
            }

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

            if($array_images){
                $dataCreate['image_details'] = json_encode($array_images);
            }

            if($crawler->filter('#prodDetails')->count()) {

                $informations = $crawler->filter('#prodDetails')->html();
            } else {
                $informations = null;
            }

            $dataCreate['informations'] = $informations;



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

            $array_url = explode('%2F',$url);
            $asin_ = $array_url[2];
            $asin = explode('%3',$asin_);
            $dataCreate['asin'] = $asin[0];

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
        try {
            file_put_contents(storage_path() . '/logs/crawProducts.log', "started at " . Carbon::now()->toDateTimeString() . PHP_EOL, FILE_APPEND | LOCK_EX);
            $cron_job = Cronjob::firstOrCreate([
                'name' => $this->signature,
                'status' => '1',
                'started_at' => Carbon::now()->toDateTimeString()
            ]);
            $this->crawlBestSeller();
            $cron_job_id = $cron_job->id;
            Cronjob::where('id', $cron_job_id)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
            file_put_contents(storage_path() . '/logs/crawProducts.log', "ended at " . Carbon::now()->toDateTimeString() . PHP_EOL, FILE_APPEND | LOCK_EX);
        } catch (\Exception $ex)
        {
            dd($ex->getMessage());
        }
    }
}
