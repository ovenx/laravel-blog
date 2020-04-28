<template>
    <a-row>
        <a-col :span="24" class="list-border">
            <a-list :loading="loading" itemLayout="vertical" size="large" :pagination="pagination" :dataSource="postList">
                <a-list-item slot="renderItem" slot-scope="item, index" key="item.title">
                    <span slot="extra">
                        <router-link :to="`/categories/`+item.category">#{{item.category_name}}</router-link> <a-divider type="vertical" /> {{ item.created_time }}
                    </span>
                    <router-link :to="`/posts/`+item.id">{{item.title}}</router-link>
                </a-list-item>
            </a-list>
        </a-col>
    </a-row>
</template>
<script>
    export default {
        data() {
            return {
                postList: [],
                loading: false,
                pagination: {
                    hideOnSinglePage: true,
                    pageSize: 20,
                    current: 1,
                    total: 0,
                    onChange: page => {
                        this.pagination.current = page;
                        this.getList()
                    },
                }
            };
        },
        created() {
            this.getList()
        },
        methods: {
            getList() {
                this.loading = true
                request.get('/posts', {params: {page: this.pagination.current, page_num: this.pagination.pageSize}}).then(result => {
                    this.loading = false
                    const data = result.data
                    this.pagination.total = parseInt(data.total)
                    this.postList = data.data
                });
            }
        },
    };
</script>
<style>
    .ant-list-items{
        border: 1px solid #e8e8e8;
        padding: 0 24px 16px;
    }
    .ant-list-something-after-last-item .ant-spin-container > .ant-list-items > .ant-list-item:last-child{
        border:none;
        padding-bottom:0px;
    }
    .ant-list-item-main{
        font-size: 1rem;
    }
    .ant-list-item-main > a{
        color: rgba(0, 0, 0, 0.65);
        text-decoration: none;
        font-size:16px;
        font-weight: 500;
    }
    .card-list{
        margin-bottom:2rem;
    }
    .card-list .ant-card-body{
        display: flex;
        flex-wrap: wrap;
    }
    .card-list p{
        padding:3px 5px;
        margin-right:8px;
    }
    .ant-tag {
        border-radius:0;
    }

</style>
