<?php

namespace App\Controllers;

use Qup\Http\GenericResponse;
use Qup\Http\Get;
use Qup\Http\Status;
use Qup\Interfaces\Response;

final readonly class HomeController
{
    #[Get('/home')]
    public function index(): Response {
        return new GenericResponse(Status::HTTP_200, 'OK');
    }

    #[Get('/show/{name}')]
    public function show(string $name): Response {
        return new GenericResponse(Status::HTTP_200, $name);
    }
}