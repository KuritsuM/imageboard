<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PostFormDto implements \JsonSerializable
{
    public $name;

    public $theme;

    /**
     * @Assert\NotBlank
     */
    public $text;

    public $threadId;

    public $board;

    public $filenames;

    /**
     * PostFormDto constructor.
     * @param $name
     * @param $theme
     * @param $text
     * @param $threadId
     * @param $board
     */
    public function __construct($name, $theme, $text, $board, $threadId, $filenames)
    {
        $this->name = $name;
        $this->theme = $theme;
        $this->text = $text;
        $this->board = $board;
        $this->threadId = $threadId;
        $this->filenames = $filenames;
    }


    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'theme' => $this->theme,
            'text' => $this->text
        ];
    }


}