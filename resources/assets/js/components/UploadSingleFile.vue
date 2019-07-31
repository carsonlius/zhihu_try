<template>
    <div class="example-simple">
        <div class="upload">
            <ul>
                <li v-for="(file, index) in files" :key="file.id">
                    <span>{{file.name}}</span> -
                    <span>{{file.size | formatSize}}</span> -
                    <span v-if="file.error">{{file.error}}</span>
                    <span v-else-if="file.success">success</span>
                    <span v-else-if="file.active">active</span>
                    <span v-else-if="file.active">active</span>
                    <span v-else></span>
                    <span class="btn btn-primary btn-xs"  @click.prevent="remove(file)">清除</span>
                </li>
            </ul>
            <div class="example-btn">
                <file-upload
                        class="btn btn-primary"
                        :post-action=post_action_url
                        :headers="headers"
                        :extensions="extensions_control"
                        :accept="accept_control"
                        :multiple="false"
                        :size="size_control"
                        v-model="files"
                        @input-filter="inputFilter"
                        @input-file="inputFile"
                        ref="upload">
                    <i class="fa fa-plus"></i>
                    选择文件
                </file-upload>
                <button type="button" class="btn btn-danger"  v-if="$refs.upload && $refs.upload.active" @click.prevent="$refs.upload.active = false">
                    <i class="fa fa-stop" aria-hidden="true"></i>
                    取消上传
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import FileUpload from 'vue-upload-component';
    export default {
        name: "UploadSingleFile",
        components: {
            FileUpload,
        },
        props : [
            'post_action', // 文件上传的地址
            'size', // 允许上传的最大字节
            'accept', // 允许上传的类型
            'uploaded_type', // 上传类型 用来抛出上传完成得信号
        ],
        computed : {
            // 设置 header
            headers(){
                return {
                    'Authorization' : window.Laravel.api_token
                };
            },
        },
        data() {
            return {
                files: [],
                size_control : this.size ? this.size : 0, // 允许上传的最大字节
                accept_control : this.accept ? this.accept : 'image/*,audio/*',
                post_action_url : this.post_action, // 上传的url
                extensions_control : 'gif,jpg,jpeg,png,webp,mp3,mp4', // 允许的文件后缀
                determine_show : false, // 是否上传成功
                file_name: '', // 文件名称
                uploaded_event_type : this.uploaded_type ? this.uploaded_type : 'uploaded',
            }
        },
        mounted(){
            console.log(this.$refs.upload);
        },
        methods: {
            // 清除上传得文件
            remove(file){
                this.$refs.upload.remove(file);
                axios.delete('/api/file', {data : {file_path: this.file_name}}).then((response)=>{
                    console.log('删除已经上传得文件', response);
                    if (response.data.status === 0) {
                        this.$emit(this.uploaded_event_type, {file_name : '', action : 'deleted', success :true});
                    } else {
                        this.$emit('exception', {
                            title : '提示',
                            body : response.errors.msg
                        });
                    }
                }).catch(response=>{
                    console.log('删除已经上传得文件异常', response);
                    this.$emit('exception', {
                        title : '提示',
                        body : '清除上传文件失败'
                    });
                });
            },

            inputFilter(newFile, oldFile, prevent) {
                if (newFile && !oldFile) {
                    // Before adding a file
                    // 添加文件前
                    // Filter system files or hide files
                    // 过滤系统文件 和隐藏文件
                    if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
                        return prevent()
                    }
                    // Filter php html js file
                    // 过滤 php html js 文件
                    if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                        return prevent()
                    }
                }
            },
            // this.$emit('uploaded', {msg : 'uploaded success!'});

            inputFile(newFile, oldFile) {
                if (newFile && !oldFile) {
                    // 添加文件
                }

                // 更新文件
                if (newFile && oldFile) {
                    // 开始上传
                    if (newFile.active !== oldFile.active) {
                        console.log('Start upload', newFile.active, newFile)

                        // 限定最小字节
                        // if (newFile.size >= 0 && newFile.size < 100 * 1024) {
                        //     newFile = this.$refs.upload.update(newFile, {error: 'size'})
                        // }
                    }

                    // 上传进度
                    if (newFile.progress !== oldFile.progress) {
                        console.log('progress', newFile.progress, newFile)
                    }

                    // 上传错误
                    if (newFile.error !== oldFile.error) {
                        console.log('error', newFile.error, newFile)
                    }

                    // 上传成功
                    if (newFile.success !== oldFile.success) {
                        console.log('success', newFile.success, newFile);
                        if (newFile.response.status === 0) {
                            this.determine_show = true;
                            this.file_name = newFile.response.data.file_name;
                            let file_url = newFile.response.data.file_qiniu;
                            this.$emit(this.uploaded_event_type, {file_name : file_url, action : 'uploaded', success :true})
                        }
                    }
                }

                if (!newFile && oldFile) {
                    // 删除文件

                    // 自动删除 服务器上的文件
                    if (oldFile.success && oldFile.response.id) {
                        // $.ajax({
                        //   type: 'DELETE',
                        //   url: '/file/delete?id=' + oldFile.response.id,
                        // });
                        console.log(oldFile, '删除旧得文件');
                    }
                }

                // 自动上传
                if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
                    if (!this.$refs.upload.active) {
                        // axios.post('/api/file/music').then(response=>{
                        //     console.log('请求success', response);
                        // }).catch(response=>{
                        //     console.log('请求fail', response);
                        // });

                        this.$refs.upload.active = true;
                    }
                }
            }
        }

    }
</script>

<style scoped>
    .example-simple label.btn {
        margin-bottom: 0;
        margin-right: 1rem;
    }

</style>