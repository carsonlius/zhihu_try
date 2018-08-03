<template>
    <div>
        <div class="form-group div_bottom">
            <textarea v-model="body" :placeholder="placeholder" class="textarea-inherit" id="message_textarea" rows="3"></textarea>
            <button type="submit" class="btn btn-xs btn-primary pull-right" @click.prevent="responseMessage">发送</button>
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
                        <h4 class="media-heading">{{ message.from_user.name !== friend_name ? '我' : message.from_user.name}}</h4>
                        <p style="white-space: pre-line">{{ message.body }}</p>
                        <span class="pull-left">{{ message.created_at_human }}</span>
                        <button @click.prevent="focusTextarea" v-if="showBtn(message.from_user_id)" class="pull-right btn btn-xs btn-primary">回复</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InboxDetail",
        props: ['friend_id', 'friend_name'],
        data: function () {
            return {
                list_message: [],
                body: ''
            }
        },
        computed: {
            placeholder: function () {
                return '发私信给 ' + this.friend_name;
            }
        },
        mounted: function () {
            this.requestMessage();
        },
        methods: {
            // 是否需要展示回复按钮
            showBtn : function(from_user_id){
                return from_user_id === parseInt(this.friend_id);
            },
            // 获取焦点
            focusTextarea: function(){

                this.$nextTick(() => this.$refs.input.focus());
                $('#message_textarea')[0].focus();
            },
            // 回复私信
            responseMessage: function () {
                let params = {
                    to_user_id: this.friend_id,
                    body: this.body
                };
                let vm = this;
                this.$http.post('/api/message/store', params, {responseType: 'json'}).then(function (response) {
                    if (response.body.status === 0) {
                        vm.list_message.unshift(response.body.result_store);
                        vm.body = '';
                    }
                });
            },
            // 将未读私信标记为已读私信
            markRead: function(){
                let params = {
                    responseType: 'json', params: { friend_id : this.friend_id}
                };
                this.$http.get('/api/message/markAsRead', params).then(function (response) {
                    if (response.body.status !== 0) {
                        console.log(response.body.msg);
                    }
                });
            },

            // 获取私信信息
            requestMessage: function () {
                let params = {
                    params: {friend_id: this.friend_id},
                    responseType: 'json'
                };
                let vm = this;
                this.$http.get('/api/message/inboxShow', params).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        this.list_message = response.body.data;
                        // 将未读标记为已经读了
                        // vm.markRead();
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
        width: 100%;
        overflow: auto;
        word-break: break-all;
    }

    .div_bottom {
        margin-bottom: 30px;
    }

</style>