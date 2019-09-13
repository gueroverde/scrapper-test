<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Shop;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Weidner\Goutte\GoutteFacade;

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

            $crawlerProduct->slice(0,10)->each(function (Crawler $node) {
                $product = new Product();
                $product->name = $node->filter("meta[itemprop='name']")->attr('content');
                $product->description = $node->filter("meta[itemprop='name']")->attr('content');
                $product->image = $node->filter("meta[itemprop='image']")->attr('content');
                $product->price = (float) number_format((float) ltrim(trim($node->filter('.price-main')->text()), '$'),2);
                $product->shop()->associate(Shop::find(1));
                $product->save();

            });
            $this->info('something');
            return 0;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
            return 1;
        }
    }
}
