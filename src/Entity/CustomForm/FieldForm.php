<?php

namespace App\Entity\CustomForm;

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
     * @ORM\Column(type="boolean")
     */
    private $isRequired;

    /**
     * @ORM\ManyToOne(targetEntity="CmsForm", inversedBy="fields")
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

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function getForm(): ?CmsForm
    {
        return $this->form;
    }

    public function setForm(?CmsForm $form): self
    {
        $this->form = $form;

        return $this;
    }
}
