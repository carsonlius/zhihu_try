<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="tree-block">
                <v-select-tree :data='treeData3' :searchable="searchable" :multiple="multiple" :searchtext="searchtext"
                               :pleasechoosetext="pleasechoosetext"
                               v-model='initSelected'/>
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
        props: ['role_name', 'role_id'],
        data() {
            return {
                initSelected: [],
                searchable: true,
                multiple : true,
                searchtext: '搜索需要的节点',
                pleasechoosetext: '请选择当前角色分配权限',
                treeData3: []
            }
        },
        mounted : function(){
            this.iniParams();
        },
        methods : {
            iniParams : function() {
                let vm = this;
                this.$http.get('/api/permission/tree').then(function (response) {
                    if (response.body.status === 0) {
                        vm.treeData3 = response.body.list_permission;
                    }
                });
            },
            // 分配权限
            updatePermission : function () {
                let params = {

                };
                console.log(this.initSelected);
                console.log(params);
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