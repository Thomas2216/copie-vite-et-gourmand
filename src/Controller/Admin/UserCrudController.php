<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack,
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('plainPassword', 'Nouveau mot de passe (laisser vide pour ne pas changer)')
                ->setRequired(false)
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false),
            TextField::new('nom'),
            TextField::new('prenom', 'Prénom'),
            TextField::new('adresse', 'Adresse postale'),
            TextField::new('ville'),
            TelephoneField::new('telephone', 'Téléphone'),
            ArrayField::new('roles', 'Role')->setPermission('ROLE_ADMIN'),
        ];
    }

    public function persistEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPasswordIfProvided($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPasswordIfProvided($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPasswordIfProvided(mixed $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        $allData = $this->requestStack->getCurrentRequest()?->request->all() ?? [];
        $plainPassword = null;
        foreach ($allData as $value) {
            if (is_array($value) && !empty($value['plainPassword'])) {
                $plainPassword = $value['plainPassword'];
                break;
            }
        }

        if (!empty($plainPassword)) {
            $hashed = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashed);
        }
    }
}
