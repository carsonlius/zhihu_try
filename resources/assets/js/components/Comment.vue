<template>
    <div>
        <button class="btn btn-sm btn-default" :data-target="dialogId" data-toggle="modal" @click="iniComments"  v-text="text"></button>
        <div class="modal fade" :id="dialog_id" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            评论列表
                        </h4>
                    </div>

                    <div class="modal-body" v-if="comments.length">
                        <div>
                            <div class="media" v-for="comment in comments">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object" :src="comment.user.avatar" alt="">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{ comment.user.name}}</h4>
                                    {{ comment.body}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <div class="form-group text-center">
                            <span v-show="errors.has(data_vv_name)" class="alert-danger">{{ errors.first(data_vv_name) }}</span>
                            <input type="text" class="form-control"  v-model="body"  :data-vv-name="data_vv_name" data-vv-as="评论内容" placeholder="评论内容" v-validate.initial="'required'">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary btn-sm" @click.prevent="store">评论</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // id是question_id或者answer_id
        // type 是answer或者question
        // count是当前model的计数
        props: ['id', 'type', 'count'],
        data: function () {
            return {
                body: '',
                success_status: false,
                comments : [],
            };
        },
        computed : {
            dialog_id : function() {
                return 'comments-dialog-' + this.type + '-' + this.id;
            },
            dialogId : function () {
                return '#' + this.dialog_id;
            },
            text : function(){
                return this.count + '评论';
            },
            data_vv_name : function(){
                return this.dialog_id + '-body';
            }
        },
        methods: {
            // 获取当前问题或者答案的评论列表
            iniComments : function() {
                let url ='/api/comments/' + this.id + '/'+ this.type;
                let params = {
                    responseType : 'json'
                };
                let vm = this;
                this.$http.get(url, params).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.comments = (response.body.list_comments);
                    }
                });

            },
            // 发送私信
            store: function () {
                let url = '/api/comments/store';
                let params = {
                      body : this.body,
                      type : this.type,
                      id : this.id,
                };
                let vm = this;
                this.$http.post(url, params, {jsonType : 'json'}).then(function (response) {
                    console.log(response);
                    vm.success_status = response.body.status === 0;
                    if (response.body.status === 0) {
                        // 新增列表加入到当前的体系中
                        vm.comments.push(response.body.result_store);
                        vm.body = '';
                    }
                }, function (response) {
                    console.log(response);
                })
            },
        }
    }
</script>

<style scoped>
    img {
        width: 64px; height: 64px;
    }
</style>