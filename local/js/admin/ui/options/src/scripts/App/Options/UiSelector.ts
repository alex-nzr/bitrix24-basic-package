import {UiSelectorSettings, UiSelectorSettingsEntity} from "../../../types/Settings";

export class UiSelector
{
    private readonly fieldName: string;
    private readonly isMultiple: boolean;
    private readonly entities: UiSelectorSettingsEntity[];
    private readonly preselectedItems: object;
    private readonly placementId: string;
    private readonly containerId: string;
    private readonly eventHandlers: object;
    private tagSelector: object;

    constructor(params: UiSelectorSettings)
    {
        this.fieldName = params.OPTION_NAME;
        this.placementId = params.PLACEMENT_ID;
        this.containerId = params.CONTAINER_ID;
        this.isMultiple = params.MULTIPLE;
        this.entities = params.ENTITIES;
        this.preselectedItems = params.PRESELECTED_ITEMS;
        this.eventHandlers = params.EVENT_HANDLERS;

        this.init();
    }

    public init(): void
    {
        const BX = window.BX;

        const selectorParams = {
            id: `${this.fieldName}_selector`,
            multiple: this.isMultiple,
            addButtonCaption: 'Select',
            addButtonCaptionMore: this.isMultiple ? 'More' : 'Select',
            dialogOptions: {
                context: `${this.fieldName}_CONTEXT`,
                entities: this.entities.map((entity: UiSelectorSettingsEntity) => {
                    return {
                        id           : entity.id,
                        options      : entity.options ?? {},
                        dynamicLoad  : true,
                        dynamicSearch: true,
                    };
                }),
                preload: true,
                preselectedItems: this.preselectedItems,
                hideOnSelect: !this.isMultiple,
                hideByEsc: true,
                searchOptions: {
                    allowCreateItem: false,
                    footerOptions: {
                        label: ''
                    }
                },
                events: {}
            },
            events: {
                onBeforeTagAdd: (event) => {
                    if (typeof this.eventHandlers.onBeforeTagAdd === 'function')
                    {
                        this.eventHandlers.onBeforeTagAdd(event);
                    }
                },
                onBeforeTagRemove: (event) => {
                    if (typeof this.eventHandlers.onBeforeTagRemove === 'function')
                    {
                        this.eventHandlers.onBeforeTagRemove(event);
                    }
                },
                onAfterTagAdd: (event) => {
                    const container = BX(this.containerId);
                    if (container)
                    {
                        const inputId = `${this.fieldName}_input`;
                        let input = BX(inputId);
                        if (input)
                        {
                            input.value = this.getNewFieldValue();
                        }
                        else
                        {
                            input = BX.create('input', {
                                props: {
                                    id: inputId,
                                    value: this.getNewFieldValue(),
                                    type: "hidden",
                                    name: this.fieldName,
                                },
                            });
                            container.append(input);
                        }
                    }
                    if (typeof this.eventHandlers.onAfterTagAdd === 'function')
                    {
                        this.eventHandlers.onAfterTagAdd(event);
                    }
                },
                onAfterTagRemove: (event) => {
                    //const {tag} = event.getData();
                    const container = BX(this.containerId);
                    if (container)
                    {
                        const inputId = `${this.fieldName}_input`;
                        const input = BX(inputId);
                        input && (input.value = this.getNewFieldValue());
                    }
                    if (typeof this.eventHandlers.onAfterTagRemove === 'function')
                    {
                        this.eventHandlers.onAfterTagRemove(event);
                    }
                },
            }
        }

        this.tagSelector = new BX.UI['EntitySelector']['TagSelector'](selectorParams);
        this.tagSelector['renderTo'](BX(this.placementId));
    }

    private getNewFieldValue(): string
    {
        try
        {
            let result;
            const tags = this.tagSelector['getTags']();
            if (this.isMultiple)
            {
                result = [];
                tags.length && tags.forEach(tag => {
                    result.push({
                        ID: tag['getId']() ?? 0,
                        TITLE: tag['getTitle']() ?? '',
                        AVATAR: tag['getAvatar']() ?? '',
                    });
                });
            }
            else
            {
                result = {};
                if (tags.length)
                {
                    result = {
                        ID: tags[0]['getId']() ?? 0,
                        TITLE: tags[0]['getTitle']() ?? '',
                        AVATAR: tags[0]['getAvatar']() ?? '',
                    };
                }
            }

            return JSON.stringify(result);
        }
        catch (e)
        {
            console.error(e);
            return '{}';
        }
    }
}