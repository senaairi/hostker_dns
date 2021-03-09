<template>
    <div>跳转中。。。</div>
</template>

<script lang="ts">
    import { Vue } from 'vue-class-component';
    export default class Index extends Vue {
        name = 'Index'
        mounted () {
            this.$router.push({ path: '/login' });
        }
    };
</script>

<style scoped>

</style>
