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

    public $filenames;


    /**
     * ThreadFormDto constructor.
     * @param String $board
     */
    public function __construct(string $theme, string $text, string $board, array $filenames)
    {
        $this->theme = $theme;
        $this->text = $text;
        $this->board = $board;
        $this->filenames = $filenames;
    }


}