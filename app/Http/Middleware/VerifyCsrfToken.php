<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'stripe/webhook',
        'stripe/*',
        'checkout/*',
        'https://billing.stripe.com/p/session/*',
        'https://billing.stripe.com/*',
        'https://checkout.stripe.com/*',
    ];
}
