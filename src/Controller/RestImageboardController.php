<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Threads;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestImageboardController extends AbstractController
{
    /**
     * @Route("/api/threads/{board}", name="rest_imageboard", methods={ "GET" })
     */
    public function getThreads(Request $request, string $board): Response
    {
        header("Access-Control-Allow-Origin: *");
        $threads = $this->getDoctrine()->getRepository(Threads::class)->findByBoard($board);

        $response = [
            'board' => $board,
            'threads' => $threads
        ];

        return $this->jsonResponse($response);
    }

    /**
     * @Route("/api/posts/{threadId}", name="get_thread", methods={"GET"})
     */
    public function getThread(Request $request, string $threadId) {
        header("Access-Control-Allow-Origin: *");
        $thread = $this->getDoctrine()->getRepository(Threads::class)->find($threadId);

        $posts = $this->getDoctrine()->getRepository(Posts::class)->findByThreadId($threadId);

        $response = [
            'board' => $thread->getBoard()->getName(),
            'thread' => $thread,
            'posts' => $posts
        ];

        return $this->jsonResponse($response);
    }

    private function jsonResponse($data, $status = 200, $errorMsg = null) {
        if ($status == 200) {
            $jsonArray = [
                'status' => $status,
                'data' => $data,
            ];

            return $this->json($jsonArray);
        }
        else {
            $jsonArray = [
                'status' => $status,
                'errorCode' => $errorMsg,
                'data' => $data,
            ];

            return $this->json($jsonArray);
        }
    }
}
