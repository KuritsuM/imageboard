<?php

namespace App\Controller;

use App\Dto\ThreadFormDto;
use App\Entity\Boards;
use App\Entity\Threads;
use App\Exceptions\ValidateException;
use App\Form\BoardsType;
use App\Repository\BoardsRepository;
use App\Repository\ThreadsRepository;
use App\Service\DtoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageboardController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(BoardsRepository $boardsRepository): Response
    {
        $boards = $boardsRepository->findAll();

        return $this->render('imageboard/show_boards.html.twig', [
            'boards' => $boards
        ]);
    }

    /**
     * @Route("/new_board/", name="new_board")
     */
    public function makeNewBoard(Request $request) {
        $board = new Boards();
        $boardForm = $this->createForm(BoardsType::class, $board);

        $boardForm->handleRequest($request);

        $error = null;
        if ($boardForm->isSubmitted() && $boardForm->isValid() && !$boardForm->isEmpty()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($board);

            try {
                $em->flush();
            }
            catch (\Exception $e) {
                $error = 'Данная борда уже существует';
            }
        }

        return $this->render('board/new_board.html.twig', [
            'board_form' => $boardForm->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/{board}", name="board_main")
     */
    public function boards(Request $request, String $board, ThreadsRepository $threadsRepository) {
        $threads = $threadsRepository->findByBoard($board);

        foreach ($threads as $thread) {
            $thread->setFormatedCreatedAt($thread->getCreatedAt()->format('d/m/Y H:i:s'));
        }

        return $this->render('board/board.html.twig', [
            'boardName' => $board,
            'threads' => $threads
        ]);
    }

    /**
     * @Route("/temp/{board}", name="createThread", methods={ "POST" })
     */
    public function createThread(Request $request, $board, DtoService $dtoService) {
        try {
            $thread = $dtoService->makeThreadFromDto(new ThreadFormDto($request->request, $board));

            $em = $this->getDoctrine()->getManager();

            $em->persist($thread);

            $em->flush();

            return $this->redirectToRoute('board_name', [ 'board' => $board]);
        } catch (ValidateException $e) {
            $this->json(array('ok'=>'notOk'));
        } catch (\Exception $e) {
            $this->json(array('ok'=>'notOk'));
        }
    }
}
