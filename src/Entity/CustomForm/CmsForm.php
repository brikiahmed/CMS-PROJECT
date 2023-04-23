<?php

namespace App\Entity\CustomForm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CmsForm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\OneToMany(targetEntity="FieldForm", mappedBy="form", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $fields;

    /**
     * @ORM\OneToMany(targetEntity="ButtonsForm", mappedBy="form", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $buttons;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->buttons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return Collection|FieldForm[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(FieldForm $field): self
    {
        if (!$this->fields->contains($field)) {
            $field->setForm($this);
            $this->fields[] = $field;
        }

        return $this;
    }

    public function removeField(FieldForm $field): self
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getForm() === $this) {
                $field->setForm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ButtonsForm[]
     */
    public function getButtons(): Collection
    {
        return $this->buttons;
    }

    public function addButton(ButtonsForm $field): self
    {
        if (!$this->buttons->contains($field)) {
            $field->setForm($this);
            $this->buttons[] = $field;
        }

        return $this;
    }

    public function removeButton(ButtonsForm $field): self
    {
        if ($this->buttons->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getForm() === $this) {
                $field->setForm(null);
            }
        }

        return $this;
    }
}

