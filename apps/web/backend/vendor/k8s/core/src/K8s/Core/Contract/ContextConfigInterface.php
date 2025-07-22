<?php

/**
 * This file is part of the k8s/core library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Core\Contract;

interface ContextConfigInterface
{
    /**
     * Authentication uses an HTTP user client certificate / key pair.
     */
    public const AUTH_TYPE_CERTIFICATE = 'certificate';

    /**
     * Authentication uses a bearer token.
     */
    public const AUTH_TYPE_TOKEN = 'token';

    /**
     * Authentication uses basic HTTP username / password authentication.
     */
    public const AUTH_TYPE_BASIC = 'basic';

    /**
     * The authentication type in use. Returns one of the AUTH_TYPE_* constants on this interface.
     */
    public function getAuthType(): string;

    /**
     * The HTTP user client key in base64 form.
     */
    public function getClientKeyData(): ?string;

    /**
     * The path to the HTTP user client key.
     */
    public function getClientKey(): ?string;

    /**
     * The path to the HTTP user client certificate.
     */
    public function getClientCertificate(): ?string;

    /**
     * The HTTP user client certificate in base64 form.
     */
    public function getClientCertificateData(): ?string;

    /**
     * The username for HTTP basic auth.
     */
    public function getUsername(): ?string;

    /**
     * The password for HTTP basic auth.
     */
    public function getPassword(): ?string;

    /**
     * The bearer token.
     */
    public function getToken(): ?string;

    /**
     * The API server base URI.
     */
    public function getServer(): string;

    /**
     * The path to the server certificate authority cert.
     */
    public function getServerCertificateAuthority(): ?string;

    /**
     * The server certificate authority cert in base64 form.
     */
    public function getServerCertificateAuthorityData(): ?string;
}
