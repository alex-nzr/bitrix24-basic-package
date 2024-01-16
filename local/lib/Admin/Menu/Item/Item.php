<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Item.php
 * 31.12.2023 19:12
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Menu\Item;

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Menu\IMenuItem;
use Bitrix\Main\Security\Random;
use Exception;

/**
 * @class Item
 * @package ANZ\Bitrix24\BasicPackage\Admin\Menu\Item
 */
abstract class Item implements IMenuItem
{
    const DEFAULT_SORT = 500;

    public function __construct(
        protected string $id,
        protected string $title,
        protected int $sort = self::DEFAULT_SORT,
        protected string $icon = ''
    ){
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected static function checkAndPrepareData(array $data): array
    {
        if (!key_exists('TITLE', $data) || empty($data['TITLE']))
        {
            throw new Exception('TITLE can not be empty');
        }

        return [
            'ID' => (!key_exists('ID', $data) || empty($data['ID']) || is_numeric($data['ID']))
                    ? $data['ID'] . Random::getString(10)
                    : (string)$data['ID'],
            'TITLE' => (string)$data['TITLE'],
            'SORT' => (key_exists('SORT', $data)) ? (int)$data['SORT'] : static::DEFAULT_SORT,
            'ICON' => (key_exists('ICON', $data)) ? (string)$data['ICON'] : ''
        ];
    }

    abstract public static function fromArray(array $data): static;
    abstract public function getCompatibleData(): array;
    abstract public function isParent(): bool;
}