<template>
    <a-layout id="layout-left-pannel">
        <a-layout-sider :trigger="null" collapsible v-model="collapsed">
            <router-link class="header-link" :to="`/`" style="color:#fff"><div class="header">{{ title }}</div></router-link>
            <menu-list/>
        </a-layout-sider>
        <a-layout>
            <a-layout-header style="background: #fff; padding: 0">
                <a-icon
                        class="trigger"
                        :type="collapsed ? 'menu-unfold' : 'menu-fold'"
                        @click="handleCoolapse"
                />
                <span style="float: right;margin-right:20px;font-size:18px">
                    <span style="padding-right:10px">admin</span>
                    <a-icon @click="logout" type="poweroff" style="font-size:18px;"/>
                </span>

            </a-layout-header>
            <a-layout-content
                    :style="{ margin: '24px 16px', padding: '24px', overflow:'auto', background: '#fff', minHeight: '280px' }"
            >
                <app-main/>
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>
<script>
    import {AppMain, MenuList} from './components'
    import { removeToken } from '../utils/auth'
    export default {
        components: {
            AppMain,
            MenuList,
        },
        data() {
            return {
                collapsed: false,
                title: 'Laravel Blog'
            };
        },
        methods: {
            handleCoolapse() {
                console.log(this.collapsed)
                if (this.collapsed) {
                    this.title = 'Laravel Blog'
                } else {
                    this.title = 'Blog'
                }
                this.collapsed = !this.collapsed
            },
            logout() {
                const that = this
                request.post('/logout').then(result =>  {
                    removeToken()
                    location.reload()
                }).catch(function (error) {
                    console.log(error);
                });
            },
        }
    };
</script>
<style>
    #layout-left-pannel {
        height: 100%;
    }

    #layout-left-pannel .trigger {
        font-size: 18px;
        line-height: 64px;
        padding: 0 24px;
        cursor: pointer;
        transition: color 0.3s;
    }

    #layout-left-pannel .trigger:hover {
        color: #1890ff;
    }

    #layout-left-pannel .header {
        height: 32px;
        margin: 16px;
        text-align: center;
        color: #fff;
        font-weight: bold;
        font-size: 20px;
    }
    .header-link:focus{
        text-decoration-skip-ink:none;
        -webkit-text-decoration-skip:none;
    }
</style>
