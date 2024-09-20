<?php

namespace App\Controller;

use App\Service\Gateway;
use Braintree\Exception\NotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/payment', name: 'payment_')]
class PaymentController extends AbstractController
{
    function __construct(private Gateway $gateway)
    {
    }

    #[Route('/authorization', name: 'authorization', methods: 'GET')]
    public function authorization(): JsonResponse
    {
        $authorizationToken = $this->gateway->clientToken()->generate();

        return $this->json([
            'authorization' => $authorizationToken,
        ]);
    }

    #[Route('/subscribe', name: 'subscribe', methods: 'POST')]
    public function subscribe(Request $request, #[CurrentUser] $user): JsonResponse
    {
        if ($user === null) {
            return $this->json([
                'error' => 'You need to be authorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userId = $user->getId();

        $content = json_decode($request->getContent());

        $this->gateway->customer()->create([
            'id' => $userId,
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getSurname(),
        ]);

        $result = $this->gateway->paymentMethod()->create([
            'customerId' => $userId,
            'paymentMethodNonce' => $content->nonce,
            'options' => [
                'verifyCard' => true,
            ],
        ]);

        if (!$result->success) {
            return $this->json([
                'error' => $result,
            ], Response::HTTP_BAD_REQUEST);
        }

        $paymentMethod = $result->paymentMethod;

        $result = $this->gateway->subscription()->create([
            'planId' => $content->plan, // plan id from the request (eg: '5fc2')
            'paymentMethodToken' => $paymentMethod->token,
        ]);

        if (!$result->success) {
            return $this->json([
                'error' => $result,
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'subscription' => $result->subscription,
        ]);
    }

    #[Route('/plan/update', name: 'plan_updade', methods: 'PATCH')]
    public function updatePlan(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent());
        // request body example:
        // {
        //     "plan": "5fc2",
        //     "price": "12.00"
        // }
        $plan = $content->plan;
        $price = $content->price;

        try {
            $result = $this->gateway->plan()->update($plan, [
                'price' => $price,
            ]);

            if (!$result->success) {
                return $this->json([
                    'error' => $result,
                ], Response::HTTP_BAD_REQUEST);
            }

            return $this->json([
                'plan' => $result->plan,
            ]);
        } catch (NotFound $th) {
            return $this->json([
                'error' => 'The plan was not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
