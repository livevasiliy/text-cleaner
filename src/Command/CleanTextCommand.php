<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TextCleaner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(name: 'app:text:clean', description: 'Команда для очистки и нормализации пунктуации')]
class CleanTextCommand extends Command
{
    public function __construct(
        private readonly TextCleaner $textCleaner,
        private readonly Filesystem $filesystem,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('source', InputArgument::REQUIRED, 'Источник текста: "file" или "console"')
            ->addArgument('output-file', InputArgument::REQUIRED, 'Путь до выходного файла')
            ->addArgument('input-file', InputArgument::OPTIONAL, 'Путь до входного файла (если источник "file")');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Установка кодировки
        mb_internal_encoding('UTF-8');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('chcp 65001 > nul');
        }

        $source = strtolower(trim($input->getArgument('source')));
        $inputFile = $input->getArgument('input-file');
        $outputFile = $input->getArgument('output-file');

        // Валидация и интерактивный ввод
        $helper = $this->getHelper('question');

        if (!in_array($source, ['file', 'console'])) {
            $question = new ChoiceQuestion(
                'Выберите источник текста:',
                ['file', 'console'],
                'file'
            );
            $source = $helper->ask($input, $output, $question);
        }

        if ($source === 'file' && !$inputFile) {
            $question = new Question('Введите путь к входному файлу: ');
            $inputFile = trim($helper->ask($input, $output, $question));
        }

        if (!$outputFile && $source !== 'console') {
            $question = new Question('Введите путь к выходному файлу: ');
            $outputFile = trim($helper->ask($input, $output, $question));
        } elseif ($source === 'console' && !$outputFile) {
            $question = new Question('Введите путь к выходному файлу: ');
            $outputFile = trim($helper->ask($input, $output, $question));
        }

        // Получение текста
        if ($source === 'file') {
            if (!$this->filesystem->exists($inputFile)) {
                $output->writeln("Ошибка: Файл $inputFile не найден.");
                return Command::FAILURE;
            }
            try {
                $originalText = file_get_contents($inputFile);
            } catch (\Exception $e) {
                $output->writeln("Ошибка чтения файла $inputFile: {$e->getMessage()}");
                return Command::FAILURE;
            }
        } else {
            $output->writeln('Введите текст напрямую (для завершения нажмите Enter дважды):');
            $lines = [];
            while (true) {
                $line = trim(fgets(STDIN));
                if ($line === '') {
                    break;
                }
                $lines[] = $line;
            }
            $originalText = implode("\n", $lines);
            if (empty($originalText)) {
                $output->writeln('Ошибка: Введен пустой текст.');
                return Command::FAILURE;
            }
        }

        // Обработка текста
        $cleanedText = $this->textCleaner->cleanText($originalText);

        // Запись в файл
        try {
            $this->filesystem->dumpFile($outputFile, $cleanedText);
            $output->writeln("Текст успешно обработан и записан в $outputFile");
            return Command::SUCCESS;
        } catch (IOException $e) {
            $output->writeln("Ошибка записи в файл $outputFile: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
