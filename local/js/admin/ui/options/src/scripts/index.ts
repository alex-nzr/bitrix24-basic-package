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
            const AdminUI = window.BX.namespace('Anz.Admin.UI');
            const settings = window.BX['Extension'] ? window.BX['Extension'].getSettings() : {};
            AdminUI.Options = new Options(settings);
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
