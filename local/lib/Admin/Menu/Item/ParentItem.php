<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - ParentItem.php
 * 31.12.2023 19:21
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Menu\Item;

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Menu\IMenuItem;

/**
 * @class ParentItem
 * @package ANZ\Bitrix24\BasicPackage\Admin\Menu\Item
 */
class ParentItem extends Item
{
    protected array $itemsData = [];
    protected array $items = [];

    /**
     * ParentItem constructor
     * @param string $id
     * @param string $title
     * @param array $itemsData
     * @param int|null $sort
     * @param string $icon
     */
    public function __construct(string $id, string $title, array $itemsData = [], ?int $sort = 500, string $icon = '')
    {
        parent::__construct($id, $title, $sort, $icon);
        $this->itemsData = $itemsData;
    }

    /**
     * @param array $data
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $data): static
    {
        $preparedData = static::checkAndPrepareData($data);
        $items = is_array($data['ITEMS']) ? $data['ITEMS'] : [];
        return new static(
            $preparedData['ID'], $preparedData['TITLE'], $items, $preparedData['SORT'], $preparedData['ICON']
        );
    }

    /**
     * @return array
     */
    public function getCompatibleData(): array
    {
        return [
            'menu_id' => $this->getId(),
            'text' => $this->getTitle(),
            'title' => $this->getTitle(),
            'sort' => $this->getSort(),
            'items_id' => $this->getItemsId(),
            'icon' => $this->getIcon(),
            'items' => array_map(fn(IMenuItem $item) => $item->getCompatibleData(), $this->getItems()),
        ];
    }

    /**
     * @param \ANZ\Bitrix24\BasicPackage\Internal\Contract\Menu\IMenuItem $item
     * @return void
     */
    public function addChildItem(IMenuItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return IMenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getItemsId(): string
    {
        return $this->id . '_items';
    }

    /**
     * @return array
     */
    public function getItemsData(): array
    {
        return $this->itemsData;
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        return true;
    }
}