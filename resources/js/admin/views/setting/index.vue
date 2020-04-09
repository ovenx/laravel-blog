<template>
    <div>
        <a-page-header style="border: 1px solid rgb(235, 237, 240)" title="Reset Password"
                       :breadcrumb="{ props: { routes } }">
        </a-page-header>
        <a-card  style="margin-top:20px">
            <a-form :form="form" @submit="handleSubmit">
                <a-form-item v-bind="formItemLayout" label="Old Password">
                    <a-input
                            v-decorator="['old_password',{ rules: [{required: true,message: 'Please input old password!'}]}]"
                            type="password"/>
                </a-form-item>
                <a-form-item v-bind="formItemLayout" label="New Password">
                    <a-input
                            v-decorator="['new_password',{rules: [{required: true,message: 'Please input new password!'},{
                validator: validateToNextPassword}]}]"
                            type="password"/>
                </a-form-item>
                <a-form-item v-bind="formItemLayout" label="Confirm New Password">
                    <a-input
                            v-decorator="['confirm_new_password',{rules: [{required: true,message: 'Please input new password!'},{
                validator: compareToFirstPassword,
              }]}]"
                            type="password"/>
                </a-form-item>
                <a-form-item v-bind="tailFormItemLayout">
                    <a-button type="primary" html-type="submit" :loading="loading">
                        Submit
                    </a-button>
                </a-form-item>
            </a-form>
        </a-card>
    </div>
</template>
<script>
    import { removeToken } from '../../utils/auth'
    export default {
        data() {
            return {
                routes: [
                    {
                        path: '/',
                        breadcrumbName: 'Home',
                    },
                    {
                        path: '/setting',
                        breadcrumbName: 'Setting',
                    }
                ],
                loading: false,
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
            }
        },
        beforeCreate() {
            this.form = this.$form.createForm(this, {name: 'setting'});
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault();
                this.form.validateFields((error, values) => {
                    console.log('error', error);
                    console.log('Received values of form: ', values);
                    if (!error) {
                        this.submit()
                    }
                });
            },
            submit() {
                this.loading = true
                const that = this
                request.put('/reset_password', this.form.getFieldsValue()).then(result => {
                    console.log('ok')
                    that.loading = false
                    if (result.code > 0) {
                        this.$notification['success']({
                            message: result.msg
                        });
                        removeToken()
                        location.reload()
                    }
                })
            },
            validateToNextPassword(rule, value, callback) {
                const form = this.form;
                if (value && this.confirmDirty) {
                    form.validateFields(['confirm_new_password'], {force: true});
                }
                callback();
            },
            compareToFirstPassword(rule, value, callback) {
                const form = this.form;
                if (value && value !== form.getFieldValue('new_password')) {
                    callback('Two passwords that you enter is inconsistent!');
                } else {
                    callback();
                }
            },
        }
    }

</script>