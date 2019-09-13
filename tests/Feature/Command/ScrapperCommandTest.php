<?php
namespace Tests\Feature\Command;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use Weidner\Goutte\GoutteFacade;

/**
 * Class ScrapperCommand
 * @package Tests\Feature\Command
 */
class ScrapperCommandTest extends TestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function save_crawled_data()
    {
        $crawler = new Crawler(file_get_contents(__DIR__ . '/Linio/Success.html'));
        GoutteFacade::shouldReceive('request')
            ->once()
            ->andReturn($crawler)
        ;

        $this->artisan('make:scrapper')
        ->expectsOutput('successful')
        ->assertExitCode(0)
        ;

        $this->assertEquals(10, Product::count());
    }

    /** @test */
    public function throw_exception_when_has_minus_of_10_item()
    {
        $crawler = new Crawler(file_get_contents(__DIR__ . '/Linio/Minus10.html'));
        GoutteFacade::shouldReceive('request')
            ->once()
            ->andReturn($crawler)
        ;

        $this->artisan('make:scrapper')
            ->expectsOutput('do not exist enough Item or change linio structured html')
            ->assertExitCode(1)
        ;

    }
}
