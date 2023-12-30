import {UiSelector} from "./Options/UiSelector";
import {UiSelectorSettings} from "../../types/Settings";

export class Options
{
    private settings: object;

    constructor(settings: object)
    {
        this.settings = settings;
    }

    public createUiSelectorOption(params: UiSelectorSettings)
    {
        return new UiSelector(params);
    }
}