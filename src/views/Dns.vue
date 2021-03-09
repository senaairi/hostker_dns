<template>
    <div class="bodybox">
        <div class="header_user">
            <el-avatar :size="60" src="image/loginimg.png"></el-avatar>
            <div class="email">{{ HeaderInfo.email }}</div>
        </div>
        <p>随便搓的，能用就行。没有读取域名列表的API，所以 <el-button type="primary" size="mini" @click="Add">添加域名</el-button></p>
        <el-tabs v-model="SelectTabs" type="border-card" editable @edit="TabsEdit">
            <el-tab-pane :key="item" v-for="item in DomainNameList" :label="item" :name="item">
                <Board :DomainName="item"></Board>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script lang="ts">
    import { Vue, Options } from 'vue-class-component';
    import { Account, Seesion, LoginPage } from '@/Common/HostKerAPI';
    import Board from '@/components/dns/Board.vue';

    @Options({
        components: { Board }
    })
    export default class Dns extends Vue {
        name = 'Dns';
        HeaderInfo: Record<any, any> = {
            email: ''
        };

        DomainNameList: any[] = [];
        SelectTabs = '';

        Add () {
            this.$prompt('请输入域名', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消'
            }).then((value: any) => {
                this.DomainNameList.push(value.value);
                this.SelectTabs = value.value;
                // 将域名列表记录到缓存，下次就不用再输入了
                const arrl = this.CookieDomainListGet();
                arrl.push(value.value);
                this.CookieDomainListSave(arrl);
            });
        }

        TabsEdit (targetName: any, action: string, c: any) {
            if (action === 'add') {
                this.Add();
            } else if (action === 'remove') {
                this.DomainNameList.splice(this.DomainNameList.indexOf(targetName), 1);
                // 更新cookie
                const arrl = this.CookieDomainListGet();
                if (arrl.indexOf(targetName) !== -1) arrl.splice(arrl.indexOf(targetName), 1);
                this.CookieDomainListSave(arrl);
                // 如果是关闭当前页，需要重写选中第一页
                if (targetName === this.SelectTabs) this.SelectTabs = arrl[0];
            }
        }

        CookieDomainListGet (): string[] {
            const dl = this.$cookie.getCookie('domainlist');
            if (dl) {
                return dl.split(',');
            } else {
                return [];
            }
        }

        CookieDomainListSave (arr: string[]) {
            this.$cookie.setCookie('domainlist', arr.join(','), { expire: 60 * 60 * 24 * 7 });
        }

        mounted () {
            if (!Account.login) {
                LoginPage();
            } else {
                this.HeaderInfo.email = Seesion.email;
                // 读取cookie内记录的域名列表
                const arrl = this.CookieDomainListGet();
                if (arrl.length > 0) {
                    arrl.forEach((n: string) => {
                        this.DomainNameList.push(n);
                    });
                    this.SelectTabs = arrl[0];
                }
            }
        }
    }
</script>

<style lang="scss">
.bodybox {
    margin: auto;
    width: 100%;
    max-width: 1600px;
}

.header_user {
    height: 80px;

    .el-avatar {
        float: left;
        display: inline-block;
        box-shadow: #8e8e8e 0 0 10px -3px;
    }

    .email {
        float: left;
        display: inline-block;
        line-height: 60px;
        margin-left: 20px;
    }
}
</style>
