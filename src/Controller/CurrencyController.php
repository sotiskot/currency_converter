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

    /**
     * @Route("/list", name="list")
     */
    public function rateList()
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
        return $this->render('currency/rateList.html.twig', [
            'controller_name' => 'CurrencyController',
            'rates' => json_encode($rates),
            'currencies' => json_encode($currencies)
            ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addRate(Request $request)
    {
        /**
         * insert new Rate in Rates Table
         */
        $message = '';
        $fail = true;
        
        $content = json_decode($request->getContent(), true);
        $count = 0;

        $entityManager = $this->getDoctrine()->getManager();

        /**
         *  Query to check if the rate trying to add already exist,
         *  ex SELECT * FROM rates WHERE currencies = 'eurusd' OR currencies = 'usdeur'
         */ 
        $check = $this->getDoctrine()->getManager()->getRepository(Rates::class)->findBy(['currencies' => [$content['from'].$content['to'], $content['to'].$content['from']]] );


        /**
         *  Check if query is empty and the user doesn't try to add a rate for 1 coin
         *  and last make sure the input from the user is a valid number
         */
        if(count($check) == 0 && $content['from'] != $content['to'] && is_numeric($content['rate']))
        {
            // if everything is proper create a new rate
            $rate = new Rates();

            $rate->setCurrencies($content['from'].$content['to']);
            $rate->setRate((float)$content['rate']);

            $entityManager->persist($rate);

            $entityManager->flush();
            
            //  alter message for the user and fail condition for the vue component
            $message = 'Added succesfully';
            $fail = false;
        }else if($content['from'] == $content['to'])
        {
            // checks if user has the same coin in both selects and sends back appropriate message
            $message = 'Tried to add rate for 1 currency';
        }else if(!is_numeric($content['rate']))
        {
            // checks if user's input is not a number to send back appropriate message
            $message = 'Make sure the rate is a proper number';
        }
        else{
            /**
             *  If everything else is correct then the currencies he is trying
             *  to add a rate for already have a rate
             */  
            $message = 'Selected currencies already have a rate';
            $fail = true;
        }

        // create a json response with a message and a fail condition for the vue
        return new JsonResponse([
            'message' => $message,
            'failed' => $fail
        ]);
    }

    /**
     * @Route("/delete", name="delete")
     */
    public function delRate(Request $request)
    {
        /**
         * delete Rate from Rates Table
         */
        
        $content = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();
        
        /**
         *  Doctrine query to find the rate the user is trying to delete
         *  ex SELECT * FROM rates WHERE currencies = 'eurusd'
         *  Run the doctrine remove Query and then Send back a message
         *  for the user
         */
        $rate = $this->getDoctrine()->getManager()->getRepository(Rates::class)->findOneBy(['currencies' => $content['currencies']]);

        $entityManager->remove($rate);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Deleted entry',
        ]);
    }
}
