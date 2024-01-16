<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - LinkItem.php
 * 31.12.2023 19:20
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Menu\Item;

/**
 * @class LinkItem
 * @package ANZ\Bitrix24\BasicPackage\Admin\Menu\Item
 */
class LinkItem extends Item
{
    public function __construct(
        protected string $id,
        protected string $title,
        protected string $url,
        protected int $sort = 1000,
        protected string $icon = ''
    ){
        parent::__construct($id, $title, $sort, $icon);
    }

    /**
     * @param array $data
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $data): static
    {
        $preparedData = static::checkAndPrepareData($data);
        return new static(
            $preparedData['ID'], $preparedData['TITLE'], (string)$data['URL'], $preparedData['SORT'], $preparedData['ICON']
        );
    }

    /**
     * @return array
     */
    public function getCompatibleData(): array
    {
        return [
            'text' => $this->getTitle(),
            'title' => $this->getTitle(),
            'sort' => $this->getSort(),
            'url' => $this->getUrl(),
            'icon' => $this->getIcon()
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        return false;
    }
}