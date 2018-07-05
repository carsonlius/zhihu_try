<template>
    <button class="btn btn-sm" :class="[voted ? 'btn-primary' : 'btn-default']" @click="followToggle" :title="text_title">
        <span class="glyphicon glyphicon-triangle-top" aria-hidden="true" v-if="voted"></span>
        <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" v-else></span>
        <span class="btn_margin">{{text_voted}}</span>
    </button>
</template>

<script>
    export default {
        props: ['answer_id', 'votes_count'],
        data: function () {
            return {
                voted: false,
                count : 0
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

                        // 调整点赞者的数量
                        if (response.data.voted) {
                            vm.count++;
                        } else {
                            vm.count--;
                        }

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

</style>