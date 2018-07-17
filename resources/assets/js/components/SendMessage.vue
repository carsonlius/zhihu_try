<template>
    <div>
        <span class="btn btn-sm btn-default" data-target="#modal-send-message" data-toggle="modal">
            <font-awesome-icon icon="comment" />发送私信
        </span>

        <div class="modal fade" id="modal-send-message" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            发送私信
                        </h4>
                    </div>

                    <div class="modal-body">
                        <span v-show="errors.has('body')" class="alert-danger">{{ errors.first('body') }}</span>
                        <textarea class="form-control" placeholder="私信内容" rows="3" data-vv-as="私信内容"  data-vv-name="body" v-validate.initial="'required'"
                                  v-model="body" v-if="!success_status"></textarea>
                        <div class="alert alert-success" v-if="success_status">
                            <strong>私信发送成功</strong>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="sendMessage">
                            发送
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        data: function () {
            return {
                body: '',
                success_status: false
            };
        },
        methods: {
            // 发送私信
            sendMessage: function () {
                let url = '/api/message/store';
                let vm = this;
                this.$http.post(url, {to_user_id: vm.user_id, body: this.body}).then(function (response) {
                    vm.success_status = response.body.status === 0;
                    if (response.body.status === 0) {
                        // 防止一瞬间关闭模态框 导致看不到提示
                        setTimeout(function () {
                            $('#modal-send-message').modal('toggle');

                            // 将模态框重置为默认的状态 方便再次发送私信
                            vm.success_status = false;
                            vm.body = '';
                        }, 2000);
                    }


                }, function (response) {
                    console.log(response);
                })
            },
        }
    }
</script>

<style scoped>
    .btn_margin {
        margin-left: 5px;
    }

</style>