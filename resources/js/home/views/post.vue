<template>
    <a-row>
        <a-spin :spinning="loading">
            <a-col :span="18" class="list-border">
                <div class="article-header">
                    <h1 class="title">{{ postInfo.title }}</h1>
                    <div class="desc">
                        <span class="article-date">Date : {{ postInfo.created_time}}</span>
                        <a-divider type="vertical"/>
                        <span class="article-date">Category : <router-link :to="`/categories/`+postInfo.category">{{ postInfo.category_name}}</router-link></span>
                        <a-divider type="vertical"/>
                        <span class="article-date">Tag :
                            <span v-for="(item, index) in postInfo.tags" :key="index">
                                <span v-if="index>0">,</span>
                                <router-link :to="`/tags/`+item">{{ tagList[item]}}</router-link>
                            </span>
                        </span>
                    </div>
                </div>
                <a-divider dashed/>
                <div class="article-content" v-html="postInfo.content_html"></div>
            </a-col>
            <a-col :span="5" :offset="1">
                <a-card title="TOC" class="card-list category-card">
                    <div class="card-content" click="custormAnchor" id="toc-html"></div>
                </a-card>
            </a-col>
        </a-spin>
    </a-row>
</template>
<script>

    export default {
        data() {
            return {
                loading: false,
                postId: 0,
                postInfo: {},
                tagList: {}
            };
        },
        created() {
            this.postId = this.$route.params && this.$route.params.id
            this.getInfo()
        },
        methods: {
            getInfo() {
                this.loading = true
                request.get('/posts/' + this.postId).then(result => {
                    this.loading = false
                    this.postInfo = result.data.info
                    this.tagList = result.data.tagList

                    let MyComponent = Vue.extend({
                        template: `<div>${this.postInfo.toc_html}</div>`,
                        methods: {
                            custormAnchor(anchorName) {
                                let anchorElement = document.querySelector(anchorName);
                                console.log(anchorElement)
                                if (anchorElement) {
                                    let total = anchorElement.offsetTop;//定位锚点

                                    // Chrome
                                    document.body.scrollTop = total
                                    // Firefox
                                    document.documentElement.scrollTop = total
                                    // Safari
                                    window.pageYOffset = total
                                }
                            }
                        }
                    });
                    let component = new MyComponent().$mount();
                    let dom = document.querySelector("#toc-html").appendChild(component.$el);
                });
            }
        },
    };
</script>
<style>
    .ant-card-head {
        min-height: auto;
        padding-left: 15px;
        color: #959595;
        font-size: 14px;
    }

    .ant-card-head-title {
        padding: 10px 0;
    }

    .ant-card-body {
        font-size: 14px;
        padding: 15px 15px 15px 0px;
    }

    .ant-card-body ul {
        margin-bottom: 0;

    }

    .ant-card-body ul a {
        color: #666;
    }

    .article-header .title {
        font-size: 1.4rem;
    }

    .article-content pre {
        background: #eee;
        padding: 20px;
    }
    .article-content code{
        background: #eee;
        padding: .2rem .3rem;
    }

</style>
