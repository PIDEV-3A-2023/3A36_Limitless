<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Stripe\Stripe;
use Stripe\Charge;

class PaiementController extends AbstractController
{

    #[Route('/formPaiement', name: 'app_paiement_form')]

    public function afficherFomr()
    {
        return $this->render('paiement/paiement.html.twig');
    }

    #[Route('/paiementStripe', name: 'app_paiement')]
    public function payment(Request $request)
    {   
        $numeroCarte=$_POST['numero-carte'];
        $exp_month=intval(substr($_POST['expiration'],0,2));
        $exp_year=intval(substr($_POST['expiration'],-2));
        $cvc=$_POST['cvv'];
        $nom=$_POST['nom'];
        $email=$_POST['email'];
        $prix=intval($_POST['prix']);
        $refer=$_POST['refer'];

            
   \Stripe\Stripe::setApiKey('sk_test_51MeOmdAImipVJMp4npAZs3vzcocjTuiSr65s2gv5Wlwlt2D0kKf3CWSWoNuQMKXVT7vklxqUV7MlxKGCUlqDUB0e00Uqss47MZ'); 
       
try{
    $source = \Stripe\Source::create([
    'type' => 'card',
    'card' => [
        'number' => $numeroCarte,
        'exp_month' => $exp_month,
        'exp_year' => $exp_year,
        'cvc' => $cvc
    ],
    'metadata' => [
        'customer_name' => $nom,
        'customer_email' => $email,
    ],
    'amount' => $prix*100,
    'currency' => 'eur'
    ]);
   
   
    $payment = \Stripe\Charge::create([
    'amount' => $prix*100,
    'currency' => 'eur',
    'source' => $source->id,
    'receipt_email' => 'ballamoussa.keita@esprit.tn',
    'description'=>'Payement de la commande: '.$refer
    ]);
    }
    catch (\Stripe\Exception\CardException $e) {
           // Gérez les erreurs de carte bancaire
           $error = $e->getError();
           $errorMessage = $error['message'];
            return $this->render('paiement/paiement.html.twig',['erreur'=>$errorMessage]);
       } catch (\Stripe\Exception\RateLimitException | \Stripe\Exception\InvalidRequestException | \Stripe\Exception\AuthenticationException | \Stripe\Exception\ApiConnectionException | \Stripe\Exception\ApiErrorException $e) {
           // Gérez les autres erreurs Stripe
           $errorMessage = $e->getMessage();
           return $this->render('paiement/paiement.html.twig',['erreur'=>$errorMessage]);
       }
       

       return new Response("PAYEMENT REUSSI");
    }


    #[Route('/paiementsuccess', name: 'app_paiement_succes')]
    public function paymentSuccess()
    {
         // Afficher la page de confirmation de paiement réussi
        // return $this->render('payment_success.html.twig');
        var_dump("succès");
        die;
    }
}
