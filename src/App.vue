<template>
    <router-view/>
</template>
<style lang="scss">
.edit_drawer {
    .el-drawer__body {
        padding: 20px;
    }
    .edit_drawer_content {
        display: flex;
        flex-direction: column;
        height: 100%;
        form {
            flex: 1;
        }
    }
    .edit_drawer_footer {
        display: flex;
        margin: auto;
    }
}
</style>
