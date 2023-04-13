<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class FieldForm
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
    private $label;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="RegistrationForm", inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $form;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getForm(): ?RegistrationForm
    {
        return $this->form;
    }

    public function setForm(?RegistrationForm $form): self
    {
        $this->form = $form;

        return $this;
    }
}
