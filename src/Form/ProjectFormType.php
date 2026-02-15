<?php

namespace App\Form;

use App\Entity\Project;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ImageField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\LinkField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('shortDescription', TextareaType::class)
            ->add('longDescription', QuillType::class,[
                'quill_options' => [
                    QuillGroup::build(
                        new HeaderField(HeaderField::HEADER_OPTION_1),
                        new HeaderField(HeaderField::HEADER_OPTION_2),
                        new BoldField(),
                        new ItalicField(),
                        new LinkField()
                    ),
                ],
            ])
            ->add('mImage',FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Assert\Count([
                        'max' => 5,
                        'maxMessage' => 'Můžeš nahrát maximálně {{ limit }} obrázky.',
                    ]),
                ],
            ])
            ->add('sImage',FileType::class, [
                'required' => false,
            ])
            ->add('isSchoolProject',CheckboxType::class, [
                'required' => false,
            ])
            ->add('date',DateType::class)
            ->add('save',SubmitType::class,['label' => 'Save']);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
