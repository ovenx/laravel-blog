<template>
    <a-layout>
        <a-layout-header>
            <a-row class="header-row">
                <a-col :span="5" class="nav-title">
                    <router-link :to="`/`">Laravel Blog</router-link>
                </a-col>
                <a-col :span="7" class="nav-content">
                    <ul class="menu-list">
                        <router-link v-for="(item, index) in navList" :key="index" :to="item.link"><li class="menu-item">{{ item .title}}</li></router-link>
                    </ul>
                </a-col>
            </a-row>

        </a-layout-header>
        <a-layout-content>
            <app-main/>
        </a-layout-content>
        <a-layout-footer>
            <a-row class="footer-row">
                Copyright 2020
            </a-row>
        </a-layout-footer>
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
                navList: [
                    { title: 'Home', link: '/' },
                    { title: 'Categories', link: '/categories' },
                    { title: 'Tags', link: '/tags' },
                    { title: 'Archives', link: '/archives' },
                ]
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
        width: 1200px;
        max-width: 100%;
        padding-top: 1.5rem;
        padding-bottom:1.5rem;
        padding-left: 15px;
        padding-right: 15px;
    }
    .ant-layout{
        background: #fff;
    }
    .ant-layout a{
        color:rgba(0, 0, 0, 0.65);
    }
    .ant-layout a:hover{
        color: #1890ff;
    }
    .ant-layout .ant-pagination-item{
        border-radius: 0px;
    }
    .ant-pagination-prev, .ant-pagination-next, .ant-pagination-jump-prev, .ant-pagination-jump-next{
        border-radius:0px;
    }
    .ant-pagination-prev .ant-pagination-item-link, .ant-pagination-next .ant-pagination-item-link{
        border-radius:0px;
    }
    .ant-layout-header{
        background: #fff;
        box-shadow: 0 2px 8px #f0f1f2;
        position: relative;
        z-index: 10;
        max-width: 100%;
    }
    .footer-row{
        width: 1200px;
        margin: 0 auto;
        padding-left:15px;
        padding-right:15px;
    }
    .header-row{
        width: 1200px;
        margin: 0 auto;
        padding-left:15px;
        padding-right:15px;
    }
    .nav-title{
        font-weight: 500;
        font-size:20px;
    }
    .nav-content{
        font-size:16px;
        float: right;
    }
    .nav-content ul{
        display: flex;
        justify-content: space-between;
    }
    .nav-content li {
        list-style:none;
    }
    sup{
        top: -0.8em;
        right: -0.2rem
    }
</style>
