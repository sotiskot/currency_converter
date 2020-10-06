<?php

namespace App\Controller;

use App\Entity\Rates;
use App\Entity\Currency;

use App\Repository\RatesRepository;
use App\Repository\CurrencyRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        /**
         * get all data from currency table and rates table
         */
        $currencies = $this->getDoctrine()->getManager()->getRepository(Currency::class)->getAll();
        $rates = $this->getDoctrine()->getManager()->getRepository(Rates::class)->getAll();
        

        /**
         * remove db id from each
         */
        foreach ($currencies as $currency) {
            unset($currency['id']);
        }

        foreach ($rates as $rate) {
            unset($rate['id']);
        }
        

        /**
         * render the twig view and pass rate data and currency data
         */
        return $this->render('currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
            'rates' => json_encode($rates),
            'currencies' => json_encode($currencies)
            ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        
        $entityManager = $this->getDoctrine()->getManager();

        $rate = $entityManager->getRepository(Rates::class)->findOneBy(['currencies' => $content['currencies']]);
        if(!$rate)
        {
            return new JsonResponse([
                'message' => 'no record to edit'
            ]);
        }

        $rate->setRate((float)$content['rate']);

        $entityManager->flush();

        return new JsonResponse([
            'message' => 'New rate for '.$content['currencies'].' saved successfully'
        ]);
    }
}
