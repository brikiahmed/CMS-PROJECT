<?php

// src/Service/FormBuilder.php

namespace App\Service;

use App\Entity\CustomForm\ButtonsForm;
use App\Entity\CustomForm\CmsForm;
use App\Entity\CustomForm\FieldForm;
use App\Repository\ButtonsFormRepository;
use App\Repository\FieldFormRepository;
use Doctrine\ORM\EntityManagerInterface;

class FormBuilderService
{
    private $em;
    private $buttonsFormRepository;
    private $fieldFormRepository;

    public function __construct(EntityManagerInterface $em, ButtonsFormRepository $buttonsFormRepository, FieldFormRepository $fieldFormRepository)
    {
        $this->em = $em;
        $this->buttonsFormRepository = $buttonsFormRepository;
        $this->fieldFormRepository = $fieldFormRepository;
    }

    /**
     * createFieldAndButtonForExistingForm
     * @param CmsForm $form
     * @param $fields
     */
    public function createFieldAndButtonForExistingForm(CmsForm $form, $fields) {
        if (isset($fields['fields'])) {
            foreach ($fields['fields'] as $fieldData) {
                $field = new FieldForm();
                $field->setForm($form);
                $field->setLabel($fieldData['label']);
                $field->setType($fieldData['type']);
                $field->setIsRequired($fieldData['isRequired'] ?? 0);
                $this->fieldFormRepository->add($field , true);
            }
        }
        if(isset($fields['buttons'])) {
            foreach ($fields['buttons'] as $button) {
                $field = new ButtonsForm();
                $field->setForm($form);
                $field->setLabel($button['label']);
                $field->setType($button['type']);
                $this->buttonsFormRepository->add($field, true);
            }
        }
    }

    /**
     * Add new form
     * @param string $title
     * @param $isEnabled
     * @param array $fields
     * @return CmsForm
     */
    public function createForm(string $title, $isEnabled ,array $fields): CmsForm
    {
        $form = new CmsForm();
        $form->setTitle($title);
        $form->setIsEnabled($isEnabled);
        if (isset($fields['fields'])) {
            foreach ($fields['fields'] as $fieldData) {
                $field = new FieldForm();
                $field->setLabel($fieldData['label']);
                $field->setType($fieldData['type']);
                $field->setIsRequired($fieldData['isRequired'] ?? 0);
                $form->addField($field);
            }
        }

        if (isset($fields['buttons'])) {
            foreach ($fields['buttons'] as $button) {
                $field = new ButtonsForm();
                $field->setLabel($button['label']);
                $field->setType($button['type']);
                $form->addButton($field);
            }
        }

        $this->em->persist($form);
        $this->em->flush();
        return $form;
    }
}
