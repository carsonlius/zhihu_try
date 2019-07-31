<template>
    <div>
        <prompt-modal ref="prompt_cmp"></prompt-modal>
        <div class="panel-default panel">
            <div class="panel-heading">
                <span style="font-weight: bold;">期刊列表</span>
                <span class="pull-right">
                    <a href="/mini/periodicals/creation" class="btn btn-primary btn-xs">新建期刊</a>
                </span>
            </div>
            <div>
                <form @submit.stop.prevent="search" class="form-inline">
                    <div class="form-group search-item">
                        <v-select v-model="type" placeholder="期刊类型" :options="list_types"></v-select>
                    </div>
                    <div class="form-group search-item">
                        <v-select v-model="published" placeholder="请选状态" :options="list_published"></v-select>
                    </div>
                    <div class="form-group search-item">
                        <input type="text" class="form-control" v-model.trim="month" placeholder="期刊月份">
                    </div>

                    <div class="form-group search-item">
                        <input type="text" class="form-control" v-model.trim="periodical_index" placeholder="期刊期数">
                    </div>

                    <div class="form-group search-item">
                        <button type="submit" class="btn btn-primary btn-sm">查询</button>
                    </div>
                </form>
            </div>
        </div>
        <v-table
                is-vertical-resize
                :vertical-resize-offset='60'
                is-horizontal-resize
                style="width:100%"
                :multiple-sort="false"
                :min-height="600"
                even-bg-color="#f2f2f2"
                :columns="tableConfig.columns"
                :table-data="tableConfig.tableData"
                row-hover-color="#eee"
                row-click-color="#edf7ff"
                @sort-change="sortChange"
                :paging-index="(pageIndex-1)*pageSize"
                @on-custom-comp="customCompFunc"
        ></v-table>
        <div class="mtop-20"></div>
        <v-pagination @page-change="pageChange" @page-size-change="pageSizeChange" :total="total" :page-size="pageSize"
                      :layout="['total', 'prev', 'pager', 'next', 'sizer', 'jumper']"></v-pagination>
    </div>
</template>

<script>

    export default {
        name: "PeriodicalList",
        data() {
            return {
                periodical_index: '', // 第几期
                month: '', // 期刊月份
                published: '', // 是否已经发布
                type: '', // 期刊的类型
                list_types: [
                    {value: 'music', label: '音乐'},
                    {value: 'movie', label: '电影'},
                    {value: 'text', label: '句子'},
                ], // 类型列表

                list_published: [
                    {label: '待发布', value: 1},
                    {label: '已发布', value: 2},
                    {label: '撤回', value: 3},
                ], // 发布的状态

                tableDate: [],
                pageIndex: 1,
                total: 0,
                pageSize: 20,
                tableConfig: {
                    multipleSort: false,
                    tableData: [],
                    columns: [
                        {
                            field: 'id',
                            title: 'ID',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true
                        },
                        {
                            field: 'month',
                            title: '期刊月份',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true
                        },
                        {
                            field: 'periodical_index',
                            title: '期数',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true
                        },
                        {
                            field: 'img',
                            title: '封面',
                            width: 200,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true,
                            formatter(row_data) {
                                return `<image class="img-item" src="${row_data.img}"/>>`;
                            }
                        },
                        {
                            field: 'type',
                            title: '类型',
                            width: 100,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            isResize: true,
                            formatter(row_data) {
                                let list_types = {
                                    'music': '音乐',
                                    'movie' : '电影',
                                    'text' : '句子'
                                    };

                                return list_types[row_data.type];
                            }
                        },
                        {
                            field: 'custome-adv',
                            title: '操作',
                            width: 200,
                            titleAlign: 'center',
                            columnAlign: 'center',
                            componentName: 'operation',
                            isResize: true
                        }
                    ]
                }
            }
        },
        mounted: function () {
            this.search();
        },
        methods: {
            // 异常展示
            _errorShow(msg){
                this.$refs.prompt_cmp.open({
                    title: '提示',
                    body : msg
                });
            },

            // 生成条件
            _searchParams(){
                return {
                    periodical_index: this.periodical_index, // 第几期
                    month: this.month, // 期刊月份
                    published: this.published && Object.values(this.published).length !== 0  ? this.published.value: '', // 是否已经发布
                    type: this.type && Object.values(this.type).length !== 0  ? this.type.value: '', // 期刊的类型
                };
            },

            // 查询权限
            search() {
                // 参数
                let params = this._searchParams(),
                    url = '/api/mini/periodicals';

                axios.get(url, {params}).then(response=>{
                    console.log(response, '生成列表');
                    if (response.data.status === 0) {
                        this.tableDate = response.data.data;
                        this.getTableData();
                    } else {
                        this._errorShow(response.data.errors.msg);
                    }
                    
                }).catch(response=>{
                    console.log(response, '期刊获取异常');
                    this._errorShow('期刊获取异常');
                });

                // 发送请求

            },
            getTableData() {
                this.tableConfig.tableData = this.tableDate.slice((this.pageIndex - 1) * this.pageSize, (this.pageIndex) * this.pageSize)
            },
            pageChange(pageIndex) {

                this.pageIndex = pageIndex;
                this.getTableData();
                console.log(pageIndex)
            },
            pageSizeChange(pageSize) {

                this.pageIndex = 1;
                this.pageSize = pageSize;
                this.getTableData();
            },
            sortChange(params) {

                if (params.height.length > 0) {

                    this.tableConfig.tableData.sort(function (a, b) {

                        if (params.height === 'asc') {

                            return a.height - b.height;
                        } else if (params.height === 'desc') {

                            return b.height - a.height;
                        } else {

                            return 0;
                        }
                    });
                }
            },
            customCompFunc(params) {
                if (params.type === 'edit') {
                    let url_edit = '/mini/periodicals/' + params.rowData.id;
                    window.open(url_edit, '_blank');
                }
            }
        },

    }

    // 自定义列组件
    Vue.component('operation', {
        template: `<span><a href="javascript:;" class="btn btn-primary btn-xs" @click.stop.prevent="update(rowData,index)">编辑</a></span>`,
        props: {
            rowData: {
                type: Object
            },
            field: {
                type: String
            },
            index: {
                type: Number
            }
        },
        methods: {
            update() {

                // 参数根据业务场景随意构造
                let params = {type: 'edit', index: this.index, rowData: this.rowData};
                this.$emit('on-custom-comp', params);
            }
        }
    });
</script>

<style scoped>
    .img-item {
        width: 200px;
        height: 100px;
    }
    .search-item {
        margin : 20px  10px;
    }
</style>