<?php

namespace App\Controller;
use App\Entity\CurrencyRates;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RatesController extends AbstractController
{
    const PER_PAGE = 10;

    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator
    ) {}

    #[Route('/')]
    public function listAction()
    {
        $currencyRatesRepo = $this->em->getRepository(CurrencyRates::class);
        $currencies        = $currencyRatesRepo->findCurrencies();

        return $this->render('Rates/rates.html.twig', ['currencies' => $currencies]);
    }

    #[Route('/get-rates', name: 'get-rates')]
    public function getRatesAction(Request $request)
    {
        $params            = $this->buildParamsFromRequest($request);
        $page              = $request->get('page');
        $currencyRatesRepo = $this->em->getRepository(CurrencyRates::class);
        $query             = $currencyRatesRepo->getRatesQuery($params);
        $lastUpdate        = $currencyRatesRepo->findOneBy([], ['date' => 'DESC']);
        $footer            = $currencyRatesRepo->findMinMaxAvgRates($params);

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            self::PER_PAGE
        );

        return new JsonResponse([
            'items'       => $pagination->getItems(),
            'total_pages' => $pagination->getPaginationData()['pageCount'],
            'footer'      => $footer,
            'last_update' => $lastUpdate->getDate(),
        ]);
    }

    private function buildParamsFromRequest(Request $request): array
    {
        return [
            'base'      => $request->get('base'),
            'to'        => $request->get('to'),
            'sortBy'    => $request->get('sortBy'),
            'sortOrder' => $request->get('sortOrder'),
        ];
    }
}