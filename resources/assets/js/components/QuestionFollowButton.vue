<template>
    <button class="btn btn-sm" :class="[followed ? 'btn-success' : 'btn-default']" v-text="text_followed" @click="followToggle"></button>
</template>

<script>
    export default {
        props: ['question_id', 'id'],
        data : function(){
            return {
                followed : false
            };
        },
        methods:{
            // 开关是否关注
            followToggle : function () {
                let params = {
                    question_id : this.question_id
                };
                let vm = this;
                this.$http.post('/api/question/follow', params).then(function(response){
                    if (response.data.status === 0) {
                        vm.followed = response.data.followed;
                    }
                });

            },
            // 判断是否以及更关注该问题
            init_followed : function () {
                if (!parseInt(this.id)) {
                    this.followed = false;
                    return '';
                }

                let params = {
                    question_id : this.question_id
                };
                let vm = this;
                this.$http.post('/api/question/follower', params).then(function(response){
                    if (response.data.status === 0) {
                        vm.followed = response.data.followed;
                    }
                });
            }

        },
        mounted : function () {
            //判断是否以及更关注该问题
            this.init_followed();
        },
        computed : {
            text_followed : function() {
                return this.followed ? '已关注' : '关注该问题';
            },
        }
    }
</script>

<style scoped>

</style>