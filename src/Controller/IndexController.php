<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\EditProfileType;
use App\Repository\AvisRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'avis' => $avisRepository->findValides(),
        ]);
    }

    #[Route('/user', name: 'app_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function user(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findByUser($this->getUser());

        return $this->render('index/user.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/user/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response {

        $user = $this->getUser();

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }

            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('app_user');
        }

        return $this->render('index/edit-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@viteetgourmand.fr', 'Vite & Gourmand'))
                ->to('contact@viteetgourmand.fr')
                ->replyTo(new Address($data['email']))
                ->subject('[Contact] ' . $data['titre'])
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'titre'       => $data['titre'],
                    'expediteur'  => $data['email'],
                    'description' => $data['description'],
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('index/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenements', name: 'app_evenements', methods: ['GET'])]
    public function evenements(): Response
    {
        return $this->render('index/evenements.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions')]
    public function mentions(): Response
    {
        return $this->render('index/mentions-legales.html.twig');
    }
}
