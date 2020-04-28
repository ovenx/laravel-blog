<template>
    <div>
        <a-page-header style="border: 1px solid rgb(235, 237, 240)" title="Post List"
                       :breadcrumb="{ props: { routes } }">
            <template slot="extra">
                <router-link :to="`/post/add`">
                    <a-button key="1" type="primary">
                        New
                    </a-button>
                </router-link>
            </template>
        </a-page-header>


        <a-table :columns="columns" :dataSource="data" bordered style="padding-top:20px" :pagination="pagination"
                 :loading="loading"
                 @change="handleTableChange"
                 :rowKey="rowKey">
            <template slot="category" slot-scope="text, record">
                {{ categoryList[text] }}
            </template>
            <template slot="tag" slot-scope="text, record">
                <a-tag color="blue" v-for="(item, index) in record.tags" :key="index">
                    {{tagList[item]}}
                </a-tag>
            </template>
            <template slot="operation" slot-scope="text, record">
                <router-link :to="{ name: 'editPost', params: { id: record.id}}"><a-button type="primary">Edit</a-button></router-link>
                <a-popconfirm
                        title="Sure to delete?"
                        @confirm="() => handleDelete(record.id)"
                >
                    <a-button type="danger">Delete</a-button>
                </a-popconfirm>
            </template>
        </a-table>
    </div>
</template>
<script>
    const columns = [
        {
            title: 'ID',
            dataIndex: 'id',
            key: 'id',
        },
        {
            title: 'Title',
            dataIndex: 'title',
            key: 'title',
        },
        {
            title: 'Category',
            dataIndex: 'category',
            scopedSlots: {customRender: 'category'},
        },
        {
            title: 'Tags',
            dataIndex: 'tags',
            scopedSlots: {customRender: 'tag'},
        },
        {
            title: 'CreateTime',
            dataIndex: 'created_date',
            key: 'created_date',
        },
        {
            title: 'Operation',
            dataIndex: 'operation',
            scopedSlots: {customRender: 'operation'},
        }
    ];

    const data = [];
    export default {
        data() {
            return {
                routes: [
                    {
                        path: '/',
                        breadcrumbName: 'Home',
                    },
                    {
                        path: '/post',
                        breadcrumbName: 'Post',
                    }
                ],
                data,
                columns,
                loading: false,
                rowKey: 'id',
                categoryList: [],
                tagList: [],
                pagination: {pageSize: 15, current: 1, total: 0},
            }
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
                    this.categoryList = result.categoryList
                    this.tagList = result.tagList
                    console.log(this.tagList)
                    this.data = data.data
                });
            },
            handleTableChange(pagination, filters, sorter) {
                console.log(pagination);
                this.pagination.current = pagination.current;
                this.getList()
            },

            handleDelete(id) {
                request.delete('/posts/'+id).then(result => {
                    if (result.code > 0) {
                        this.$notification['success']({
                            message: result.msg,
                        });
                        this.getList()
                    }
                })
            },
        }
    }

</script>
