export interface UiSelectorSettings
{
    OPTION_NAME: string,
    CONTAINER_ID: string,
    PLACEMENT_ID: string,
    MULTIPLE: boolean,
    PRESELECTED_ITEMS: object,
    EVENT_HANDLERS: UiSelectorEventHandlers,
    ENTITIES: UiSelectorSettingsEntity[],
}

export interface UiSelectorSettingsEntity{
    id: string,
    options?: object
}

export interface UiSelectorEventHandlers{
    [key: string]: UiSelectorEventHandler,
}

export interface UiSelectorEventHandler{
    namespace: string,
    method: string
}