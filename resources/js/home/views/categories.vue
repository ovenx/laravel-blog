<template>
    <a-row>
        <a-col :span="24" class="list-border" :loading="loading">
            <a-spin :spinning="loading">
                <div class="category">
                    <div class="category-title">Total {{ categoryList.length }} Categories</div>
                    <div class="category-list">
                        <router-link :to="`/categories/`+item.id" v-for="(item, index) in categoryList" :key="index">{{ item.name }} <sup v-if="item.num>0">{{ item.num }}</sup></router-link>
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
                categoryList: [],
                loading: false,
            };
        },
        created() {
            this.getList()
        },
        methods: {
            getList() {
                this.loading = true
                request.get('/categories').then(result => {
                    this.loading = false
                    this.categoryList = result.data
                });
            }
        },
    };
</script>
<style>
    .category{
        text-align: center;
    }
    .category .category-title{
        font-size: 16px;
        font-weight: 500;
        padding-top: 1rem;
        border-bottom: 2px solid #9E9E9E;
        display: inline-block;
    }
    .category .category-list{
        padding-top:2rem;
    }
    .category-list a{
        padding:10px;
    }

</style>
