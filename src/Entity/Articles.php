<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
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
    private $updated_on;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="text")
     */
    private $short_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $article_picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $other_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tags;

    /**
     * @ORM\Column(type="date")
     */
    private $created_on;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="articles")
     */
    private $articles_categories;

    /**
     * @ORM\ManyToMany(targetEntity=SubCategory::class, inversedBy="articles")
     */
    private $articles_sub_category;

    public function __construct()
    {
        $this->articles_categories = new ArrayCollection();
        $this->articles_sub_category = new ArrayCollection();
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

    


    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updated_on;
    }

    public function setUpdatedOn(\DateTimeInterface $updated_on): self
    {
        $this->updated_on = $updated_on;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getArticlePicture(): ?string
    {
        return $this->article_picture;
    }

    public function setArticlePicture(string $article_picture): self
    {
        $this->article_picture = $article_picture;

        return $this;
    }

    public function getOtherFile(): ?string
    {
        return $this->other_file;
    }

    public function setOtherFile(string $other_file): self
    {
        $this->other_file = $other_file;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

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

    /**
     * @return Collection<int, Categories>
     */
    public function getArticlesCategories(): Collection
    {
        return $this->articles_categories;
    }

    public function addArticlesCategory(Categories $articlesCategory): self
    {
        if (!$this->articles_categories->contains($articlesCategory)) {
            $this->articles_categories[] = $articlesCategory;
        }

        return $this;
    }

    public function removeArticlesCategory(Categories $articlesCategory): self
    {
        $this->articles_categories->removeElement($articlesCategory);

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getArticlesSubCategory(): Collection
    {
        return $this->articles_sub_category;
    }

    public function addArticlesSubCategory(SubCategory $articlesSubCategory): self
    {
        if (!$this->articles_sub_category->contains($articlesSubCategory)) {
            $this->articles_sub_category[] = $articlesSubCategory;
        }

        return $this;
    }

    public function removeArticlesSubCategory(SubCategory $articlesSubCategory): self
    {
        $this->articles_sub_category->removeElement($articlesSubCategory);

        return $this;
    }

    




}


