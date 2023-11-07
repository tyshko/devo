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

        if (empty($data['title'])) {
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
    public function deleteTodo(int $id): JsonResponse
    {
        return $this->json(['message' => 'Todo deleted']);
    }

    #[Route('/api/todos/{id}/complete', name: 'complete_todo', methods: ['PATCH'])]
    public function completeTodo(int $id): JsonResponse
    {
        return $this->json(['message' => 'Todo completed']);
    }

    #[Route('/api/todos/{id}', name: 'update_todo', methods: ['PUT'])]
    public function updateTodo(Request $request, int $id): JsonResponse
    {
        return $this->json(['message' => 'Todo updated']);
    }

    #[Route('/api/todos', name: 'list_todos', methods: ['GET'])]
    public function listTodos(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $todos = $this->toDoRepository->findAllTodos($page, $limit);

        return $this->json([
            'data' => $todos,
            'page' => $page,
            'limit' => $limit
        ], Response::HTTP_OK);
    }
}
