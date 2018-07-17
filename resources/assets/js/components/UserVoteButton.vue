<template>
    <div>
        <div class="alert alert-success message_span text-center" v-if="text_status">
            <strong>{{text_show}}</strong>
        </div>
        <button class="btn btn-sm tip" :class="[voted ? 'btn-primary' : 'btn-default']" @click="followToggle" :title="text_title">
            <span class="glyphicon glyphicon-triangle-top" aria-hidden="true" v-if="voted"></span>
            <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" v-else></span>
            <span class="btn_margin">{{text_voted}}</span>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['answer_id', 'votes_count'],
        data: function () {
            return {
                voted: false,
                count : 0,
                text_show : '',
                text_status : false
            };
        },
        created : function(){
          this.count = this.votes_count;
        },
        methods: {
            // 开关是否关注
            followToggle: function () {
                let params = {
                    answer_id: this.answer_id
                };
                let vm = this;
                this.$http.post('/api/answer/vote', params).then(function (response) {
                    if (response.data.status === 0) {
                        vm.voted = response.data.voted;

                        // 展示提示框，说明本次动作的意义
                        vm.text_status = true;

                        // 调整点赞者的数量
                        if (response.data.voted) {
                            vm.count++;
                            vm.text_show = '点赞成功';
                        } else {
                            vm.count--;
                            vm.text_show = '取消点赞';
                        }
                        // 两秒钟后 取消提示框
                        setTimeout(function(){
                            vm.text_status = false;
                        }, 2000);
                    }
                });

            },
            // 判断是否以及更关注该问题
            init_voted: function () {
                let params = {
                    responseType: 'json'
                };
                let url = '/api/answer/' + this.answer_id + '/votes/users';
                let vm = this;

                this.$http.get(url, params).then(function (response) {
                    if (response.data.status === 0) {
                        vm.voted = response.data.voted;
                    }
                });
            }

        },
        mounted: function () {
            //判断是否以及更关注该问题
            this.voted = !! this.count;
            this.init_voted();
        },
        computed: {
            text_voted: function () {
                return this.count;
            },
            text_title : function () {
                return this.voted ? '已经点赞' : '未点赞';
            }
        }
    }
</script>

<style scoped>
    .btn_margin{
        margin-left: 5px;
    }
    .message_span{
        /*width: 20%;*/
    }
</style>