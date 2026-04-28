<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: '/login', name: 'app_login', methods: ['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Security $security): RedirectResponse
    {
        $security->logout();
        return $this->redirectToRoute('app_login');
    }

    #[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
    public function create(HttpFoundationRequest $request, EntityManagerInterface $em, MailerInterface $mailer, Security $security): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']); // rôle par défaut

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class, [
            'label' => 'Créer',
            'attr' => ['class' => 'btn btn-primary'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $mailer->send(
                (new TemplatedEmail())
                    ->from(new Address('no-reply@viteetgourmand.fr', 'Vite & Gourmand'))
                    ->to($user->getEmail())
                    ->subject('Bienvenue sur Vite & Gourmand !')
                    ->htmlTemplate('emails/inscription.html.twig')
                    ->context(['user' => $user])
            );

            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('index/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
