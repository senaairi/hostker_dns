import MessageBox from 'element-plus/packages/message-box/src/message-box';
import Message from 'element-plus/es/el-message/index';

/**
 * 我也不知道为啥要写这东西，不写就各种爆炸
 */
declare module '@vue/runtime-core' {
    export interface ComponentCustomProperties {
        $message: Message;
        $messageBox: MessageBox;
        $alert: MessageBox.alert;
        $confirm: MessageBox.confirm;
        $prompt: MessageBox.prompt;
        $cookie: {
            setCookie(keyName: keyName, value: string, options?: SetCookieOptions): this;
            getCookie(keyName: keyName): any;
            removeCookie(keyName: keyName, options?: RemoveCookieOptions): this | boolean;
            config(options: CookieConfigOptions): void;
            isCookieAvailable(keyName: keyName): boolean;
            keys(): string[];
        };
    }
}
