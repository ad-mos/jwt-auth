<?php

namespace Tymon\JWTAuth\Providers\JWT;

use Exception;
use JWT as Firebase;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class FirebaseAdapter extends JWTProvider implements JWTInterface
{
    /**
     * Create a JSON Web Token
     *
     * @return string
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function encode(array $payload)
    {
        try {
            return Firebase::encode($payload, $this->secret, $this->algo);
        } catch (Exception $e) {
            throw new JWTException('Could not create token: ' . $e->getMessage());
        }
    }

    /**
     * Decode a JSON Web Token
     *
     * @param  string  $token
     * @return array
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function decode($token)
    {
        try {
            return (array) Firebase::decode($token, $this->secret);
        } catch (Exception $e) {
            // firebase implementation doesn't return the payload if it has expired
            if ($e->getMessage() === 'Expired Token') {
                throw new TokenExpiredException('JWT has expired');
            } else {
                throw new JWTException('Could not decode token: ' . $e->getMessage());
            }
        }
    }
}
