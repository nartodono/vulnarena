<?php

/**
 * This file was automatically generated by k8s/api-generator 0.12.0 for API version v1.25.16
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Api\Model\Api\Authentication\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * TokenReviewSpec is a description of the token authentication request.
 */
class TokenReviewSpec
{
    /**
     * @Kubernetes\Attribute("audiences")
     * @var string[]|null
     */
    protected $audiences = null;

    /**
     * @Kubernetes\Attribute("token")
     * @var string|null
     */
    protected $token = null;

    /**
     * @param string[]|null $audiences
     * @param string|null $token
     */
    public function __construct(?array $audiences = null, ?string $token = null)
    {
        $this->audiences = $audiences;
        $this->token = $token;
    }

    /**
     * Audiences is a list of the identifiers that the resource server presented with the token identifies
     * as. Audience-aware token authenticators will verify that the token was intended for at least one of
     * the audiences in this list. If no audiences are provided, the audience will default to the audience
     * of the Kubernetes apiserver.
     */
    public function getAudiences(): ?array
    {
        return $this->audiences;
    }

    /**
     * Audiences is a list of the identifiers that the resource server presented with the token identifies
     * as. Audience-aware token authenticators will verify that the token was intended for at least one of
     * the audiences in this list. If no audiences are provided, the audience will default to the audience
     * of the Kubernetes apiserver.
     *
     * @return static
     */
    public function setAudiences(array $audiences)
    {
        $this->audiences = $audiences;

        return $this;
    }

    /**
     * Token is the opaque bearer token.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Token is the opaque bearer token.
     *
     * @return static
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }
}
