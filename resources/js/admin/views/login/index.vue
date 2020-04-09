<template>
    <a-card class="login" title="Laravel Blog">
        <a-form :form="form" @submit="handleSubmit">
            <a-form-item v-bind="formItemLayout" label="Username">
                <a-input v-decorator="['username',{ rules: [{required: true,message: 'Please input username!'}]}]"/>
            </a-form-item>
            <a-form-item v-bind="formItemLayout" label="Password">
                <a-input v-decorator="['password',{rules: [{required: true,message: 'Please input password!'}]}]"
                         type="password"/>
            </a-form-item>
            <a-form-item v-bind="tailFormItemLayout">
                <a-button type="primary" html-type="submit" :loading="loading">
                    Login
                </a-button>
            </a-form-item>
        </a-form>
    </a-card>
</template>


<script>
    import {setToken} from '../../utils/auth'

    export default {
        data() {
            return {
                confirmDirty: false,
                autoCompleteResult: [],
                formItemLayout: {
                    labelCol: {
                        xs: {span: 24},
                        sm: {span: 8, offset: 0},
                    },
                    wrapperCol: {
                        xs: {span: 24},
                        sm: {span: 13},
                    },
                },
                tailFormItemLayout: {
                    wrapperCol: {
                        xs: {
                            span: 24,
                            offset: 0,
                        },
                        sm: {
                            span: 16,
                            offset: 8,
                        },
                    },
                },
                loading: false,
            };
        },
        beforeCreate() {
            this.form = this.$form.createForm(this, {name: 'login'});
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault();
                this.form.validateFields((error, values) => {
                    console.log('error', error);
                    console.log('Received values of form: ', values);
                    if (!error) {
                        this.login()
                    }
                });
            },
            login() {
                this.loading = true
                const that = this
                request.post('/login', this.form.getFieldsValue()).then(result => {
                    this.loading = false
                    if (result.code > 0) {
                        setToken(result.access_token)
                        that.$router.push({path: that.redirect || '/', query: that.otherQuery})
                    }
                })
            },
        },
    };
</script>

<style>
    .login {
        width: 400px;
        margin: 0 auto;
        margin-top: 10%;
    }
</style>