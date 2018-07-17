<template>
    <button class="btn btn-sm" :class="[followed ? 'btn-success' : 'btn-default']"  @click.prevent="followToggle">
        <span></span><span>{{ text_message }}</span>
    </button>
</template>

<script>
    export default {
        data: function () {
            return {
                text_message : ''
            };
        },
        methods: {
            // 开关是否关注
            followToggle: function () {
                let params = {
                    question_id: this.question_id
                };
                let vm = this;
                this.$http.post('/api/question/follow', params).then(function (response) {
                    if (response.body.status === 0) {
                        vm.followed = response.body.followed;
                        let obj_question_following = $('#question_following');
                        let number_question_following = parseInt(obj_question_following.text());
                        if (vm.followed) {
                            // 关注者增加一
                            obj_question_following.text(number_question_following + 1);
                        } else {
                            obj_question_following.text(number_question_following - 1);
                        }


                    }
                });

            },
            // 判断是否以及更关注该问题
            init_followed: function () {
                if (!parseInt(this.id)) {
                    this.followed = false;
                    return '';
                }

                let params = {
                    question_id: this.question_id
                };
                let vm = this;
                this.$http.post('/api/question/follower', params).then(function (response) {
                    if (response.data.status === 0) {
                        vm.followed = response.data.followed;
                    }
                });
            }

        },
        mounted: function () {
            //判断是否以及更关注该问题
            this.init_followed();
        },
        computed: {
            text_followed: function () {
                return this.followed ? '已关注' : '关注该问题';
            },
        }
    }
</script>

<style scoped>

</style>