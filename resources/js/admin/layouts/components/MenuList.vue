<template>
    <a-menu theme="dark" mode="inline">
        <template v-for="(item,index) in route" v-if="!item.hidden">
            <a-menu-item
                    v-if="hasOneShowingChild(item.children,item) && (!onlyOneChild.children||onlyOneChild.noShowingChildren)&&!item.alwaysShow">
                <router-link :to="resolvePath(item.path, onlyOneChild.path)">
                    <a-icon :type="onlyOneChild.meta.icon"/>
                    <span>{{ onlyOneChild.meta.title }}</span>
                </router-link>
            </a-menu-item>

            <a-sub-menu v-else>
                <span slot="title"><a-icon :type="item.meta.icon"/><span>{{ item.meta.title }}</span></span>
                <a-menu-item v-for="(child, child_index) in item.children" :key="child_index">
                    <router-link :to="resolvePath(item.path, child.path)">{{ child.meta.title }}</router-link>
                </a-menu-item>
            </a-sub-menu>
        </template>
    </a-menu>
</template>

<script>
    import {constantRoutes} from '../../router'
    import path from 'path'

    export default {
        data() {
            this.onlyOneChild = null
            return {
                route: constantRoutes
            }
        },
        methods: {
            hasOneShowingChild(children = [], parent) {
                const showingChildren = children.filter(item => {
                    if (item.hidden) {
                        return false
                    } else {
                        // Temp set(will be used if only has one showing child)
                        this.onlyOneChild = item
                        return true
                    }
                })

                // When there is only one child router, the child router is displayed by default
                if (showingChildren.length === 1) {
                    return true
                }

                // Show parent if there are no child router to display
                if (showingChildren.length === 0) {
                    this.onlyOneChild = {...parent, path: '', noShowingChildren: true}
                    return true
                }

                return false
            },
            resolvePath(basePath, routePath) {
                return path.resolve(basePath, routePath)
            }
        }
    }

</script>