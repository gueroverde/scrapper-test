<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Price;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;


class PriceType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Price',
        'description' => 'A type',
        'model' => Price::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id of price',
            ],
            'price' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'price of product',
            ],
            'msrp' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'msrp of product',
            ],
            'product' => [
                'type' => GraphQL::type('product'),
                'description' => 'shop when product is scrapper',
            ],
        ];
    }
}
