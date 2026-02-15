<?php

namespace App\Form;

use App\Entity\Experience;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\LinkField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExperienceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company')
            ->add('shortDescription',TextareaType::class)
            ->add('longDescription', QuillType::class,[
                'quill_options' => [
                    QuillGroup::build(
                        new BoldField(),
                        new ItalicField(),
                        new LinkField(),
                        // and many more
                    ),
                ],
            ])
            ->add('position')
            ->add('dateFrom')
            ->add('dateTo')
            ->add('save',SubmitType::class,['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
