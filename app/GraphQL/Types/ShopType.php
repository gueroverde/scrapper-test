<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Shop;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ShopType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Shop',
        'description' => 'A type',
        'model' => Shop::class,
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id of shop',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'name of shop',
            ],
            'products' => [
                'type' => Type::listOf(GraphQL::type('product')),
                'description' => 'The shop products',
            ]
        ];
    }
}
