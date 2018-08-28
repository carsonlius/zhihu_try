<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="font-bold">{{ role_name }}的权限分配</h4>
            <a class="btn btn-primary btn-sm" href="/Role">返回列表</a>
        </div>
        <div class="panel-body">
            <prompt-modal ref="prompt_modal"></prompt-modal>
            <div class="tree-block">
                <v-select-tree :data='treeData3' :searchable="searchable" :draggable="draggable" :dragAfterExpanded="dragAfterExpanded"
                               :multiple="multiple" :searchtext="searchtext"
                               :pleasechoosetext="pleasechoosetext"
                               v-model.lazy='initSelected'/>
            </div>
        </div>
        <div class="panel-footer">
            <button @click.prevent="updatePermission" class="btn btn-primary btn-sm">更新配置</button>
        </div>
    </div>

</template>

<script>
    export default {
        name: 'RolePermission',
        props: ['role_name', 'role_id', 'permission_attached'],
        data() {
            return {
                icon_type : 'success',
                msg_error : '更新节点成功',
                initSelected : ['点击查看节点分配'],
                dragAfterExpanded : false,
                searchable: true,
                draggable : false,
                multiple : true,
                searchtext: '搜索需要的节点',
                pleasechoosetext: '请选择当前角色分配权限',
                treeData3: []
            }
        },
        created : function(){
            this.initSelected = JSON.parse(this.permission_attached);
            this.initTree();
        },
        methods : {
            closeModel : function(){
                this.$refs.modal_prompt.close();
            },
            // 初始化组件树
            initTree : function(){
                let vm = this;
                this.$http.get('/api/permission/tree_permission', {params:{role_id: this.role_id}, responseType:'json'} ).then(function (response) {
                    if (response.body.status === 0) {
                        vm.treeData3 = response.body.list_permission;
                    }
                });
            },
            // 分配权限
            updatePermission : function () {
                let params = {
                    list_permission_name : this.initSelected,
                    role_id: this.role_id
                };
                console.log(params);
                // 更新角色权限
                this.$http.post('/api/role/permission', params, {responseType: 'json'}).then(function (response) {
                   console.log('分配角色', response);
                   if (response.body.status === 0) {
                       this.$refs.prompt_modal.open({
                           title : '权限分配提示',
                           body : '权限分配成功',
                           btn_name_right : '返回角色列表',
                           btn_name_left : '留在本页面',
                           btn_url_right : '/Role'
                       });
                   } else {
                       this.$refs.prompt_modal.open({
                           title : '权限分配提示',
                           body : '分配权限失败',
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