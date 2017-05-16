<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\RelatedProduct;
use App\Models\Cronjob;
use Illuminate\Console\Command;
use Goutte\Client;
use DB;
use App\Crawler\Functions;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Link;
use App\Models\Category;
class CrawHin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craw:hin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Craw Hin';
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

    public function crawlItem($url)
    {

        $url = 'https://www.amazon.com'.$url;
        try{

            $client = new Client();
            $crawler = $client->request('GET', $url);
            $dataCreate = [];
            $count = 1;
            $title= null;
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

//            $asin = trim($crawler->filter('#detail-bullets > table > tbody > tr > td > div > ul > li:nth-child(2)')->text());
//            dd($asin);
//            $dataCreate['asin'] = $asin;
            if($title!=null){
                $count = Product::where('name',$title)->count();
            }

            if($count == 0){
                if(!empty($dataCreate))
                {

                    $dataCreate['amazone_url'] = $url;
                    $product = Product::create($dataCreate);
                    $this->line('Success with '.$title);
                }

                foreach ($crawler->filter('#sponsored-products-dp_feature_div .a-carousel-viewport ol li .sp_dpOffer > a') as $node){

                    $domElement = new Crawler($node);
                    $link = $domElement->attr('href');
                    $url_related = 'https://www.amazon.com'.$link;
                    if(isset($link)) {
                        $data_related = [
                            'product_id' => $product->id,
                            'url_related_product' => $url_related
                        ];
                        RelatedProduct::firstOrCreate($data_related);
                        $this->crawlItem($link);
                    }
                }
            }

        }catch (\Exception $ex){
           // Log::info($ex->getMessage());
            dd($ex->getMessage(). $ex->getTraceAsString());
        }

    }


    public function getProductCategory($url){
        $count = 0;
        for($i = 1;$i<=5;$i++){
            try{
                $client = new Client($this->config);
                $crawler = $client->request('GET', $url.'#'.$i);
                $crawler->filter('#zg_centerListWrapper div.zg_itemWrapper > div > a')->each(function ($node) use ($client,$count) {
                    $link = $node->attr('href');
                    $this->line( 'https://www.amazon.com'.$link);
                    $link_model = Link::firstOrCreate(['link' =>  'https://www.amazon.com'.$link]);
                });
            }catch (\Exception $ex){
                Log::info($ex->getTraceAsString());
                Cronjob::where('name',$this->signature)->update([
                    'status' => '2',
                    'ended_at' => Carbon::now()->toDateTimeString()
                ]);
            }
        }

    }

    public function getSubCategory($url){
        //get sub category
        try{
            $client = new Client($this->config);
            $crawler = $client->request('GET', $url);
            $crawler->filter('#zg_browseRoot > ul > ul > li > a')->each(function ($node) use ($client) {
                $link = $node->attr('href');
                $this->getProductCategory($link);
            });
        }catch (\Exception $ex){
            Log::info($ex->getTraceAsString());
            Cronjob::where('name',$this->signature)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    }


    public function crawlBestSeller($url = 'https://www.amazon.com/gp/bestsellers/')
    {
        $client = new Client($this->config);
        $crawler = $client->request('GET', $url);
        try {
            //get Category
            $crawler->filter('#zg_browseRoot > ul > li > a')->each(function ($node) use ($client) {
                $link = $node->attr('href');
                $this->getSubCategory($link);
            });
        } catch (\Exception $ex)
        {
            Log::info($ex->getMessage());
            Cronjob::where('name',$this->signature)->update([
                'status' => '2',
                'ended_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    }


    public function handle()
    {
        $this->line(Carbon::now()->toDateTimeString());
        $this->crawlBestSeller();
        $this->line(Carbon::now()->toDateTimeString());
    }

}
