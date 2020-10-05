<?php

namespace App\Controller;

use App\Entity\Rates;
use App\Repository\RatesRepository;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/currency", name="currency")
     */
    public function index()
    {
        $currencies = $this->getDoctrine()->getManager()->getRepository(Currency::class)->getAll();
        $rates = $this->getDoctrine()->getManager()->getRepository(Rates::class)->getAll();
        
        foreach ($currencies as $currency) {
            unset($currency['id']);
        }

        foreach ($rates as $rate) {
            unset($rate['id']);
        }
    
        return $this->render('currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
            'rates' => json_encode($rates),
            'currencies' => json_encode($currencies)
            ]);
    }
}
