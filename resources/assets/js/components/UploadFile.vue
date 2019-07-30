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
                </li>
            </ul>
            <div class="example-btn">
                <file-upload
                        class="btn btn-primary"
                        :post-action=post_action_url
                        :headers="headers"
                        extensions="gif,jpg,jpeg,png,webp,mp3"
                        accept="image/*,audio/*"
                        :multiple="false"
                        :size="1024 * 1024 * 10"
                        v-model="files"
                        @input-filter="inputFilter"
                        @input-file="inputFile"
                        ref="upload">
                    <i class="fa fa-plus"></i>
                    选择文件
                </file-upload>
                <button type="button" class="btn btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                    开始上传
                </button>
                <button type="button" class="btn btn-danger"  v-else @click.prevent="$refs.upload.active = false">
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
        name: "UploadFile",
        components: {
            FileUpload,
        },
        props : [
            'post_action', // 文件上传的地址
            'csrf_token', // token
        ],
        computed : {
            headers(){
                return {
                    'X-CSRF-TOKEN' : this.csrf_token,
                    'Authorization' : window.Laravel.api_token
                };
            }
        },
        data() {
            return {
                files: [],
                post_action_url : this.post_action,
                upload_active : this.upload_active_attr ? this.upload_active_attr :false, // 是否上传文件
            }
        },
        methods: {
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
                        console.log('success', newFile.success, newFile)
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