<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use LogicException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app_forgot_password_request')]
    public function forgotPassword(Request                 $request,
                                   UserRepository          $userRepository,
                                   TranslatorInterface     $translator,
                                   TokenGeneratorInterface $tokenGenerator,
                                   MailerInterface         $mailer,
                                   LoggerInterface         $logger,
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user === null) {
                $this->addFlash('error', $translator->trans('message.emailNotFound'));
                return $this->redirectToRoute('app_forgot_password_request');
            }

            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $userRepository->save($user, true);

            $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject($translator->trans('message.emailSubject'))
                ->htmlTemplate('security/resetPasswordEmail.html.twig')
                ->context([
                    'url' => $url,
                    'locale' => $request->getLocale(),
                ]);
            try {
                $mailer->send($email);
                $this->addFlash('success', $translator->trans('message.emailSent', ['%email%' => $user->getEmail()]));
            } catch (TransportExceptionInterface $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', $translator->trans('message.problem'));
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgot-password-request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'reset_password')]
    public function resetPassword(string                      $token,
                                  Request                     $request,
                                  UserRepository              $userRepository,
                                  UserPasswordHasherInterface $hasher,
                                  TranslatorInterface         $translator,
    ): Response
    {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if ($user === null) {
            $this->addFlash('error', $translator->trans('message.invalidToken'));
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setResetToken(null);

            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                ));
            $userRepository->save($user, true);
            $this->addFlash('success', $translator->trans('message.passChanges'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
