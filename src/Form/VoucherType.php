<?php

namespace App\Form;

use App\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Market;
use App\Entity\User;
use App\Entity\VoucherCategory;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class VoucherType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('expiration')
            ->add('value')
            ->add('usageLimit')
            ->add('type')
            ->add('isValid')
            ->add('isGivenToUser')
            ->add('category',ChoiceType::class, [
                'choices' => $this->getCategory(),
            ])
            ->add('userWon',ChoiceType::class, [
                'choices' => $this->getUserOne(),
            ])
            ->add('marketRelated',ChoiceType::class, [
                'choices' => $this->getMarketRelatedChoices(),
            ])
            ->add('code')
            ->add('save', SubmitType::class, ['label' => 'Create Voucher']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }

    private function getMarketRelatedChoices()
    {
        // Fetch choices from the database
        $choices = $this->entityManager->getRepository(Market::class)->findAll();

        $formattedChoices = [];
        foreach ($choices as $choice) {
            $formattedChoices[$choice->getId()] = $choice->getName();
        }

        return $formattedChoices;
    }

    private function getUserOne()
    {
        // Create a query builder instance
        $qb = $this->entityManager->createQueryBuilder();
    
        // Build the query to select users with reputation >= 1500
        $query = $qb->select('u')
                    ->from(User::class, 'u')
                    ->where($qb->expr()->gte('u.reputation', ':reputation'))
                    ->setParameter('reputation', 1500)
                    ->getQuery();
    
        // Execute the query to get the user with reputation >= 1500
        $userWithReputation = $query->getOneOrNullResult();
    
        $formattedChoice = null; // Initialize with null, in case user is not found
        if ($userWithReputation !== null) {
            // User found, create a formatted choice
            $formattedChoice = [$userWithReputation->getId() => $userWithReputation->getUsername()];
        }
    
        return $formattedChoice;
    }

    private function getCategory()
    {
        // Fetch choices from the database
        $category = $this->entityManager->getRepository(VoucherCategory::class)->findAll();

        $formattedChoices = [];
        foreach ($category as $category) {
            $formattedChoices[$category->getId()] = $category->getTitre();
        }

        return $formattedChoices;
    }
}
