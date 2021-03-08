<template>
    <div>
        <p>
            <el-button size="mini" @click="AddDrawerOpen=true" type="primary">新增解析</el-button>
        </p>
        <el-table :data="RecordList" style="width: 100%" border>
            <el-table-column prop="id" label="ID" width="100" :sortable="true"></el-table-column>
            <el-table-column prop="header" label="主机头" width="180" :sortable="true"></el-table-column>
            <el-table-column
                prop="type"
                label="类型"
                width="80"
                :filters="[
                    {text: 'CDN', value: 'CDN'},
                    {text: 'A', value: 'A'},
                    {text: 'AAAA', value: 'AAAA'},
                    {text: 'CNAME', value: 'CNAME'},
                    {text: 'TXT', value: 'TXT'},
                    {text: 'MX', value: 'MX'}
                ]"
                :filter-method="FilterHandler"
            ></el-table-column>
            <el-table-column prop="ttl" label="TTL" width="60"></el-table-column>
            <el-table-column prop="priority" label="优先级" width="65"></el-table-column>
            <el-table-column prop="data" label="解析内容"></el-table-column>
            <el-table-column label="操作" width="150">
                <template #default="scope">
                    <el-button size="mini" @click="Edit(scope.$index)" type="primary">编辑</el-button>
                    <el-popconfirm confirmButtonText='好的' cancelButtonText='不用了' icon="el-icon-info" @confirm="Delete(scope.$index)" iconColor="red" title="确定删除吗？">
                        <template #reference>
                            <el-button size="mini" type="danger">删除</el-button>
                        </template>
                    </el-popconfirm>
                </template>
            </el-table-column>
        </el-table>
        <!-- 新增 -->
        <el-drawer custom-class="edit_drawer" title="新增" v-model="AddDrawerOpen" direction="rtl" :before-close="AddDrawerClose">
            <div class="edit_drawer_content">
                <el-form label-width="100px">
                    <el-form-item label="主机头">
                        <el-input v-model="AddDrawerData.header" placeholder="根域名解析填@">
                            <template #append>.{{DomainName}}</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="类型">
                        <el-select v-model="AddDrawerData.type" style="width: 100%">
                            <el-option value="CDN" label="CDN"></el-option>
                            <el-option value="A" label="A"></el-option>
                            <el-option value="CNAME" label="CNAME"></el-option>
                            <el-option value="TXT" label="TXT"></el-option>
                            <el-option value="MX" label="MX"></el-option>
                            <el-option value="AAAA" label="AAAA"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="解析值">
                        <el-input v-model="AddDrawerData.data" type="textarea" :rows="5"></el-input>
                    </el-form-item>
                    <el-form-item label="TTL">
                        <el-input v-model="AddDrawerData.ttl" placeholder="最小60，最大339384"></el-input>
                    </el-form-item>
                    <el-form-item label="优先级">
                        <el-input v-model="AddDrawerData.priority" placeholder="最小1，最大50000"></el-input>
                    </el-form-item>
                </el-form>
                <div class="edit_drawer_footer">
                    <el-button type="primary" @click="AddDrawerSave()">保存</el-button>
                    <el-button @click="AddDrawerClose">取消</el-button>
                </div>
            </div>
        </el-drawer>
        <!-- 新增 end -->
        <!-- 编辑 -->
        <el-drawer custom-class="edit_drawer" title="编辑" v-model="EditDrawerOpen" direction="rtl" :before-close="EditDrawerClose">
            <div class="edit_drawer_content">
                <el-form label-width="100px">
                    <el-form-item label="主机头">
                        <el-input v-model="EditDrawerData.header" disabled>
                            <template #append>.{{DomainName}}</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="解析值">
                        <el-input v-model="EditDrawerData.data" type="textarea" :rows="5"></el-input>
                    </el-form-item>
                    <el-form-item label="TTL">
                        <el-input v-model="EditDrawerData.ttl" placeholder="最小60，最大339384"></el-input>
                    </el-form-item>
                    <el-form-item label="优先级">
                        <el-input v-model="EditDrawerData.priority" placeholder="最小1，最大50000"></el-input>
                    </el-form-item>
                </el-form>
                <div class="edit_drawer_footer">
                    <el-button type="primary" @click="EditDrawerSave()">保存</el-button>
                    <el-button @click="EditDrawerClose">取消</el-button>
                </div>
            </div>
        </el-drawer>
        <!-- 编辑 end -->
    </div>
</template>

<script lang="ts">
    import { Vue } from 'vue-class-component';
    import { Prop } from 'vue-property-decorator';
    import { GetData } from '@/Common/HostKerAPI';
    // 瞎jb起名的
    export default class Board extends Vue {
        name = 'Board';

        @Prop(String) DomainName!: string;

        RecordList: any[] = [];

        LoadRecord () {
            const f = new FormData();
            f.append('domain', this.DomainName);
            GetData('dnsGetRecords', f, (data: any) => {
                if (data.success) {
                    this.RecordList = data.records;
                } else {
                    this.$alert('加载解析列表失败' + data.errorMessage);
                }
            });
        }

        // ------------------------------------------------------------------------------------------------------------------ 新增
        AddDrawerData: Record<any, any> = {
            header: '',
            type: 'A',
            data: '',
            ttl: 300,
            priority: 0
        };

        AddDrawerOpen = false;
        AddDrawerSave () {
            const f = new FormData();
            f.append('domain', this.DomainName);
            if (!this.AddDrawerData.header) {
                this.$message.error('请填写主机头');
                return false;
            }
            f.append('header', this.AddDrawerData.header);
            f.append('type', this.AddDrawerData.type);
            if (this.AddDrawerData.type !== 'CDN') {
                if (!this.AddDrawerData.data) {
                    this.$message.error('请填写解析值');
                    return false;
                }
                if (this.AddDrawerData.ttl < 60 || this.AddDrawerData.ttl > 339384) {
                    this.$message.error('请填写TTL');
                    return false;
                }
                f.append('data', this.AddDrawerData.data);
                f.append('ttl', String(this.AddDrawerData.ttl));
            }
            if (this.AddDrawerData.type === 'MX') {
                if (this.AddDrawerData.priority < 1 || this.AddDrawerData.priority > 50000) {
                    this.$message.error('请填写优先级');
                    return false;
                }
                f.append('priority', String(this.AddDrawerData.priority));
            }
            GetData('dnsAddRecord', f, (data: any) => {
                if (data.success) {
                    this.$message.success('添加解析成功');
                    this.RecordList.push({
                        id: data.id,
                        header: this.AddDrawerData.header,
                        type: this.AddDrawerData.type,
                        data: this.AddDrawerData.data,
                        ttl: this.AddDrawerData.ttl,
                        priority: this.AddDrawerData.priority
                    });
                    this.AddDrawerClose();
                } else {
                    this.$message.error(data.errorMessage);
                }
            });
        }

        AddDrawerClose () {
            this.AddDrawerData.header = '';
            this.AddDrawerData.type = 'A';
            this.AddDrawerData.data = '';
            this.AddDrawerData.ttl = '300';
            this.AddDrawerData.priority = '';
            this.AddDrawerOpen = false;
        }
        // ------------------------------------------------------------------------------------------------------------------ 新增 end

        // ------------------------------------------------------------------------------------------------------------------ 编辑
        EditDrawerData = {
            id: '',
            header: '',
            index: 0,
            data: '',
            ttl: 0,
            priority: 0
        };

        EditDrawerOpen = false;
        Edit (index: number) {
            const row = this.RecordList[index];
            if (row.type === 'CDN') {
                this.$message.error('CDN解析没啥好编辑的');
                return;
            }
            this.EditDrawerData.id = row.id;
            this.EditDrawerData.header = row.header;
            this.EditDrawerData.data = row.data;
            this.EditDrawerData.ttl = row.ttl;
            this.EditDrawerData.priority = row.priority;
            this.EditDrawerData.index = index;
            this.EditDrawerOpen = true;
        }

        EditDrawerSave () {
            const row = this.RecordList[this.EditDrawerData.index];
            if (!this.EditDrawerData.data) {
                this.$message.error('请填写解析值');
                return false;
            }
            if (this.EditDrawerData.ttl < 60 || this.EditDrawerData.ttl > 339384) {
                this.$message.error('请填写TTL');
                return false;
            }
            const f = new FormData();
            f.append('id', this.EditDrawerData.id);
            f.append('data', this.EditDrawerData.data);
            f.append('ttl', String(this.EditDrawerData.ttl));
            if (row.type === 'MX') {
                if (this.EditDrawerData.priority < 1 || this.EditDrawerData.priority > 50000) {
                    this.$message.error('请填写优先级');
                    return false;
                }
                f.append('priority', String(this.EditDrawerData.priority));
            }
            GetData('dnsEditRecord', f, (data: any) => {
                if (data.success) {
                    this.$message.success('修改解析成功');
                    row.data = this.EditDrawerData.data;
                    row.ttl = this.EditDrawerData.ttl;
                    if (row.type === 'MX') row.priority = this.EditDrawerData.priority;
                    this.EditDrawerClose();
                } else {
                    this.$message.error(data.errorMessage);
                }
            });
        }

        EditDrawerClose () {
            this.EditDrawerOpen = false;
        }
        // ------------------------------------------------------------------------------------------------------------------ 编辑 end

        Delete (index: number) {
            const row = this.RecordList[index];
            const f = new FormData();
            f.append('id', String(row.id));
            GetData('dnsDeleteRecord', f, (data: any) => {
                if (data.success) {
                    this.$message.success('删除解析成功');
                    delete this.RecordList[index];
                } else {
                    this.$message.error(data.errorMessage);
                }
            });
        }

        FilterHandler (value: string, row: any, column: any) {
            const property = column.property;
            return row[property] === value;
        }

        mounted () {
            this.LoadRecord();
        }
    }
</script>

<style scoped>

</style>
