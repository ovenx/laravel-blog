<template>
    <div>
        <a-page-header style="border: 1px solid rgb(235, 237, 240)" title="Category List"
                       :breadcrumb="{ props: { routes } }">
            <template slot="extra">
                <a-button key="1" type="primary" @click="handleAdd">
                    New
                </a-button>
            </template>
        </a-page-header>


        <a-table :columns="columns" :dataSource="data" bordered style="padding-top:20px" :pagination="pagination"
                 :loading="loading"
                 @change="handleTableChange"
                 :rowKey="rowKey">
            <template slot="operation" slot-scope="text, record">
                <a-button type="primary" @click="handleEdit(record)">Edit</a-button>
                <a-popconfirm
                        title="Sure to delete?"
                        @confirm="() => handleDelete(record.id)"
                >
                    <a-button type="danger">Delete</a-button>
                </a-popconfirm>
            </template>
        </a-table>

        <a-modal
                :title="dialogType==='edit'?'Edit Category':'New Category'"
                :visible="visible"
                @ok="handleOk"
                :confirmLoading="confirmLoading"
                @cancel="handleCancel"
        >
            <a-form :form="form">
                <a-form-item label="Category Name">
                    <a-input
                            v-decorator="['name',{initialValue: currentData.name, rules: [{required: true,message: 'Please input category name!',},],},]"/>
                </a-form-item>
            </a-form>
        </a-modal>
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
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
        },
        {
            title: 'Create Time',
            dataIndex: 'created_date',
            key: 'created_date',
        },
        {
            title: 'Operation',
            dataIndex: 'operation',
            scopedSlots: {customRender: 'operation'},
        }
    ];
    const currentData = {
        id: 0,
        name: '',
    }
    export default {
        data() {
            return {
                routes: [
                    {
                        path: '/',
                        breadcrumbName: 'Home',
                    },
                    {
                        path: '/category',
                        breadcrumbName: 'Category',
                    }
                ],
                columns,
                loading: false,
                visible: false,
                confirmLoading: false,
                dialogType: 'add',
                currentData: currentData,
                data: [],
                rowKey: 'id',
                pagination: {pageSize: 15, current: 1, total: 0},
            }
        },
        beforeCreate() {
            this.form = this.$form.createForm(this, {name: 'category'});
        },
        created() {
            this.getList()
        },
        methods: {
            getList() {
                this.loading = true
                request.get('/categories', {params: {page: this.pagination.current, page_num: this.pagination.pageSize}}).then(result => {
                    this.loading = false
                    const data = result.data
                    this.pagination.total = parseInt(data.total)
                    this.data = data.data
                    console.log(result)
                })
            },

            handleTableChange(pagination, filters, sorter) {
                console.log(pagination);
                this.pagination.current = pagination.current;
                this.getList()
            },

            handleAdd() {
                this.dialogType = 'new'
                this.visible = true;
            },

            handleEdit(record) {
                console.log(record)
                if (this.currentData.id) {
                    this.form.setFieldsValue({name : record.name})
                } else {
                    this.currentData.name = record.name
                }
                this.currentData.id = record.id
                this.dialogType = 'edit'
                this.visible = true;
            },

            handleOk(e) {
                e.preventDefault();
                this.form.validateFields((error, values) => {
                    console.log('error', error);
                    console.log('Received values of form: ', values);
                    if (!error) {
                        this.doSubmit()
                    }
                });
            },
            doSubmit() {
                this.confirmLoading = true;
                const isEdit = this.dialogType === 'edit'
                if (isEdit) {
                    request.put('/categories/'+this.currentData.id, this.form.getFieldsValue()).then(result => this.callback(result))
                } else {
                    request.post('/categories', this.form.getFieldsValue()).then(result => this.callback(result))
                }
            },
            callback(result) {
                this.confirmLoading = false;
                if (result.code > 0) {
                    this.visible = false;
                    this.$notification['success']({
                        message: result.msg,
                    });
                    this.getList()
                }
            },
            handleDelete(id) {
                request.delete('/categories/'+id).then(result => this.callback(result))
            },
            handleCancel(e) {
                console.log('Clicked cancel button');
                this.visible = false;
            },
        }
    }

</script>
