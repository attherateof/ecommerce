<?php 

declare(strict_types=1);

namespace App\GraphQL\Mutations\Admin\User;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Contracts\User\Authentication\LoginInterface;

final readonly class Login
{
    public function __construct(private readonly LoginInterface $login) {
        
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
        
        return $this->login
            ->setGuard($guard)
            ->setArguments($args)
            ->setRequest($context->request())
            ->authenticate();
    }
}
