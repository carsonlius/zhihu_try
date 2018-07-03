<template>
    <button class="btn btn-sm" :class="[voted ? 'btn-primary' : 'btn-default']" v-text="text_voted"
            @click="followToggle"></button>
</template>

<script>
    export default {
        props: ['answer_id', 'votes_count'],
        data: function () {
            return {
                voted: false
            };
        },
        methods: {
            // 开关是否关注
            followToggle: function () {
                let params = {
                    answer_id: this.answer_id
                };
                let vm = this;
                this.$http.post('/api/answer/vote', params).then(function (response) {
                    console.log(response);
                    if (response.data.status === 0) {
                        vm.voted = response.data.voted;

                        // 调整点赞者的数量
                        if (response.data.voted) {
                            vm.votes_count++;
                        } else {
                            vm.votes_count--;
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
                    console.log(response);
                    if (response.data.status === 0) {
                        vm.voted = response.data.voted;
                    }
                });
            }

        },
        mounted: function () {
            //判断是否以及更关注该问题
            this.init_voted();
        },
        computed: {
            text_voted: function () {
                return this.votes_count;
            },
        }
    }
</script>

<style scoped>

</style>