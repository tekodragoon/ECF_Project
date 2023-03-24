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
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}')]
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
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
                $this->addFlash('success', $translator->trans('message.sended'));
            } catch (TransportExceptionInterface $e) {
                //TODO: log error
                $this->addFlash('error', $translator->trans('message.problem'));
            }
            $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
