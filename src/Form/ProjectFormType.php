<?php

namespace App\Form;

use App\Entity\Project;
use App\Enum\ProjectType;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\HeaderGroupField;
use Ehyiah\QuillJsBundle\DTO\Fields\BlockField\ListField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\BoldField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\ItalicField;
use Ehyiah\QuillJsBundle\DTO\Fields\InlineField\LinkField;
use Ehyiah\QuillJsBundle\DTO\QuillGroup;
use Ehyiah\QuillJsBundle\Form\QuillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                        new HeaderGroupField(),
                        new BoldField(),
                        new ItalicField(),
                        new LinkField(),
                        new ListField('bullet')
                    ),
                ],
            ])
            ->add('mImage',FileType::class, [
                'required' => false,
                'data_class' => null,
            ])
            ->add('sImage',FileType::class, [
                'required' => false,
                'data_class' => null,
            ])
            ->add('skills')
            ->add('type', ChoiceType::class, [
                'choices' => ProjectType::cases(),
                'choice_label' => fn(ProjectType $type) => $type->label(),
                'choice_value' => fn(?ProjectType $type) => $type?->value,
                'placeholder' => 'Vyber typ projektu',
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
