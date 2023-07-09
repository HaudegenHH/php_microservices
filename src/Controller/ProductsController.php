<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController 
{
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id): Response 
    {
        if($request->headers->has('force_fail')) {
            return new JsonResponse(
              ["error" => "Promotions Engine failure message"],
              $request->headers->get('force_fail')
            );
        }
        
        return new JsonResponse([
            "quantity" => 5,
            "request_location" => "UK",
            "voucher_code" => "OU812",
            "request_date" => "2023-09-07",
            "product_id" => $id
        ], 200);
    }  
}