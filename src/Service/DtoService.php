<?php


namespace App\Service;


use App\Dto\ThreadFormDto;
use App\Entity\Threads;
use App\Exceptions\ValidateException;
use App\Repository\BoardsRepository;
use App\Repository\ThreadsRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoService
{
    private ValidatorInterface $validator;

    private BoardsRepository $boardsRepository;

    /**
     * DtoService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator, BoardsRepository $boardsRepository)
    {
        $this->validator = $validator;
        $this->boardsRepository = $boardsRepository;
    }

    public function makeThreadFromDto(ThreadFormDto $dto, $directory) {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ValidateException((string) $errors);
        }


        $file = $dto->files->get('thread')['file1'];

        //dd($file);

        $filename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();

        $file->move(
            $directory,
            $filename
        );


        $thread = new Threads();
        $thread->setCreatedAt(new \DateTimeImmutable());
        $thread->setText($dto->text);
        $thread->setBoard($this->boardsRepository->findByBoardName($dto->board));
        $thread->setTheme($dto->theme);
        $thread->setFile1($filename);

        return $thread;
    }
}