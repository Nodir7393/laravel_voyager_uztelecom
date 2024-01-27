<?php

namespace App\Structures;

/**
 * Class CarModelFilterData
 */
class ApplicationFilterData
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $sort;

    /**
     * @var int
     */
    private $perPage = 10;

    /**
     * @param array $input
     * @return ApplicationFilterData
     */
    public static function fill(array $input): ApplicationFilterData
    {
        return new self(
            $input['id'] ?? null,
            $input['name'] ?? null,
            $input['sort'] ?? null,
            $input['perPage'] ?? null
        );
    }

    public function __construct(
        ?int $id,
        ?string $name,
        ?string $sort,
        ?int $perPage
    ) {
        $this->setId($id ?? null);
        $this->setName($name ?? null);
        $this->setSort($sort ?? null);
        $this->setPerPage($perPage ?? $this->perPage);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'sort' => $this->getSort(),
            'perPage' => $this->getPerPage(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort(?string $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = ($perPage >= 1) ? $perPage : $this->perPage;
    }
}
