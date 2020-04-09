<template>
    <a-layout>
        <a-layout-header>Header</a-layout-header>
        <a-layout-content>
            <app-main/>
        </a-layout-content>
        <a-layout-footer>Footer</a-layout-footer>
    </a-layout>
</template>
<script>
    import {AppMain} from './components'
    export default {
        components: {
            AppMain,
        },
        data() {
            return {
            };
        },
        methods: {
        }
    };
</script>
<style>
    .app-main{
        background:#fff;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        padding-right: 15px;
        padding-left: 15px;
        width: 1140px;
        max-width: 100%;
        padding-top: 1.5rem;
        padding-bottom:1.5rem;
    }
    .ant-layout{
        background: #fff;
    }
</style>
