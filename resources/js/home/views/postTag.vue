<template>
    <a-row>
        <a-spin :spinning="loading">
            <a-col :span="24" class="archive-list">
                <div class="archive-item">
                    <h1>{{ tagName }}</h1>
                    <a-timeline>
                        <a-timeline-item v-for="(subItem, subIndex) in postList" :key="subIndex">
                            <span>{{ subItem.created_date}}</span>
                            <router-link :to="`/posts/`+subItem.id">{{ subItem.title}}</router-link>
                        </a-timeline-item>
                    </a-timeline>
                </div>
                <a-pagination hideOnSinglePage v-model="pagination.current" @change="pagination.onChange"  :pageSize="pagination.pageSize" :total="pagination.total"/>
            </a-col>
        </a-spin>
    </a-row>
</template>
<script>
    export default {
        data() {
            return {
                tagId: 0,
                tagName: '',
                postList: [],
                pagination: {
                    pageSize: 100,
                    current: 1,
                    total: 0,
                    onChange: page => {
                        this.pagination.current = page;
                        this.getList()
                    },
                },
                loading: false,
            };
        },
        created() {
            this.tagId = this.$route.params && this.$route.params.id
            this.getList()
        },
        methods: {
            getList() {
                this.loading = true
                request.get('/tags/'+this.tagId, {params: {page: this.pagination.current, page_num: this.pagination.pageSize}}).then(result => {
                    this.loading = false
                    this.pagination.total = parseInt(result.data.total)
                    this.postList = result.data.data
                    this.tagName = result.tagName
                });
            }
        },
    };
</script>
<style>
    .archive-item h1{
        padding-bottom:.5rem;
        font-size:18px;
    }
    .ant-timeline-item-last > .ant-timeline-item-content{
        min-height: 0;
    }
    .ant-timeline-item span{
        color: #8a8a8a;
        margin-right: .5rem;
    }

</style>
