<?php

namespace App\Console\Commands;

use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Goutte\Client;
use DB;
use App\Crawler\Functions;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerAmazone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:amazone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

    public function crawlBestSellerTest2($url = 'https://www.amazon.com/gp/bestsellers/')
    {
        $this->line('Started at: ' . Carbon::now()->toDateTimeString());
        $client = new Client();

        $crawler = $client->request('GET', $url);

        try {

            foreach ($crawler->filter('.a-link-normal') as $domElement) {

                $node = new Crawler($domElement);

                $post = $client->request('GET', $node->attr('href'));

                $dataCreate = [];

                if ($post->filter('#productTitle')->count()) {
                    $title = Functions::removeSpecialChar($post->filter('#productTitle')->text());
                    $dataCreate['name'] = $title;
                }

                if ($post->filter('#priceblock_ourprice')->count()) {
                    $price = substr(Functions::removeSpecialChar($post->filter('#priceblock_ourprice')->text()), 1);

                    if (!is_numeric($price)) {
                        $price = Functions::removeSpecialChar($post->filter('.buyingPrice')->text());
                        $price .= '.' . Functions::removeSpecialChar($post->filter('.priceToPayPadding')->text());
                    }

                    $dataCreate['price'] = $price;
                }

                if ($post->filter('.imgTagWrapper > img')->count()) {
                    $image = $post->filter('.imgTagWrapper > img')->attr('src');
                    $dataCreate['images'] = $image;
                }

                if ($post->filter('#productDescription')->count()) {
                    $description = Functions::removeSpecialChar($post->filter('#productDescription')->html());
                    $dataCreate['description'] = $description;
                }

                if (!empty($dataCreate)) {
                    Product::create($dataCreate);
                    $this->line('Success with ' . $dataCreate['name']);
                }

                for ($i = 1; $i < 5; $i++) {

                    $url = $post->filter('#anonCarousel1 > ol > li:nth-child('.$i.') > div > a')->first();

                    $this->crawlBestSellerTest2($url->attr('href'));
                }

            };

            $this->line('Ended at: ' . Carbon::now()->toDateTimeString());

        } catch (\Exception $ex) {
            // Functions::traceCrawler();
            $this->line($ex->getMessage());
        }


    }


    public function crawlBestSeller($url = 'https://www.amazon.com/gp/bestsellers/')
    {
        $this->line('Started at: ' . Carbon::now()->toDateTimeString());
        $client = new Client();

        $crawler = $client->request('GET', $url);

        try {

            $crawler->filter('.a-link-normal')->each(function ($node) use ($client) {

                $link = $node->link();

                $post = $client->click($link);

                $dataCreate = [];

                if ($post->filter('#productTitle')->count()) {
                    $title = Functions::removeSpecialChar($post->filter('#productTitle')->text());
                    $dataCreate['name'] = $title;
                }

                if ($post->filter('#priceblock_ourprice')->count()) {
                    $price = substr(Functions::removeSpecialChar($post->filter('#priceblock_ourprice')->text()), 1);

                    if (!is_numeric($price)) {
                        $price = Functions::removeSpecialChar($post->filter('.buyingPrice')->text());
                        $price .= '.' . Functions::removeSpecialChar($post->filter('.priceToPayPadding')->text());
                    }

                    $dataCreate['price'] = $price;
                }

                if ($post->filter('.imgTagWrapper > img')->count()) {
                    $image = $post->filter('.imgTagWrapper > img')->attr('src');
                    $dataCreate['images'] = $image;
                }

                if ($post->filter('#productDescription')->count()) {
                    $description = Functions::removeSpecialChar($post->filter('#productDescription')->html());
                    $dataCreate['description'] = $description;
                }

                if (!empty($dataCreate)) {
                    Product::create($dataCreate);
                    $this->line('Success with ' . $dataCreate['name']);
                }

                $client2 = new Client();

                $crawler2 = $client2->request('GET', $node->attr('href'));

                $crawler2->filter('.a-link-normal')->each(function ($node2) use ($client2) {

                    dd($node2->attr('href'));

                    $this->crawlBestSeller($node2->attr('href'));
                });

            });


            $this->line('Ended at: ' . Carbon::now()->toDateTimeString());

        } catch (\Exception $ex) {
            // Functions::traceCrawler();
            dd($ex->getMessage());
        }


    }

    public function handle()
    {
        $this->crawlBestSellerTest2();
    }

}
