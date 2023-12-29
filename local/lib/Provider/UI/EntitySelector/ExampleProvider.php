<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - ExampleProvider.php
 * 17.12.2023 01:39
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Provider\UI\EntitySelector;

use ANZ\Bitrix24\BasicPackage\Internal\Debug\Logger;
use Bitrix\Crm\Controller\Entity;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\UserTable;
use Bitrix\UI\EntitySelector\BaseProvider;
use Bitrix\UI\EntitySelector\Dialog;
use Bitrix\UI\EntitySelector\Item;
use Bitrix\UI\EntitySelector\SearchQuery;
use Throwable;

/**
 * @class ExampleProvider
 * @package ANZ\Bitrix24\BasicPackage\Provider\UI\EntitySelector
 */
class ExampleProvider extends BaseProvider
{
    const ENTITY_ID = 'custom-user';
    const ENTITY_TYPE = 'project-user';
    const COUNT_LIMIT = 30;

    protected static string|DataManager $dataClass;

    public function __construct()
    {
        parent::__construct();
        static::$dataClass = UserTable::class;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return true;
    }

    protected function getItemEntityId(): string
    {
        return static::ENTITY_ID;
    }

    /**
     * @param array $ids
     * @return \Bitrix\UI\EntitySelector\Item[]
     * @throws \Exception
     */
    public function getItems(array $ids = []): array
    {
        $items = [];

        foreach ($this->getItemsByIds($ids) as $item)
        {
            $items[] = $this->makeItem($item);
        }

        return $items;
    }

    /**
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    private function getItemsByIds(array $ids = []): array
    {
        $query = static::$dataClass::query()
            ->setSelect(['*', 'IS_ONLINE'])
            ->setLimit(self::COUNT_LIMIT);
        if (!empty($ids))
        {
            $query->whereIn('ID', $ids);
        }

        return $query->fetchAll();
    }

    /**
     * @param string $searchString
     * @return array
     * @throws \Exception
     */
    private function getItemsBySearchString(string $searchString): array
    {
        return static::$dataClass::query()
            ->setSelect(['*', 'IS_ONLINE'])
            ->setFilter([
                [
                    'LOGIC' => 'OR',
                    ['%NAME' => $searchString],
                    ['%LAST_NAME' => $searchString],
                    ['%SECOND_NAME' => $searchString],
                ]
            ])
            ->fetchAll();
    }

    /**
     * @param array $item
     * @return \Bitrix\UI\EntitySelector\Item
     */
    private function makeItem(array $item): Item
    {
        $uiItem = new Item([
            'id' => $item['ID'],
            'entityId' => static::ENTITY_ID,
            'entityType' => static::ENTITY_TYPE,
            'title' => $item['NAME'] . ' ' . $item['LAST_NAME'],
            'customData' => [
                'login' => $item['LOGIN'],
                'onlineStatus' => $item['IS_ONLINE'] ? 'online' : 'offline',
            ]
        ]);

        $uiItem->setBadges([
            [
                'title' => $item['IS_ONLINE'] ? 'inline' : 'offline',
                'textColor' => '#000',
                'bgColor' => $item['IS_ONLINE'] ? 'lightgreen' : 'lightgrey',
            ],
            [
                'title' => $item['LOGIN'],
                'textColor' => '#000',
                'bgColor' => 'lightgrey',
            ],
        ]);

        return $uiItem;
    }

    /**
     * @throws \Exception
     */
    public function doSearch(SearchQuery $searchQuery, Dialog $dialog): void
    {
        try
        {
            $items = $this->getItemsBySearchString($searchQuery->getQuery());

            if (!empty($items))
            {
                foreach ($items as $item)
                {
                    $dialog->addItem(
                        $this->makeItem($item)
                    );
                }
            }
        }
        catch(Throwable $e)
        {
            Logger::printToFile(
                date('d.m.Y H:i:s') . " " . self::class . " " . $e->getMessage()
            );
        }
    }

    /**
     * @param \Bitrix\UI\EntitySelector\Dialog $dialog
     * @return void
     * @throws \Exception
     */
    public function fillDialog(Dialog $dialog): void
    {
        $context = $dialog->getContext();
        if (!empty($context)) {
            $recentItems = $dialog->getRecentItems()->getEntityItems($this->getItemEntityId());
            if (count($recentItems) < static::COUNT_LIMIT) {
                $moreItemIds = $this->getRecentItemIds($context);
                foreach ($moreItemIds as $itemId) {
                    $dialog->addRecentItem($this->makeItem($itemId));
                }
            }
        }
    }

    /**
     * @param string $context
     * @return array
     * @throws \Exception
     */
    public function getRecentItemIds(string $context): array
    {
        $ids = [];

        $recentItems = Entity::getRecentlyUsedItems($context, $this->getItemEntityId(), []);

        foreach ($recentItems as $item) {
            $ids[] = $item['ENTITY_ID'];
        }

        if (count($ids) < static::COUNT_LIMIT)
        {
            $moreIds = static::$dataClass::query()
                ->setSelect(['ID'])
                ->setFilter([])
                ->setLimit(static::COUNT_LIMIT - count($ids))
                ->fetchCollection()
                ->getIdList();

            $ids = array_unique(array_merge($ids, $moreIds));
        }

        return $ids;
    }
}