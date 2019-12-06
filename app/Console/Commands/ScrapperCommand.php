<?php

namespace App\Console\Commands;

use App\Models\Price;
use App\Models\Product;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Weidner\Goutte\GoutteFacade;

class ScrapperCommand extends Command
{
    const LINIO_SHOP_ID = 1;
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
            $shopId = self::LINIO_SHOP_ID;

            if ($crawlerProduct->count() < 10) {
                throw new Exception('do not exist enough Item or change linio structured html');
            }

            $crawlerProduct->slice(0, 10)->each(function (Crawler $node) use ($shopId) {
                $product = Product::updateOrCreate(
                    [
                        'name' => $node->filter("meta[itemprop='name']")->attr('content'),
                        'description' => $node->filter("meta[itemprop='name']")->attr('content'),
                        'image' => $node->filter("meta[itemprop='image']")->attr('content'),
                        'shop_id' => $shopId,
                    ]
                );
                $prices = new Price([
                        'price' => $this->getPriceFormat($node->filter('.price-main-md')->text()),
                        'msrp' => $this->getPriceFormat($node->filter('.original-price')->text()),
                    ]);

                $product->prices()->save($prices);
            });
            $this->info('successful');

            return 0;
        } catch (Exception $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return 1;
        }
    }

    public function getPriceFormat($priceText)
    {
        $match = [];
        preg_match('/[+-]?([0-9]*[,]?[0-9]*[.])?[0-9]+/', $priceText, $match);

        return floatval(str_replace(',', '', $match[0]));
    }
}
