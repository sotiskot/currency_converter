<?php

namespace App\Controller;

use App\Entity\Rates;
use App\Repository\RatesRepository;
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
    public function index(RatesRepository $rates)
    {

        $rates = $this->getDoctrine()->getManager()->getRepository(Rates::class)->getAll();
        
        $temp = '';

        foreach($rates as $id => $key)
        {
            foreach($key as $name => $value)
            {
                if($name != 'id')
                {
                    if($name == 'currencies')
                    {
                        $temp = $temp."'".$value."':'";
                    }else{
                        $temp = $temp.$value."',";
                    }
                }
            }
            
        }

        $temp = substr_replace($temp, '', -1);
        $temp = explode(',', $temp);

        return $this->render('currency/index.html.twig', [
            'controller_name' => 'CurrencyController',
            'rates' => json_encode($temp)
            ]);
    }
}
