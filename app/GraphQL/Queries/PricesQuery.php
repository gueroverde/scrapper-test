<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Price;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class PricesQuery extends Query
{
    protected $attributes = [
        'name' => 'prices',
        'description' => 'A query',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('price'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        if (isset($args['id'])) {
            return Price::where('id', $args['id'])->get();
        }
        return Price::with($with)->select($select)->get();
    }
}
