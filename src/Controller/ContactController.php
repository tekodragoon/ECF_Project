<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Model\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $message = new Message();
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO: Change to TemplatedEmail
            $email = (new Email())
                ->to($message->getEmail())
                ->subject($message->getSubject())
                ->text($message->getContent())
                ->html( '<p>'.$message->getContent().'</p>');
            try {
                $mailer->send($email);
                $this->addFlash('success', 'Your message has been send.');
            } catch (TransportExceptionInterface $e) {
                //TODO: log error
                $this->addFlash('error', 'A problem has occurred.');
            }
            $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
