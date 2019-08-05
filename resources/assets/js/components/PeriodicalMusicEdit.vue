<template>
    <div class="modal fade" id="periodical-prompt" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" v-if="title">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title font-bold">
                        {{ title }}
                    </h4>
                </div>

                <div class="modal-body text-center periodical-container">
                    <img :src="periodical.img" alt="封面" class="cover-img">
                    <file-single-uploader uploaded_type="uploadedmusic"
                                          @exception="onException" :post_action="upload_url" @uploadedmusic="onUploadedMusic" :size="0"></file-single-uploader>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" :class="btn_class_right" v-if="btn_name_right" @click.prevent="update()" data-dismiss="modal">{{ btn_name_right }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PeriodicalPrompt",
        data : function(){
            return {
                modal_show : false,
                title : '',
                btn_name_right : '确定', // 左侧按钮的显示的文字,
                btn_class_right : 'btn btn-secondary btn-primary', // 右侧按钮的类

                periodical : {}, // 期刊
                music_url : '', // 音乐地址
                // 上传相关的操作
                upload_url : '/api/file', // 文件上传的地址
            }
        },
        methods : {
            // 修改音乐
            onUploadedMusic(param){
                console.log(param, '上传的文件的状态发生了变化');
                this.music_url = param.file_name;
            },

            // 初始化参数
            open: function(params){
                this.title = !! params.title ? params.title : this.title;
                this.periodical = params.periodical;
                $('#periodical-prompt').modal('toggle');
            },

            // 监听异常情况
            onException(params){
                console.log(params, '异常事件');
                this.$emit('exception', params);
            },

            // 更新
            update(){
                let params  = {
                    id : this.periodical.id,
                    music_url : this.music_url
                };

                axios.patch('/api/mini/periodicals/music', params).then(response=>{
                    console.log(response, '更新音乐期刊');
                    if (response.data.status === 0) {
                        // this.$emit('exception', {msg : '期刊更新成功'});
                        setTimeout(function(){
                            window.location.href = '/mini/periodicals';
                        }, 6000);
                    } else {

                    }
                }).catch(response=>{

                });
            }
        }
    }
</script>
<style>
    .font-bold {
        font-weight: bold;
    }
    .periodical-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .cover-img {
        width: 300px;
        height: 200px;
    }
</style>
