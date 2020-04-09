<template>
    <div>
        <a-page-header style="border: 1px solid rgb(235, 237, 240)" :title="title"
                       :breadcrumb="{ props: { routes } }"></a-page-header>

        <a-card style="margin-top:20px">
            <a-spin :spinning="spinning">
                <a-form
                        :form="form"
                        @submit="handleSubmit"
                        :label-col="labelCol"
                        :wrapper-col="wrapperCol"
                >
                    <a-form-item label="Title">
                        <a-input
                                v-decorator="['title', {initialValue: defaultPost.title, rules: [{required: true,message: 'Please input title!'}]}]"/>
                    </a-form-item>
                    <a-form-item label="Category">
                        <a-select placeholder="Please Select Category"
                                  v-decorator="['category', {initialValue: defaultPost.category, rules: [{required: true,message: 'Please Select Category!'}]}]">
                            <a-select-option v-for="(item, index) in categories" :value="item.id" :key="index">
                                {{item.name}}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                    <a-form-item label="Tags">
                        <a-select
                                mode="multiple"
                                placeholder="Please Select Tags"
                                @change="handleChange"
                                style="width: 100%"
                                v-decorator="['tags', {initialValue: defaultPost.tags, rules: [{required: true,message: 'Please Select Tags!'}]}]"
                        >
                            <a-select-option v-for="(item, index) in tags" :key="index" :value="item.id">
                                {{item.name}}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                    <a-form-item label="Content">
                        <mavon-editor :ishljs="true" :boxShadow="false" :subfield="false" :shortCut="false" ref="editor"
                                      :language="markdownOption.language"
                                      v-decorator="['content_markdown', {initialValue: defaultPost.content, rules: [{required: true,message: 'Please Input Content!'}]}]"/>
                    </a-form-item>
                    <a-form-item :wrapper-col="{ span: 14, offset: 2 }">
                        <a-button type="primary" html-type="submit" :loading="loading">
                            Submit
                        </a-button>
                    </a-form-item>
                </a-form>
            </a-spin>
        </a-card>
    </div>
</template>
<script>

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
                labelCol: {span: 2},
                wrapperCol: {span: 14},
                defaultPost: {
                    title: '',
                    category: [],
                    content: "",
                    tags: [],
                },
                markdownOption: {
                    language: 'en',
                },
                categories: [],
                tags: [],
                loading: false,
                spinning: false,
                title: "New Post",
                id: 0,
            }
        },
        beforeCreate() {
            this.form = this.$form.createForm(this, {name: 'post'});
        },
        created() {
            this.getInfo()
        },
        methods: {
            getInfo() {
                let id = this.$route.params['id']
                if (id == undefined) {
                    id = 0;
                    this.title = "New Post"
                } else {
                    this.title = "Edit Post"
                }
                this.id = id
                this.spinning = true,
                request.get('/posts/' + id).then(result => {
                    this.spinning = false;
                    const data = result.data
                    this.tags = data.tags
                    this.categories = data.categories
                    this.defaultPost = data.info
                });
            },
            handleSubmit(e) {
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
                this.loading = true
                if (this.id > 0) {
                    request.put('/posts/' + this.id, this.form.getFieldsValue()).then(result => this.callback(result))
                } else {
                    request.post('/posts', this.form.getFieldsValue()).then(result => this.callback(result))
                }

            },
            callback(result) {
                this.loading = false
                if (result.code > 0) {
                    this.$notification['success']({
                        message: result.msg
                    });
                    this.$router.push('/post')
                } else {
                    this.$notification['error']({
                        message: result.msg
                    });
                }
            },
            handleChange(selectedItems) {
                this.defaultPost.tags = selectedItems;
            },
        }
    }

</script>

<style>
    .v-note-wrapper.markdown-body {
        border: 1px solid #d9d9d9;
        border-top-width: 1.02px;
        border-radius: 4px;
        z-index: 1000;
        min-height: 500px;
    }
</style>