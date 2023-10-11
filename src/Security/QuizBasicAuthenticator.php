<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class QuizBasicAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserProviderInterface $userProvider
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $userIdentifier = $request->getSession()->get('username');
        if (null === $userIdentifier) {
            throw new CustomUserMessageAuthenticationException('Authentication failed');
        }

        return new SelfValidatingPassport(new UserBadge($userIdentifier,
            fn(string $userIdentifier): UserInterface =>
                $this->userProvider->loadUserByIdentifier($userIdentifier)
        ));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_authentication'));
    }
}
