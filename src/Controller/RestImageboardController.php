<?php

namespace App\Controller;

use App\Dto\PostFormDto;
use App\Entity\Posts;
use App\Entity\Threads;
use App\Exceptions\ValidateException;
use App\Service\DtoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestImageboardController extends AbstractController
{
    /**
     * @Route("/api/*", methods={"OPTIONS"})
     */
    public function options(Request $request): Response
    {
        $response = new Response();

        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, DELETE, PUT, PATCH');
        $response->headers->set("Access-Control-Allow-Credentials", "true");
        $response->headers->set("Access-Control-Allow-Headers",
            "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/posts/", methods={"OPTIONS"})
     */
    public function options2(Request $request): Response
    {
        $response = new Response();

        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, DELETE, PUT, PATCH');
        $response->headers->set("Access-Control-Allow-Credentials", "true");
        $response->headers->set("Access-Control-Allow-Headers",
            "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/threads/{board}", name="rest_imageboard", methods={ "GET" })
     */
    public function getThreads(Request $request, string $board): Response
    {
        //header("Access-Control-Allow-Origin: *");
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
        //header("Access-Control-Allow-Origin: *");
        $thread = $this->getDoctrine()->getRepository(Threads::class)->find($threadId);

        $posts = $this->getDoctrine()->getRepository(Posts::class)->findByThreadId($threadId);

        $response = [
            'board' => $thread->getBoard()->getName(),
            'thread' => $thread,
            'posts' => $posts
        ];

        return $this->jsonResponse($response);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/posts/", name="create_post", methods={"POST"})
     */
    public function createPost(Request $request, DtoService $dtoService) {
        //header("Access-Control-Allow-Origin: *");

        $request = json_decode($request->getContent(), true, JSON_UNESCAPED_SLASHES);


        //$request->request->get();
        $dto = new PostFormDto($request['name'], $request['theme'], $request['text'], $request['board'], $request['threadId']);

        try {
            $post = $dtoService->makePostFromDto($dto);
            $em = $this->getDoctrine()->getManager();

            $em->persist($post->getThread());
            $em->persist($post);
            $em->flush();

            return $this->jsonResponse($post);
        }
        catch (ValidateException $exception) {
            return $this->jsonResponse(null, 301, $exception->getMessage());
        }
        catch (\Exception $exception) {
            return $this->jsonResponse($exception);
        }

        //return $this->jsonResponse(['request' => $dto]);
    }

    private function jsonResponse($data, $status = 200, $errorMsg = null) {
        if ($status == 200) {
            $jsonArray = [
                'status' => $status,
                'data' => $data,
            ];

            return $this->json($jsonArray, 200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS',
                'Access-Control-Max-Age' => 0
            ]);
        }
        else {
            $jsonArray = [
                'status' => $status,
                'errorCode' => $errorMsg,
                //'data' => $data,
            ];

            return $this->json($jsonArray, 200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS',
                'Access-Control-Max-Age' => 0
            ]);
        }
    }
}
