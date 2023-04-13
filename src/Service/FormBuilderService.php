<?php

// src/Service/FormBuilder.php

namespace App\Service;

use App\Entity\FieldForm;
use App\Entity\RegistrationForm;
use Doctrine\ORM\EntityManagerInterface;

class FormBuilder
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createForm(string $title, array $fields): RegistrationForm
    {
        $form = new RegistrationForm();
        $form->setTitle($title);
        foreach ($fields as $fieldData) {
            $field = new FieldForm();
            $field->setLabel($fieldData['label']);
            $field->setType($fieldData['type']);
            $field->setOptions($fieldData['options'] ?? null);
            $form->addField($field);
        }
        $this->em->persist($form);
        $this->em->flush();
        return $form;
    }

    public function updateForm(RegistrationForm $form, array $fields): RegistrationForm
    {
        foreach ($form->getFields() as $field) {
            $form->removeField($field);
        }

        foreach ($fields as $fieldData) {
            $field = new FieldForm();
            $field->setLabel($fieldData['label']);
            $field->setType($fieldData['type']);
            $field->setOptions($fieldData['options'] ?? null);
            $form->addField($field);
        }

        $this->em->flush();

        return $form;
    }
}
