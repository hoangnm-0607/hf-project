<?php

namespace App\Security\Validator;


use App\Exception\JwtException;
use App\Service\CognitoValidatorService;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class CognitoTokenValidator
{

    private CognitoValidatorService $cognitoValidatorService;

    public function __construct(CognitoValidatorService $cognitoValidatorService)
    {
        $this->cognitoValidatorService = $cognitoValidatorService;
    }

    /**
     * @throws Exception
     */
    public function validateToken(string $token): ?array
    {
        $keys = json_decode($this->cognitoValidatorService->getUserPoolKeys(), true);
        try {
            return (array)JWT::decode($token, JWK::parseKeySet($keys), array('RS256')) ?? null;
        }
        catch (Exception $e) {
            throw new JwtException($e->getMessage());
        }
    }

}
