<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);


// Handle the incoming request
    $request = Request::createFromGlobals();

// Redirect the user to the login page if accessing the root URL
    if ($request->getPathInfo() === '/') {
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse('/auth');
        $response->send();
        exit();
    }

// Send the request to the kernel and return the response
    $response = $kernel->handle($request);
    $response->send();

    $kernel->terminate($request, $response);

};
