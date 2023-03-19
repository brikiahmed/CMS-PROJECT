<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $updated;

    /**
     * @ORM\Column(type="date")
     */
    private $created_on;

    /**
     * @ORM\Column(type="date")
     */
    private $updated_on;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    
    private $category_picture;

    /**
     * @ORM\Column(type="text")
     */
    private $short_description;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, mappedBy="articles_categories")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=SubCategory::class, mappedBy="categories")
     */
    private $categories_sub;

    public function __construct()
    {
        $this->categories_sub = new ArrayCollection();
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUpdated(): ?string
    {
        return $this->updated;
    }

    public function setUpdated(string $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->created_on;
    }

    public function setCreatedOn(\DateTimeInterface $created_on): self
    {
        $this->created_on = $created_on;

        return $this;
    }

    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updated_on;
    }

    public function setUpdatedOn(\DateTimeInterface $updated_on): self
    {
        $this->updated_on = $updated_on;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    

    public function getCategoryPicture(): ?string
    {
        return $this->category_picture;
    }

    public function setCategoryPicture(string $category_picture): self
    {
        $this->category_picture = $category_picture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles?: new ArrayCollection();
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addArticlesCategory($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeArticlesCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getCategoriesSub(): Collection
    {
        return $this->categories_sub;
    }

    public function addCategoriesSub(SubCategory $categoriesSub): self
    {
        if (!$this->categories_sub->contains($categoriesSub)) {
            $this->categories_sub[] = $categoriesSub;
            $categoriesSub->setCategories($this);
        }

        return $this;
    }

    public function removeCategoriesSub(SubCategory $categoriesSub): self
    {
        if ($this->categories_sub->removeElement($categoriesSub)) {
            // set the owning side to null (unless already changed)
            if ($categoriesSub->getCategories() === $this) {
                $categoriesSub->setCategories(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }




}
