<?php

namespace Tests\Feature\Graphql;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GraphqlTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function getAllShops()
    {
        factory(Shop::class)->create(['name'=>'Linio']);

        $response = $this->graphql("{
  shops{
    id
    name
  }
}");
        $this->assertCount(1, $response->json('data.shops'));
    }

    /** @test */
    public function getAllProduct()
    {
        $product = factory(Product::class, 10)->create();

        $response = $this->graphql('{
  products{
    id
  }
}');
        $this->assertCount(10, $response->json('data.products'));
    }

    /** @test */
    public function getSpecificShop()
    {
        $shopName="Tienda 1";
        factory(Shop::class)->create(['name'=>$shopName]);
        $shop = Shop::where('name', $shopName)->first();

        $response = $this->graphql("{
  shops(id: {$shop->id}){
    id
    name
  }
}");
        $this->assertEquals($shopName, $response->json('data.shops.0.name'));
    }

    /** @test */
    public function getSpecificProduct()
    {
        $product = factory(Product::class)->create();

        $response = $this->graphql("{
          products(id: {$product->id}){
            id,
            description
          }
        }");
        $this->assertArrayHasKey($product->description, $response->toArray('data.products.0.description'));
    }

    /**
     * @param string $query
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function graphql(string $query)
    {
        return $this->post('/graphql', [
            'query' => $query
        ]);
    }

}
