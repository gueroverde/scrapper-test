<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;
use Symfony\Component\DomCrawler\Crawler;

class ScrapperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:scrapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @throws Exception
     */
    public function handle()
    {
        try {
            /** @var Crawler $crawler */
            $crawler = GoutteFacade::request('GET', 'https://www.linio.com.mx/cm/solo-hoy-ofertas');
            $crawlerProduct = $crawler->filter('.catalogue-product');

            if ($crawlerProduct->count() < 10) {
                throw new Exception('do not exist enough Item or change linio structured html');
            }
            $linioShop=Shop::find(1);
            $crawlerProduct->slice(0, 10)->each(function (Crawler $node) use ($linioShop){
                $product = new Product();
                $product->name = $node->filter("meta[itemprop='name']")->attr('content');
                $product->description = $node->filter("meta[itemprop='name']")->attr('content');
                $product->image = $node->filter("meta[itemprop='image']")->attr('content');
                $product->price = $this->getPriceFormat($node->filter('.price-main-md')->text());
                $product->shop()->associate($linioShop);
                $product->save();
            });
            $this->info('successful');

            return 0;
        } catch (Exception $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return 1;
        }
    }

    public function getPriceFormat($priceText){
        $match = [];
        preg_match('/[+-]?([0-9]*[,]?[0-9]*[.])?[0-9]+/', $priceText, $match);
        return floatval(str_replace(',','',$match[0]));
    }
}
