<?php

namespace App\Entity\CustomForm;

use App\Entity\CustomForm\CmsForm;
use App\Repository\ButtonsFormRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ButtonsFormRepository::class)
 */
class ButtonsForm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="CmsForm", inversedBy="buttons")
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

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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
