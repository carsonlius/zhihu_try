<template>
    <a href=""  @click.prevent="jumpList">
        <div class="fa-2x">
        <span class="fa-layers fa-fw">
            <i class="icon-bell-alt fa-sm"></i>
            <span class="fa-layers-counter" style="background:Tomato" v-show="count">{{ count }}</span>
        </span>
        </div>
    </a>
</template>

<script>
    export default {
        data: function () {
            return {
                count: ''
            };
        },
        methods: {
            jumpList: function(){
                let url = '/notifications';
                window.open(url, '_blank');
            },
            // 初始化私信
            messageCount : function () {
                let url = '/api/notification/unreadNum';
                let vm = this;
                this.$http.get(url, {}, {responseType : 'json'}).then(function (response) {
                    if (response.body.status === 0) {
                        vm.count = response.body.number_unread;
                    }
                });
            },
            registerFollowingChannel : function(){
                let vm= this;
                window.Echo.private('App.User.' + window.Laravel.user_id).notification(function(notification){
                    console.log(notification);
                    vm.count = parseInt(vm.count) + 1;
                });
            },
        },
        mounted: function () {
            // 计算通知的数量
            this.messageCount();

            // 监听发起关注的通知channel
            this.registerFollowingChannel();
        }
    }
</script>

<style scoped>

</style>