<?php

declare(strict_types=1);

namespace Presentation\RequestHandlers\Admin;

use Billing\Domain\Payments\PaymentGatewayFactoryInterface;
use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Presentation\Response\ViewResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

/** @package Presentation\RequestHandlers\Admin */
#[Route(
    path: '/settings/[general|brand|billing|payments|openai|mail|smtp|policies|:name]?',
    method: RequestMethod::GET
)]
class SettingsRequestHandler extends AbstractAdminViewRequestHandler implements
    RequestHandlerInterface
{
    /**
     * @param Dispatcher $dispatcher 
     * @param PaymentGatewayFactoryInterface $factory 
     * @return void 
     */
    public function __construct(
        private Dispatcher $dispatcher,
        private PaymentGatewayFactoryInterface $factory
    ) {
    }

    /**
     * @param ServerRequestInterface $request 
     * @return ResponseInterface 
     * @throws MissingResourceException 
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name');
        if (!$name) {
            $name = 'general';
        }

        $data = [];
        $data['currencies'] = Currencies::getNames();
        $data['payment_gateways'] = [];

        foreach ($this->factory->listAll() as $gateway) {
            $data['payment_gateways'][] = [
                'name' => $gateway->getName(),
                'logo' => $gateway->getLogoImgSrc(),
                'is_enabled' => $gateway->isEnabled(),
                'lookup_key' => $gateway->getLookupKey(),
            ];
        }

        return new ViewResponse(
            '/templates/admin/settings/' . $name . '.twig',
            $data
        );
    }
}
