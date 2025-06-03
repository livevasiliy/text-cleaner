<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\TextCleanType;
use App\Service\TextCleaner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TextInputDto;

class TextCleanController extends AbstractController
{
    #[Route('/', name: 'text_cleaner')]
    public function index(): Response
    {
        $form = $this->createForm(TextCleanType::class);

        return $this->render('text_cleaner/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/download', name: 'text_cleaner_download')]
    public function download(Request $request): Response
    {
        $cleaned = $request->getSession()->get('cleaned_text');

        if (!$cleaned) {
            return $this->redirectToRoute('text_cleaner');
        }

        $response = new StreamedResponse(function () use ($cleaned) {
            echo $cleaned;
        });

        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="cleaned_text.txt"');

        return $response;
    }

    #[Route('/clean/ajax', name: 'text_cleaner_ajax', methods: ['POST'])]
    public function ajaxClean(
        Request $request,
        TextCleaner $textCleaner,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = new TextInputDto();
        $dto->textInput = $request->request->get('text_input');

        $file = $request->files->get('text_file');
        $originalText = null;

        // Приоритет: текст из textarea
        if (!empty($dto->textInput)) {
            $originalText = $dto->textInput;
        } elseif ($file && $file->isValid()) {
            $originalText = file_get_contents($file->getPathname());
            $dto->textInput = $originalText; // для валидации
        }

        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return $this->json(['error' => implode("\n", $messages)], 422);
        }

        $cleanedText = $textCleaner->cleanText($originalText);

        return $this->json([
            'original' => $originalText,
            'cleaned' => $cleanedText,
        ]);
    }
}
