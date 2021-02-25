<?php

return [
    /*
     *  Attributes validation if this setting is `true`
     */
    'enabled' => true,

    /**
     * Middleware that will add validation rules by route.
     */
    'middleware' => \Bakome\ValidationAttributes\Routing\Middleware\ValidationRulesByAttributes::class,

    /**
     * Api pattern to return json response.
     */
    'api_pattern' => 'api/*',
];
