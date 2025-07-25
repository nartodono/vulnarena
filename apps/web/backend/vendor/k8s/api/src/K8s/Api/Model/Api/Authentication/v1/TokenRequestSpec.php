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
 * TokenRequestSpec contains client provided parameters of a token request.
 */
class TokenRequestSpec
{
    /**
     * @Kubernetes\Attribute("audiences",isRequired=true)
     * @var string[]
     */
    protected $audiences;

    /**
     * @Kubernetes\Attribute("boundObjectRef",type="model",model=BoundObjectReference::class)
     * @var BoundObjectReference|null
     */
    protected $boundObjectRef = null;

    /**
     * @Kubernetes\Attribute("expirationSeconds")
     * @var int|null
     */
    protected $expirationSeconds = null;

    /**
     * @param string[] $audiences
     */
    public function __construct(array $audiences)
    {
        $this->audiences = $audiences;
    }

    /**
     * Audiences are the intendend audiences of the token. A recipient of a token must identify themself
     * with an identifier in the list of audiences of the token, and otherwise should reject the token. A
     * token issued for multiple audiences may be used to authenticate against any of the audiences listed
     * but implies a high degree of trust between the target audiences.
     */
    public function getAudiences(): array
    {
        return $this->audiences;
    }

    /**
     * Audiences are the intendend audiences of the token. A recipient of a token must identify themself
     * with an identifier in the list of audiences of the token, and otherwise should reject the token. A
     * token issued for multiple audiences may be used to authenticate against any of the audiences listed
     * but implies a high degree of trust between the target audiences.
     *
     * @return static
     */
    public function setAudiences(array $audiences)
    {
        $this->audiences = $audiences;

        return $this;
    }

    /**
     * BoundObjectRef is a reference to an object that the token will be bound to. The token will only be
     * valid for as long as the bound object exists. NOTE: The API server's TokenReview endpoint will
     * validate the BoundObjectRef, but other audiences may not. Keep ExpirationSeconds small if you want
     * prompt revocation.
     */
    public function getBoundObjectRef(): ?BoundObjectReference
    {
        return $this->boundObjectRef;
    }

    /**
     * BoundObjectRef is a reference to an object that the token will be bound to. The token will only be
     * valid for as long as the bound object exists. NOTE: The API server's TokenReview endpoint will
     * validate the BoundObjectRef, but other audiences may not. Keep ExpirationSeconds small if you want
     * prompt revocation.
     *
     * @return static
     */
    public function setBoundObjectRef(BoundObjectReference $boundObjectRef)
    {
        $this->boundObjectRef = $boundObjectRef;

        return $this;
    }

    /**
     * ExpirationSeconds is the requested duration of validity of the request. The token issuer may return
     * a token with a different validity duration so a client needs to check the 'expiration' field in a
     * response.
     */
    public function getExpirationSeconds(): ?int
    {
        return $this->expirationSeconds;
    }

    /**
     * ExpirationSeconds is the requested duration of validity of the request. The token issuer may return
     * a token with a different validity duration so a client needs to check the 'expiration' field in a
     * response.
     *
     * @return static
     */
    public function setExpirationSeconds(int $expirationSeconds)
    {
        $this->expirationSeconds = $expirationSeconds;

        return $this;
    }
}
