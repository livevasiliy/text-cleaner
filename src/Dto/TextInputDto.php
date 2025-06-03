<?php

declare(strict_types=1);

use Symfony\Component\Validator\Constraints as Assert;

class TextInputDto
{
    #[Assert\AtLeastOneOf(
        constraints: [
            new Assert\NotBlank(),
            new Assert\NotNull(),
        ],
        message: 'Необходимо ввести текст или загрузить файл.'
    )]
    public ?string $textInput = null;
}
