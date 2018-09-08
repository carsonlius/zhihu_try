<template>
    <div class="col-sm-8 col-sm-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="font-bold">{{ user_name }}的角色分配</h4>
            </div>
            <div class="panel-body">
                <prompt-modal ref="prompt_modal"></prompt-modal>
                <v-select :options="list_options" multiple v-model="list_role" label="name"></v-select>
            </div>
            <div class="panel-footer col-sm-6 col-sm-offset-3">
                <button @click.prevent="updatePermission" class="btn btn-primary btn-sm">更新配置</button>
                <div class="pull-right">
                    <a href="" @click.stop.prevent="backUrl" class="btn btn-info btn-sm">返回</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'UserRoleEdit',
        props: ['user_name', 'user_id'],
        data() {
            return {
                list_options : [],
                list_role : []
            }
        },
        created : function(){
            // 初始化select组件
            this.initParams();
        },
        methods : {
            // 返回上一页
            backUrl : function(){
                window.location.href = '/Role/user';
            },
            closeModel : function(){
                this.$refs.modal_prompt.close();
            },
            // 初始化参数
            initParams : function(){
                let vm = this;
                // 初始化select组件
                this.$http.get('/api/role', {responseType: 'json'}).then(function (response) {
                    if (response.body.status === 0) {
                        vm.list_options = response.body.list_roles;
                        console.log('初始化select组件',vm.list_options, typeof vm.list_options);
                    } else {
                        vm.$refs.prompt_modal.open({
                            title : '用户角色编辑提示',
                            body: response.body.msg
                        });
                    }
                });

                // 初始化用户选中的参数
                this.$http.get('/api/role/show', {params:{user_id : this.user_id}, responseType:'json'}).then(function (response) {
                    console.log(response);
                    if (response.body.status === 0) {
                        vm.list_role = response.body.list_roles;
                        console.log('选中的脚色', vm.list_role, typeof vm.list_role);
                    } else {
                        vm.$refs.prompt_modal.open({
                            title : '用户角色编辑提示',
                            body: response.body.msg
                        });
                    }
                });
            },
            // 分配权限
            updatePermission : function () {
                let vm = this;
                let list_ids = [];
                Object.keys(this.list_role).forEach(function (key) {
                    let item = vm.list_role[key];
                    console.log(item);
                    list_ids.push(item.id);
                });
                console.log(list_ids);

                this.$http.patch('/api/user/role',{list_role_ids : list_ids, user_id: this.user_id}, {responseType: 'json'})
                    .then(function (response) {
                        console.log(response);
                        if (response.body.status === 0) {
                            vm.$refs.prompt_modal.open({
                                title : '用户更新角色提示',
                                body : '更新成功',
                                btn_name_left : '留在本页面',
                                btn_name_right : '返回用户角色列表',
                                btn_url_right : '/Role/user'
                            });

                        } else {
                            vm.$refs.prompt_modal.open({
                                title : '用户更新角色提示',
                                body : '更新失败, ' +  response.body.msg
                            });
                        }

                });
            }
        }
    }
</script>
<style>
    .tree-block {
        float: left;
        width: 33%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px dotted #ccccdd;
        overflow: auto;
        min-height: 500px;
    }
</style>