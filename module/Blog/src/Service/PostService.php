<?php

namespace Blog\Service;

use Blog\Model\Entity\CommentEntity;
use Blog\Model\Entity\PostEntity;
use Blog\Model\Repository\PostRepository;
use Blog\Model\Service\BlogModelService;
use User\Service\AuthService;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepository
     */
    public $postRepository;

    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(BlogModelService $blogModelService, AuthService $authService)
    {
        $this->postRepository = $blogModelService->postRepository();
        $this->authService = $authService;
    }

    public function getAll(): array
    {
        $posts = $this->postRepository->findAll();

        return $posts;
    }

    public function find($postId)
    {
        return $this->postRepository->findOneById($postId);
    }

    public function add(array $data): array
    {
        $post = new PostEntity();
        $post->setTitle($data['title']);
        $post->setContext($data['context']);

        $user = $this->authService->getUser();
        $post->setUser($user);

        $this->postRepository->save($post);

        return [
            'done' => true,
        ];
    }

    public function edit(PostEntity $post, array $data): array
    {
        $post->setTitle($data['title']);
        $post->setContext($data['context']);

        $this->postRepository->save($post);

        return [
            'done' => true,
        ];
    }

    public function delete(PostEntity $post): array
    {
        $this->postRepository->delete($post);

        return [
            'done' => true,
        ];
    }

    public function userHasAccess(PostEntity $post): bool
    {
        $postUserId = $post->getUser()->getId();

        $currentUserId = $this->authService->getUser()->getId();

        return $postUserId == $currentUserId;
    }
}