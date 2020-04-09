<template>
    <a-row>
        <a-col :span="18" class="list-border">
            <a-list itemLayout="vertical" size="large" :pagination="pagination" :dataSource="listData">
                <a-list-item slot="renderItem" slot-scope="item, index" key="item.title">
                    <span slot="extra">test</span>
                    <router-link :to="`/`">{{item.content}}</router-link>
                </a-list-item>
            </a-list>
        </a-col>

        <a-col :span="5"  :offset="1">
            <a-card title="Category" class="category-card">
                <p>card content</p>
                <p>card content</p>
                <p>card content</p>
            </a-card>
        </a-col>
    </a-row>
</template>
<script>
    const listData = [];
    for (let i = 0; i < 2; i++) {
        listData.push({
            href: 'https://www.antdv.com/',
            content: `ant design vue part ${i}`,
        });
    }

    export default {
        data() {
            return {
                listData,
                pagination: {
                    onChange: page => {
                        console.log(page);
                    },
                    pageSize: 2,
                },
                actions: [],
            };
        },
    };
</script>
<style>
    .ant-list-items{
        border: 1px solid #e8e8e8;
        padding: 0 24px 24px;
    }
    .ant-list-something-after-last-item .ant-spin-container > .ant-list-items > .ant-list-item:last-child{
        border:none;
        padding-bottom:0px;
    }
    .ant-list-item-main{
        font-weight: 600;
        font-size: 1rem;
    }
    .ant-list-item-main > a{
        color: #333;
        text-decoration: none
    }

</style>