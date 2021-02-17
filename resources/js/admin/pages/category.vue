<template>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">Categories     <Button @click="addModal=true"><Icon type="md-add" /> Add Category</Button></p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->


                        <!-- ITEMS -->
                        <tr v-for="(category, i) in categories" :key="i" v-if="categorys.length">
                            <td>{{category.id}}</td>
                            <td class="_table_name">{{category.categoryName}}</td>
                            <td>{{category.created_at}}</td>
                            <td>
                                <Button type="info" size="small" @click="showEditModal(category, i)">Edit</Button>
                                <Button type="error" size="small"  @click="showDeletingModal(category, i)" :loading="isDeleting">Delete</Button>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- category adding modal-->
                <Modal
                    v-model="addModal"
                    title="Add new Category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.categoryName" placeholder="Add Category Name" />
                    <div class="space"></div>
                    <Upload
                        type="drag"
                        action="app/upload"
                        :headers ="{'x-csrf-token' : token}"
                        :on-success="handleSuccess"
<!--                        :format="['jpg','jpeg','png']"-->
                        :max-size="2048"
<!--                        :on-format-error="handleFormatError"-->
                        :on-exceeded-size="handleMaxSize"
                    >


                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add Category'}}</Button>
                    </div>
                </Modal>
                <!-- category editing modal-->
                <Modal
                    v-model="editModal"
                    title="Edit category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.categoryName" placeholder="Edit category Name" />


                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing' : 'Edit category'}}</Button>
                    </div>
                </Modal>
                <!--delete modal confirmation-->
                <Modal v-model="showDeleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure you want to delete this category ?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCategory">Delete</Button>
                    </div>
                </Modal>

            </div>

        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            data: {
                categoryName: '',
                iconImage:''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            categories: [],
            editData: {
                categoryName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            token: ''
        }
    },
    methods: {
        async addCategory() {

            const res = await this.callApi('post', 'app/create_category', this.data)
            if (res.status === 201) {
                this.ca.unshift(res.data);
                this.successMessage('Category added successfully !');
                this.addModal = false;
                this.data.categoryName = '';
            } else {
                if(res.status === 422) {
                    if(res.data.errors.categoryName){
                        this.errorMessage(res.data.errors.categoryName[0])

                    }
                }else {
                    this.genericMessage('An error occurred');
                }
            }


        },

        async editCategory() {

            const res = await this.callApi('post', 'app/edit_category', this.editData)
            if (res.status === 200) {
                this.tags[this.index].tagName = this.editData.tagName;
                this.successMessage('Tag edited successfully !');
                this.editModal = false;
            } else {
                if(res.status === 422) {
                    if(res.data.errors.tagName){
                        this.errorMessage(res.data.errors.tagName[0])

                    }
                }else {
                    this.genericMessage('An error occurred !');
                }
            }


        },
        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName : tag.tagName

            }
            this.editData = obj;
            this.editModal =true;
            this.index = index;
        },

        async deleteTag() {
            this.isDeleting = true;
            const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
            if (res.status === 200) {
                //remove from array one element starting from index
                this.tags.splice(this.i,1);
                this.successMessage('Tag has been deleted !');
            }else{
                this.errorMessage('An error occurred !')
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(tag, i) {
            this.deleteItem = tag;
            this.deletingIndex = i;
            this.showDeleteModal = true;
        },

        handleSuccess (res, file) {
           this.data.iconImage = res
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },



    },

    async created() {
        this.token = window.Laravel.csrfToken
        const  res =  await this.callApi('get', 'app/get_tags')
        if(res.status === 200) {
            this.tags = res.data;
        }else{
            this.errorMessage('An error ocurred !');
        }
    }

}
</script>

<style scoped>

</style>


<!--https://www.youtube.com/watch?v=KfjEZzisQTo-->
