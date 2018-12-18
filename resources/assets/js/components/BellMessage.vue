<template>
    <a href=""  @click.prevent="jumpList">
        <div class="fa-2x">
        <span class="fa-layers fa-fw">
            <i class="icon-comments fa-sm"></i>
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
                let url = '/message/inbox';
                window.open(url, '_blank');
            },
            // 初始化私信
           messageCount : function () {
               let url = '/api/message/unreadNum';
               let vm = this;
               this.$http.post(url, {}, {responseType : 'json'}).then(function (response) {
                   if (response.body.status === 0) {
                        vm.count = response.body.number_unread;
                   }
               });
           },
            messageCreateChannel : function () {
                let vm = this;
                window.Echo.private('message-to-counter.' + window.Laravel.user_id).listen('MessageCreateEvent', function(e){
                    console.log('监听到了counter自动增长的channel', e);
                    vm.count = parseInt(vm.count) + 1;

                });
            }

        },
        mounted: function () {
            // 当前有多少个message未读
            this.messageCount();

            // 注册channel监听私信功能
            this.messageCreateChannel();


            // 每隔1分钟，更新一次私信的数量
            let vm = this;
            // setInterval(function(){
            //     vm.messageCount();
            // }, 60000);
        }
    }
</script>

<style scoped>

</style>