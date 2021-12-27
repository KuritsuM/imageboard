<?php


namespace App\Dto;

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

    /**
     * ThreadFormDto constructor.
     * @param String $board
     */
    public function __construct(InputBag $query, string $board)
    {
        $this->theme = $query->get('theme');
        $this->text = $query->get('text');
        $this->board = $board;
    }


}