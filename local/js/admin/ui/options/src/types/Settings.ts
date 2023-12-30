export interface UiSelectorSettings
{
    OPTION_NAME: string,
    CONTAINER_ID: string,
    PLACEMENT_ID: string,
    MULTIPLE: boolean,
    PRESELECTED_ITEMS: object,
    EVENT_HANDLERS: object,
    ENTITIES: UiSelectorSettingsEntity[],
}

export interface UiSelectorSettingsEntity{
    id: string,
    options?: object
}