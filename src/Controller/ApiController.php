<?php
declare(strict_types=1);

namespace EventCandyCandyBags\Controller;

use Exception;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartPersister;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Content\Product\Cart\ProductGatewayInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class ApiController extends AbstractController
{

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @var EntityRepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var CartPersister
     */
    private $cartPersister;

    /**
     * @var ProductGatewayInterface
     */
    private $productGateway;

    /**
     * ApiController constructor.
     * @param SystemConfigService $systemConfigService
     * @param EntityRepositoryInterface $mediaRepository
     * @param CartService $cartService
     * @param CartPersister $cartPersister
     * @param ProductGatewayInterface $productGateway
     */
    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $mediaRepository,
        CartService $cartService,
        CartPersister $cartPersister,
        ProductGatewayInterface $productGateway
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->mediaRepository = $mediaRepository;
        $this->cartService = $cartService;
        $this->cartPersister = $cartPersister;
        $this->productGateway = $productGateway;
    }


    /**
     * @Route("/store-api/v{version}/eccb/get-config", name="api.action.eccb.get-config", methods={"GET"})
     * @param Request $request
     * @param Context $context
     * @return JsonResponse
     */
    public function getPluginConfig(Request $request, Context $context): JsonResponse
    {
        $config = $this->systemConfigService->get('EventCandyCandyBags.config');
        return new JsonResponse($config);
    }


    /**
     * @Route("/store-api/v{version}/eccb/add-line-item", name="api.eccb.add-line-item", methods={"POST"}, defaults={"XmlHttpRequest": true})
     * @param Cart $cart
     * @param RequestDataBag $requestDataBag
     * @param Request $request
     * @param SalesChannelContext $salesChannelContext
     */
    public function addLineItems(
        Cart $cart,
        RequestDataBag $requestDataBag,
        Request $request,
        SalesChannelContext $salesChannelContext
    ) {
        $lineItemData = $requestDataBag->all();

        try {
            $uuid = $this->getUuid($lineItemData['selected']);

            $lineItem = new LineItem(
                $uuid,
                'event-candy-candy-bags',
                $uuid,
                1
            );

            $lineItem->setPayload($lineItemData);
            $lineItem->setStackable(true);
            $lineItem->setRemovable(true);

            // skip stock validation here
            $cart = $this->cartService->add($cart, $lineItem, $salesChannelContext);
            $this->cartPersister->save($cart, $salesChannelContext);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage());
        }
        return $this->redirectToRoute('frontend.cart.offcanvas');
    }

    /**
     * @param $selected
     * @return false|string
     */
    private function getUuid($selected): string
    {
        $id = '';
        foreach ($selected as $data) {
            if (isset($data['id'])) {
                $id .= $data['id'];
            }
        }

        $uuid = hash('md5', $id);
        return $uuid;
    }


}
