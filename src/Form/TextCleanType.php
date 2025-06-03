<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextCleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text_input', TextareaType::class, [
                'required' => false,
                'label' => 'Введите текст',
                'constraints' => [
                    new Assert\Length(['max' => 10000]),
                ],
            ])
            ->add('text_file', FileType::class, [
                'required' => false,
                'label' => 'Или загрузите .txt файл',
                'mapped' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['text/plain'],
                        'mimeTypesMessage' => 'Допустим только текстовый файл (.txt)',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
