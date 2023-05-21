<?php

namespace App\Entity;

use App\Repository\TodoListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoListRepository::class)]
class TodoList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'todolists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'list', targetEntity: TodoElement::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, TodoElement>
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(TodoElement $element): self
    {
        if (!$this->elements->contains($element)) {
            $element->setList($this);
            $this->elements->add($element); 
        }

        return $this;
    }

    public function removeElement(TodoElement $element): self
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getList() === $this) {
                $element->setList(null);
            }
        }

        return $this;
    }
}
