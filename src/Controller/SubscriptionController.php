<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subscription;

class SubscriptionController extends AbstractController {

    public function subscription(Request $request, \Swift_Mailer $mailer) {
        $email = $request->request->get("subEmail");
        if ($email == '' || $email == null) {
            return $this->redirectToRoute('onlyError');
        }
        $message = (new \Swift_Message('Confirmación de suscripción a Legends GG'))
                ->setFrom('info.legendsgg@gmail.com')
                ->setTo($email)
                ->setBody(
                $this->renderView(
                        'emails/subscription.html.twig',
                        ['email' => $email,
                            'emailId' => base64_encode($email)
                        ]
                ),
                'text/html'
        );

        $mailer->send($message);
        return $this->render('subscription/subscription.html.twig', array("active" => ""));
    }

    function confirmSubscription($id, $email) {
        if ($email == base64_decode($id)) {
            $entityManager = $this->getDoctrine()->getManager();
            $subscription = $entityManager->getRepository(Subscription::class)->findByEmail($email);
            if (!$subscription) {
                $subscription = new Subscription();
                $subscription->setEmail($email);
                $entityManager->persist($subscription);
                $entityManager->flush();
                return $this->render('subscription/confirmed.html.twig', array(
                    "message" => "Te has suscrito correctamente.",
                    "icon" => "check",
                    "active" => ""
                ));
            } else {
                return $this->render('subscription/confirmed.html.twig', array(
                    "message" => "Error, puede que ya estés suscrito al boletín.",
                    "icon" => "times",
                    "active" => ""
                ));
            }
        } else {
            return $this->redirectToRoute('onlyError');
        }
    }

}
