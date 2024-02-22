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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


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
            ->add('code',HiddenType::class, [
                'data' => 'code',
            ])
            ->add('value')
            ->add('category',ChoiceType::class, [
                'choices' => $this->getCategory(),
            ])
            ->add('type')
            ->add('expiration')
            ->add('usageLimit', NumberType::class, [
                'data' => 1, // Set default value to 1
                'disabled' => true, // Disable the field
            ])
            ->add('isValid')
            ->add('isGivenToUser')
            ->add('userWon',ChoiceType::class, [
                'choices' => $this->getUserOne(),
            ])
            ->add('marketRelated',ChoiceType::class, [
                'choices' => $this->getMarketRelatedChoices(),
            ])
            
            ->add('save', SubmitType::class, ['label' => 'Create Voucher']);
            //$builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
            
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
        $formattedChoices[$choice->getName()] = $choice;
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
            $email = $userWithReputation->getEmail();
            $formattedChoice = [$email => $userWithReputation];
        }
    
        return $formattedChoice;
    }

    private function getCategory()
    {
        // Fetch choices from the database
        $category = $this->entityManager->getRepository(VoucherCategory::class)->findAll();

        $formattedChoices = [];
        foreach ($category as $category) {
            $formattedChoices[$category->getTitre()] = $category;
        }

        return $formattedChoices;
    }

    // public function onPostSubmit(FormEvent $event): void
    // {
    //     $voucher = $event->getData();
    //     $form = $event->getForm();

    //     // Retrieve the selected user email, category title, and market name
    //     $userEmail = $form->get('userWon')->getData();
    //     $categoryTitle = $form->get('category')->getData();
    //     $marketName = $form->get('marketRelated')->getData();

    //     // Find the user, category, and market objects by their respective properties
    //     $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userEmail]);
    //     $category = $this->entityManager->getRepository(VoucherCategory::class)->findOneBy(['titre' => $categoryTitle]);
    //     $market = $this->entityManager->getRepository(Market::class)->findOneBy(['name' => $marketName]);
    //     var_dump($user);
    //     var_dump($category);
    //     var_dump($market);
        
    //     // Set the retrieved objects to the voucher
    //     $voucher->setUserWon($user);
    //     $voucher->setCategory($category);
    //     $voucher->setMarketRelated($market);

    //     // Hash the values and set the code
    // }
}
