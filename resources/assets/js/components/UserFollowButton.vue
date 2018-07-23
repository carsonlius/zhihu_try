<template>
    <button class="btn btn-sm" :class="[followed ? 'btn-success' : 'btn-default']" @click="followToggle">
        <font-awesome-icon icon="plus" v-if="!followed"/> {{ text_followed }}
    </button>
</template>

<script>
    export default {
        props: ['user', 'id'],
        data: function () {
            return {
                followed: false
            };
        },
        methods: {
            // 开关是否关注
            followToggle: function () {
                let params = {
                    user: this.user
                };
                let vm = this;
                this.$http.post('/api/user/follow', params).then(function (response) {
                    console.log(response);
                    if (response.data.status === 0) {
                        vm.followed = response.data.followed;
                        // 动态的调整关注者的数量
                        let count_obj = $('#following_count');
                        let following_count = parseInt(count_obj.text());
                        if (response.data.followed) {
                            // 关注者加1
                            count_obj.text(following_count+1);
                        } else {
                            // 关注者减1
                            count_obj.text(following_count-1);
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
                    params: {
                        user: this.user
                    },
                    responseType: 'json'
                };
                let vm = this;
                this.$http.get('/api/user/follower', params).then(function (response) {
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
                return this.followed ? '已关注' : '关注他';
            },
        }
    }
</script>

<style scoped>

</style>