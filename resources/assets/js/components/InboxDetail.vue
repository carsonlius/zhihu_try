<template>
    <div>
        <span>{{ placeholder }}</span>
        <div class="form-group">
            <textarea name="body" rows="5" class="textarea-inherit" v-model="body" :placeholder="placeholder"></textarea>
            <button class="btn btn-sm btn-primary pull-right btn_right" @click.prevent="responseMessage">回复</button>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                对话列表
            </div>
            <div class="panel-body">
                <div class="media" v-for="message in list_message">
                    <div class="media-left">
                        <img :src="message.from_user.avatar" alt="" width="60" height="60">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{ message.from_user.name }}</h4>
                        <p>{{ message.body }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InboxDetail",
        props: ['friend_id', 'login_name'],
        data: function () {
            return {
                list_message: [],
                body: ''
            }
        },
        computed: {
            placeholder: function () {
                return '发私信给' + this.login_name;
            }
        },
        mounted: function () {
            this.requestMessage();
        },
        methods: {
            // 回复私信
            responseMessage: function () {
                let params = {
                    to_user_id: this.friend_id,
                    body: this.body
                };
                console.log(params);
                let vm = this;
                this.$http.post('/api/message/store', params, {responseType: 'json'}).then(function (response) {
                    if (response.body.status === 0) {
                        vm.list_message.unshift(response.body.result_store);
                        vm.body = '';
                    }
                });
            },
            // 获取私信信息
            requestMessage: function () {
                let params = {
                    params: {friend_id: this.friend_id},
                    responseType: 'json'
                };
                this.$http.get('/api/message/inboxShow', params).then(function (response) {
                    if (response.body.status === 0) {
                        this.list_message = response.body.data;
                    } else {
                        console.log(response);
                    }
                });
            }
        }
    }
</script>

<style scoped>
    .textarea-inherit {
        width: 98%;
        overflow-y: auto;
    }

    .btn_right {
        margin-right: 1%;
    }

</style>