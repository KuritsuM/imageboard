<?php

namespace App\Controller;

use App\Dto\PostFormDto;
use App\Dto\ThreadFormDto;
use App\Entity\Posts;
use App\Entity\Threads;
use App\Exceptions\ValidateException;
use App\Service\DtoService;
use App\Service\UserService;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class RestImageboardController extends AbstractController
{


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
            'posts' => $posts,
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
        $dto = new PostFormDto(
            $request['name'],
            $request['theme'],
            $request['text'],
            $request['board'],
            $request['threadId'],
            $request['filenames']
        );

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

    /**
     * @param Request $request
     * @param DtoService $dtoService
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("api/threads/", name="create_thread", methods={"POST"})
     */
    public function createThread(Request $request, DtoService $dtoService) {
        $request = json_decode($request->getContent(), true, JSON_UNESCAPED_SLASHES);

        try {
            $thread = $dtoService->makeThreadFromDto(new ThreadFormDto(
                $request['theme'],
                $request['text'],
                $request['board'],
                $request['filenames']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($thread);
            $em->flush();

            return $this->jsonResponse($thread);
        } catch (ValidateException $exception) {
            return $this->jsonResponse($exception);
        }
    }

    /**
     * @Route("/file/{file}", name="get_file", methods={"GET"})
     */
    public function getFile(string $file) {
        return $this->file($this->getParameter('uploads_directory').$file,
            $file,
            ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("api/file/", name="upload_files", methods={"POST"})
     */
    public function uploadFiles(Request $request, DtoService $dtoService) {

        $filesNames = [];
        for ($i = 0; $i < $request->request->count(); ++$i) {
            $filesNames[$i] = $request->request->get($i);
        }

        $i = 0;
        $newFilenames = [];
        foreach ($request->files as $file) {
            $newFilename = $dtoService->moveFiles($file, $this->getParameter('uploads_directory'));

            //$newFilenames[$filesNames[$i]] = $newFilename;
            $newFilenames[$i] = $newFilename;
        }

        return $this->jsonResponse(['filenames' => $newFilenames]);
    }

    /**
     * @Route("/api/posts/delete/{id}", name="delete_posts", methods={"DELETE"})
     */
    public function deletePost(Request $request, UserService $userService, $id) {
        $user = $userService->getUser($request->headers->get('authorization'));

        $postsToDelete = $request->request->get('posts');

        $em = $this->getDoctrine()->getManager();
        $postsRepository = $em->getRepository(Posts::class);

        $post = $postsRepository->find($id);

        if ($userService->isCanDeleteCurrentPost($post)) {
            $em->remove($post);
        };

        $em->flush();

        return $this->jsonResponse($post);
    }

    /**
     * @Route("/api/getuser", name="get_user", methods={"GET"})
     */
    public function getUserInfo(Request $request, UserService $userService) {
        $user = $userService->getUser($request->headers->get('authorization'));

        return $this->jsonResponse(['username' => $user->getUserIdentifier(), 'roles' => $user->getRoles()]);
    }

    /**
     * @param $data
     * @param int $status
     * @param null $errorMsg
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function jsonResponse($data, $status = 200, $errorMsg = null) {
        if ($status == 200) {
            $jsonArray = [
                'status' => $status,
                'data' => $data,
            ];

            return $this->json($jsonArray, 200);
        }
        else {
            $jsonArray = [
                'status' => $status,
                'errorCode' => $errorMsg,
                //'data' => $data,
            ];

            return $this->json($jsonArray, 200);
        }
    }
}
