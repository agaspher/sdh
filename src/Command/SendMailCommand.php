<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SendMailCommand extends Command
{
    private MailerInterface $mailer;
    private ValidatorInterface $validator;

    public function __construct(MailerInterface $mailer, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->mailer    = $mailer;
        $this->validator = $validator;
    }

    public function configure()
    {
        return $this
            ->setName('mailer:send')
            ->addOption('from', null, InputOption::VALUE_REQUIRED, 'Email FROM, example test@test.com or Test Test <test@test.com>')
            ->addOption('to', null, InputOption::VALUE_REQUIRED, 'Email TO, example test@test.com or Test Test <test@test.com>')
            ->addOption('subject', null, InputOption::VALUE_REQUIRED, 'Email subject')
            ->addOption('body', null, InputOption::VALUE_REQUIRED, 'Email body');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $ss = new SymfonyStyle($input, $output);

        $ss->title('Sending new Email message');

        try {
            $this->mailer->send(
                (new Email())
                    ->from(Address::create($this->askForParam($ss, $input, 'from')))
                    ->to(Address::create($this->askForParam($ss, $input, 'to')))
                    ->subject($this->askForParam($ss, $input, 'subject'))
                    ->text($this->askForParam($ss, $input, 'body'))
            );
        } catch (TransportExceptionInterface $e) {
            $ss->error('Failed to send email');

            throw $e;
        } catch (\RuntimeException $e) {
            $ss->error($e->getMessage());

            return Command::FAILURE;
        }

        $ss->success('Email added to queue');

        return 0;
    }

    /**
     * @throws \RuntimeException
     */
    public function askForParam(SymfonyStyle $ss, InputInterface $input, string $param): string
    {
        $map = $this->getQuestionsMap();

        if ($input->getOption($param)) {
            $validator = $map[$param]['validators'] ?? null;

            $result = $validator ? $validator($input->getOption($param)) : $input->getOption($param);
        } else {
            $result = $ss->ask($map[$param]['question'], $map[$param]['default'] ?? null, $map[$param]['validators'] ?? null);
        }

        if (!$result) {
            throw new \RuntimeException(sprintf('You must specify options: %s', implode(', ', array_keys($map))));
        }

        $ss->comment(sprintf('%s: %s', $param, $result));

        return $result;
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    private function getQuestionsMap(): array
    {
        return [
            'from' => [
                'question' => 'From Email',
                'default' => 'from@email.com',
                'validators' => function (?string $answer): string {
                    $this->validateAnswer(
                        $answer,
                        [
                            new Assert\NotBlank(),
                            new Assert\Email(),
                        ]
                    );

                    return $answer;
                },
            ],
            'to' => [
                'question' => 'To Email',
                'default' => 'to@email.com',
                'validators' => function (?string $answer): string {
                    $this->validateAnswer(
                        $answer,
                        [
                            new Assert\NotBlank(),
                            new Assert\Email(),
                        ]
                    );

                    return $answer;
                },
            ],
            'subject' => [
                'question' => 'Subject Text',
                'validators' => function (?string $answer): string {
                    $this->validateAnswer(
                        $answer,
                        [
                            new Assert\NotBlank(),
                        ]
                    );

                    return $answer;
                },
            ],
            'body' => [
                'question' => 'Raw Text (not HTML)',
                'validators' => function (?string $answer): string {
                    $this->validateAnswer(
                        $answer,
                        [
                            new Assert\NotBlank(),
                        ]
                    );

                    return $answer;
                },
            ],
        ];
    }

    private function validateAnswer(?string $answer, array $asserts): void
    {
        $errors = $this->validator->validate($answer, $asserts);
        if ($errors->count()) {
            $error = $errors->get(0);

            throw new \RuntimeException($error->getMessage());
        }
    }
}