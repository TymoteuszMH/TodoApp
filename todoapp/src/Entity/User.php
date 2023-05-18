<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 50)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TodoList::class, orphanRemoval: true)]
    private Collection $todolists;

    public function __construct()
    {
        $this->todolists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, TodoList>
     */
    public function getTodolists(): Collection
    {
        return $this->todolists;
    }

    public function addTodolist(TodoList $todolist): self
    {
        if (!$this->todolists->contains($todolist)) {
            $this->todolists->add($todolist);
            $todolist->setUser($this);
        }

        return $this;
    }

    public function removeTodolist(TodoList $todolist): self
    {
        if ($this->todolists->removeElement($todolist)) {
            // set the owning side to null (unless already changed)
            if ($todolist->getUser() === $this) {
                $todolist->setUser(null);
            }
        }

        return $this;
    }
}
