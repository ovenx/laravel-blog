<template>
    <a-row>
        <a-col :span="24" class="list-border" :loading="loading">
            <a-spin :spinning="loading">
                <div class="tag">
                    <div class="tag-title">Total {{ tagList.length }} Tags</div>
                    <div class="tag-list">
                        <router-link :to="`/tags/`+item.id" v-for="(item, index) in tagList" :key="index">{{ item.name }} <sup v-if="item.num>0">{{ item.num }}</sup></router-link>
                    </div>
                </div>
            </a-spin>
        </a-col>
    </a-row>
</template>
<script>
    export default {
        data() {
            return {
                tagList: [],
                loading: false,
            };
        },
        created() {
            this.getList()
        },
        methods: {
            getList() {
                this.loading = true
                request.get('/tags').then(result => {
                    this.loading = false
                    this.tagList = result.data
                });
            }
        },
    };
</script>
<style>
    .tag{
        text-align: center;
    }
    .tag .tag-title{
        font-size: 16px;
        font-weight: 500;
        padding-top: 1rem;
        border-bottom: 2px solid #9E9E9E;
        display: inline-block;
    }
    .tag .tag-list{
        padding-top:2rem;
    }
    .tag-list a{
        padding:10px;
    }

</style>
