<?php declare(strict_types=1);

namespace EventCandyCandyBags\Controller;

use ErrorException;
use EventCandy\LabelMe\Core\Content\CandyPackage\CandyPackageEntity;
use EventCandy\LabelMe\Core\Content\Event\EventEntity;
use EventCandy\LabelMe\Core\Content\Label\LabelEntity;
use EventCandy\Sets\Storefront\Page\Product\Subscriber\ProductListingSubscriber;
use EventCandy\Sets\Utils;
use Exception;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartPersister;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\Price\PriceRounding;
use Shopware\Core\Checkout\Cart\Price\ReferencePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\ReferencePrice;
use Shopware\Core\Checkout\Cart\SalesChannel\CartResponse;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Product\Aggregate\ProductPrice\ProductPriceEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\Price\ProductPriceDefinitionBuilder;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Twig\Extension\SwSanitizeTwigFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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


    public function __construct(
        EntityRepositoryInterface $mediaRepository,
        SystemConfigService $systemConfigService

    )
    {
        $this->mediaRepository = $mediaRepository;
        $this->systemConfigService = $systemConfigService;
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

//        if (key_exists('arrowImage', $config)) {
//            $config['arrowUrl'] = $this->getImageUrl('arrowImage', $config, $context);
//        }
//
//        if (key_exists('placeholderImage', $config)) {
//            $config['placeholderImageUrl'] = $this->getImageUrl('placeholderImage', $config, $context);
//        }

        return new JsonResponse($config);
    }

    private function getImageUrl(string $configMediaKey, array $config, Context $context)
    {
        $id = $config[$configMediaKey];
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $id));

        /** @var MediaEntity $mediaEntity */
        $mediaEntity = $this->mediaRepository->search($criteria, $context)->first();
        return $mediaEntity ? $mediaEntity->getUrl() : 'noimage';
    }

}
