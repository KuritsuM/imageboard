<?php


namespace App\Dto;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Validator\Constraints as Assert;


class ThreadFormDto
{
    /**
     * @Assert\Length(
     *     max = 255,
     *     maxMessage = "Длина темы треды должна быть не более 255 символов."
     *     )
     */
    public $theme;

    /**
     * @Assert\NotBlank
     */
    public string $board;

    /**
     * @Assert\NotBlank
     */
    public $text;

    public $files;


    /**
     * ThreadFormDto constructor.
     * @param String $board
     */
    public function __construct(InputBag $query, string $board, FileBag $files)
    {
        $this->theme = $query->get('thread')['theme'];
        $this->text = $query->get('thread')['text'];
        $this->board = $board;
        $this->files = $files;
    }


}