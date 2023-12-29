import {Options} from "./App/Options"
import "../styles/main.css";

declare global {
    interface Window {
        BX: any;
    }
}

if (window.BX)
{
    window.BX.ready(() => {
        try
        {
            const AdminApp = window.BX.namespace('Project.Admin.UI');
            const settings = window.BX['Extension'] ? window.BX['Extension'].getSettings() : {};
            AdminApp.Options = new Options(settings);
        }
        catch (e)
        {
            console.error('AdminApp.Options error', e)
        }
    });
}
else
{
    console.error('BX object not found');
}
