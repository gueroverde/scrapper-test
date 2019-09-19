<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Product',
        'description' => 'A type',
        'model' => Product::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id of product',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'description of product',
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'price of product',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'url to image',
            ],
            'shop' => [
                'type' => GraphQL::type('shop'),
                'description' => 'shop when product is scrapper',
            ],

        ];
    }
}
