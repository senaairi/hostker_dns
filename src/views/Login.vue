<template>
    <div>
        <div class="login-box">
            <div class="login-box-title">请登录</div>
            <form>
                <p><el-input v-model="Form.Email" cc-number="1" name="username" placeholder="主机壳帐号"></el-input></p>
                <p><el-input v-model="Form.Token" cc-number="2" name="current-password" type="password" placeholder="API令牌"></el-input></p>
            </form>
            <p><el-checkbox v-model="Form.long">保存信息</el-checkbox></p>
            <p><el-alert title="勾选保存信息Cookie会在7天内有效，否则将在关闭浏览器后失效" type="info" :closable="false"></el-alert></p>
            <p><el-alert title="本项目纯前端实现，会话保存使用浏览器Cookie" type="info" :closable="false"></el-alert></p>
            <el-row type="flex" justify="center">
                <el-button type="primary" size="medium" @click="LoginStart">&nbsp;&nbsp;&nbsp;&nbsp;登录&nbsp;&nbsp;&nbsp;&nbsp;</el-button>
            </el-row>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue } from 'vue-class-component';
    import { Account, Seesion, GetData } from '@/Common/HostKerAPI';

    export default class Login extends Vue {
        name = 'Login';
        Form = {
            Email: '',
            Token: '',
            long: false
        };

        mounted () {
            const c_email = this.$cookie.getCookie('_email');
            const c_token = this.$cookie.getCookie('_token');
            if (c_email && c_token) {
                Seesion.email = c_email;
                Seesion.token = c_token;
                this.LoadAccountInfo();
            }
        }

        LoginStart (): boolean | void {
            if (this.Form.Email.length === 0) {
                this.$message.error('请填写帐号');
                return false;
            }
            if (this.Form.Token.length === 0) {
                this.$message.error('请填写token');
                return false;
            }
            Seesion.email = this.Form.Email;
            Seesion.token = this.Form.Token;
            this.$cookie.setCookie('_email', this.Form.Email, { expire: this.Form.long ? 60 * 60 * 24 * 7 : 0 });
            this.$cookie.setCookie('_token', this.Form.Token, { expire: this.Form.long ? 60 * 60 * 24 * 7 : 0 });
            this.LoadAccountInfo();
        }

        LoadAccountInfo () {
            GetData('quota', new FormData(), (data: any) => {
                if (data.success) {
                    Account.login = true;
                    Account.level = data.level;
                    Account.suspend = data.suspend;
                    Account.suspendReason = data.suspendReason;
                    this.GoBack();
                } else {
                    this.$message.error('登录失败！' + data.errorMessage);
                }
            });
        }

        GoBack () {
            this.$message({ message: '登录成功，正在跳转...', type: 'success' });
            if (Object.hasOwnProperty.call(this.$route.query, 'backurl')) {
                setTimeout(() => {
                    location.href = window.atob(this.$route.query.backurl as string);
                }, 1000);
            } else {
                setTimeout(() => {
                    location.href = '#/dns';
                }, 1000);
            }
        }
    }
</script>

<style scoped>
body {
    background-color: #f3f3f3;
}

.login-box {
    max-width: 350px;
    margin: 20px auto;
    border-radius: 5px;
    padding: 40px;
    box-shadow: 0 0 10px -5px #7b7b7b;
    background-color: #ffffff;
    transition: 0.5s;
}

.login-box:hover {
    box-shadow: 0 4px 10px -3px #7b7b7b;
}

.login-box-title {
    font-size: 24px;
    font-weight: 500;
    color: #585858;
    margin-bottom: 40px;
    text-align: center;
}
</style>
