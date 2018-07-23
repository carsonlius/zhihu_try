<template>
    <div class="fa-2x">
        <span class="fa-layers fa-fw">
            <i class="icon-bell-alt"></i>
        <span class="fa-layers-counter" style="background:Tomato">{{ count }}</span>
        </span>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                count: ''
            };
        },
        methods: {
            // 初始化私信
           messageCount : function () {
               let url = '/api/message/unreadNum';
               let vm = this;
               this.$http.post(url, {}, {responseType : 'json'}).then(function (response) {
                   if (response.body.status === 0) {
                        vm.count = response.body.number_unread;
                   }
               });
           }

        },
        mounted: function () {
            // 每隔10分钟，更新一次私信的数量
            let vm = this;
            setInterval(function(){
                vm.messageCount();
            }, 3000);
        }
    }
</script>

<style scoped>

</style>