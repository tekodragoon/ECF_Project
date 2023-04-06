<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Model\Message;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}')]
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,
                          MailerInterface $mailer,
                          TranslatorInterface $translator,
                          LoggerInterface $logger,
    ): Response
    {
        $message = new Message();
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
                ->to($message->getEmail())
                ->subject($translator->trans('message.confirmReceived'))
                ->htmlTemplate('contact/confirmEmailSent.html.twig')
                ->context([
                    'message' => $message->getContent(),
                    'name' => $message->getName(),
                    'subject' => $message->getSubject(),
                    'locale' => $request->getLocale(),
                ]);
            $messageEmail = (new TemplatedEmail())
                ->to($this->getParameter('app.contact.email'))
                ->subject('Quai Antique Contact')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'message' => $message->getContent(),
                    'name' => $message->getName(),
                    'subject' => $message->getSubject(),
                    'emailSender' => $message->getEmail(),
                    'locale' => $request->getLocale(),
                ]);
            try {
                $mailer->send($messageEmail);
                $mailer->send($email);
                $this->addFlash('success', $translator->trans('message.sended'));
            } catch (TransportExceptionInterface $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', $translator->trans('message.problem'));
            }
            $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
