<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiController extends AbstractController
{
    private ToDoRepository $toDoRepository;

    public function __construct(ToDoRepository $toDoRepository)
    {
        $this->toDoRepository = $toDoRepository;
    }

    #[Route('/api/todos', name: 'add_todo', methods: ['POST'])]
    public function addTodo(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || trim($data['title']) === '') {
            return $this->json(['message' => 'The title is required'], Response::HTTP_BAD_REQUEST);
        }

        $todo = new ToDo();
        $todo->setTitle($data['title']);
        $todo->setStatus('new'); // Default status
        $todo->setIsCompleted(false);
        $todo->setViewCount(0);

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json(['message' => 'Todo added', 'todo' => [
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
            'status' => $todo->getStatus(),
            'isCompleted' => $todo->getIsCompleted(),
            'viewCount' => $todo->getViewCount(),
        ]], Response::HTTP_CREATED);
    }

    #[Route('/api/todos/{id}', name: 'delete_todo', methods: ['DELETE'])]
    public function deleteTodo(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $todo = $this->toDoRepository->find($id);

        if (!$todo) {
            return $this->json(['message' => 'Todo not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->json(['message' => 'Todo deleted'], Response::HTTP_OK);
    }


    #[Route('/api/todos/{id}/complete', name: 'complete_todo', methods: ['PATCH'])]
    public function completeTodo(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $todo = $this->toDoRepository->find($id);

        if (!$todo) {
            return $this->json(['message' => 'Todo not found'], Response::HTTP_NOT_FOUND);
        }

        if ($todo->getIsCompleted()) {
            return $this->json(['message' => 'Todo already completed'], Response::HTTP_BAD_REQUEST);
        }

        $todo->setIsCompleted(true);
        $todo->setStatus('completed');

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json(['message' => 'Todo marked as completed'], Response::HTTP_OK);
    }

    #[Route('/api/todos/{id}', name: 'update_todo', methods: ['PUT'])]
    public function updateTodoTitle(Request $request, int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || trim($data['title']) === '') {
            return $this->json(['message' => 'The title is required'], Response::HTTP_BAD_REQUEST);
        }

        $todo = $this->toDoRepository->find($id);

        if (!$todo) {
            return $this->json(['message' => 'Todo not found'], Response::HTTP_NOT_FOUND);
        }

        $todo->setTitle($data['title']);

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json([
            'message' => 'Todo updated',
            'todo' => [
                'title' => $todo->getTitle(),
            ]
        ], Response::HTTP_OK);
    }



    #[Route('/api/todos', name: 'list_todos', methods: ['GET'])]
    public function listTodos(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $todosArray = $this->toDoRepository->findAllTodos($page, $limit);

        return $this->json([
            'data' => $todosArray,
            'page' => $page,
            'limit' => $limit
        ], Response::HTTP_OK);
    }

}
