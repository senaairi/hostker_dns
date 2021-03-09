import axios from 'axios';

const APIURL = 'https://lihz.org/api/?path=' + encodeURI('/api/');
const Account: Record<string, any> = {
    login: false,
    level: 0,
    suspend: 0,
    suspendReason: ''
};
const Seesion = {
    email: '',
    token: ''
};

function GetData (url: string, post: FormData, callback: any) {
    post.append('email', Seesion.email);
    post.append('token', Seesion.token);
    axios.post(APIURL + url, post).then((Res) => {
        callback(Res.data);
    });
}

function LoginPage () {
    location.href = '#/login?backurl=' + window.btoa(location.href);
}

export { Account, Seesion, GetData, LoginPage, APIURL };
