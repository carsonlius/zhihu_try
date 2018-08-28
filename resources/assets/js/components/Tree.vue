<template>
    <div class="tree-block">
        <v-select-tree :data='treeData3' :searchable="searchable" :multiple="multiple" :searchtext="searchtext"
                       :pleasechoosetext="pleasechoosetext"
                       v-model='initSelected'/>
    </div>
</template>

<script>
    export default {
        name: 'Tree',
        data() {
            return {
                initSelected: ['node1'],
                searchable: true,
                multiple : true,
                searchtext: '搜索需要的节点',
                pleasechoosetext: '请选择当前用户可以访问的节点',
                treeData3: [{
                    title: 'node1',
                    expanded: true,
                    children: [{
                        title: 'node 1-1',
                        expanded: true,
                        children: [{
                            title: 'node 1-1-1'
                        }, {
                            title: 'node 1-1-2'
                        }, {
                            title: 'node 1-1-3'
                        }]
                    }]
                }]
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
                        // vm.treeData3 = response.body.list_permission;
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