<?php

namespace App\Controller;

use App\Entity\Mailing;
use App\Form\UsermailType;
use App\Repository\MailingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, MailingRepository $repo, ValidatorInterface $validator): Response
    {
        $mail = new Mailing();
        $form = $this->createForm(UsermailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $repo->save($mail, true);
                $this->addFlash('success', 'Votre inscription à notre newsletter a bien été effectuée.');
            } else {
                $errors = $validator->validate($mail);
                $messages = [];
                foreach ($errors as $error) {
                    $messages[] = $error->getMessage();
                }
                $this->addFlash('errors', $messages);
            }
            return $this->redirectToRoute('app_home', ['_fragment' => 'block-actu']);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
        ]);
    }


}
