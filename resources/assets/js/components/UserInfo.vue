<template>
    <div>
        <div class="btn-group btn-group-sm btn-margin_top">
            <a class="btn btn-default" href="#"><i class="icon-user"></i> {{ login_name }}</a>
            <a class="btn btn-default  dropdown-toggle" data-toggle="dropdown" href="#"><span class="icon-caret-down"></span>&nbsp;</a>
            <ul class="dropdown-menu ">
                <li><a href="/password"><i class="icon-pencil"></i> 重置密码</a></li>
                <li><a href="/setting"><i class="icon-cogs"></i> 用户设置 </a></li>
                <li class="divider"></li>
                <li><a @click.stop.prevent="logout"><i class="icon-ban-circle"></i> 退出</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserInfo",
        props: ['login_name'],
        methods: {
            logout: function () {
                console.log('退出登陆');
                this.$http.post('/logout').then(function (response) {
                    console.log(response);
                    if (response.status === 200 && response.ok === true) {
                        window.location.replace('/login');
                    }
                });
            }
        },
        data: function () {
            return {}
        }
    }
</script>

<style scoped>
    .btn-margin_top {
        margin-top:10px;
    }

</style>