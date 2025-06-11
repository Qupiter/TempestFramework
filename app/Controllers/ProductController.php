<?php

namespace App\Controllers;

use App\Models\Product;
use Qup\Http\ErrorResponse;
use Qup\Http\GenericResponse;
use Qup\Http\Get;
use Qup\Http\Post;
use Qup\Http\Status;
use Qup\Interfaces\Response;

final readonly class ProductController
{
    #[Get('/products')]
    public function index(): Response {
        return new GenericResponse(Status::HTTP_200, 'Products list');
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Get('/product/{id}')]
    public function show(int $id): Response {
        return new GenericResponse(Status::HTTP_200, $id);
    }

    #[Post('/product')]
    public function create(Product $product): Response {
        if($product->getDiscount()) {
            return new ErrorResponse();
        }
        return new GenericResponse(Status::HTTP_200, $product->getName());
    }
}