<?php
namespace App\Form;
 
use App\Entity\Award;
use App\Entity\Employee;
use App\Entity\School;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 
class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('curp')
            ->add('award', EntityType::class, [
              'class' => Award::class,
              'choice_label' => 'name'
            ])
            ->add('school', EntityType::class, [
              'class' => School::class,
              'choice_label' => 'name'
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
