<?php 

declare(strict_types=1);

namespace App\GraphQL\Mutations\Admin\User;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Contracts\User\Authentication\LogoutInterface;

final readonly class Logout
{
    public function __construct(private readonly LogoutInterface $logout) {
        
    }

    /**
     * Return a value for the field.
     *
     * @param  null  $root Always null, since this field has no parent.
     * @param  array{}  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed The result of resolving the field, matching what was promised in the schema
     */
    public function __invoke(null $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): mixed
    {
        $guard = config('sanctum.guard[0]', 'web');

        return $this->logout
            ->setGuard($guard)
            ->setRequest($context->request())
            ->logout();
    }
}
