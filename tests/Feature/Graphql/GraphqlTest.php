<?php
namespace Tests\Feature\Graphql;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GraphqlTest extends TestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function getAllShops()
    {
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

    /** @test  */
    public function getSpecificShop()
    {
        $linio = Shop::where('name', 'Linio')->first();

        $response = $this->graphql("{
  shops(id: {$linio->id}){
    id
    name
  }
}");
        $this->assertEquals('Linio', $response->json('data.shops.0.name'));
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
        $this->assertEquals($product->description, $response->json('data.products.0.description'));
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