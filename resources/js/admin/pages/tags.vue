<template>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">Tags     <Button v-if="allowWrite" @click="addModal=true"><Icon type="md-add" /> Add Tag</Button></p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Tag Name</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->


                        <!-- ITEMS -->
                        <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                            <td>{{tag.id}}</td>
                            <td class="_table_name">{{tag.tagName}}</td>
                            <td>{{tag.created_at}}</td>
                            <td>
                                <Button type="info" size="small" v-if="allowUpdate" @click="showEditModal(tag, i)">Edit</Button>
                                <Button type="error" size="small" v-if="allowDelete" @click="showDeletingModal(tag, i)" :loading="isDeleting">Delete</Button>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- tag adding modal-->
                <Modal
                    v-model="addModal"
                    title="Add new Tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.tagName" placeholder="Add Tag Name" />


                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add Tag'}}</Button>
                    </div>
                </Modal>
                <!-- tag editing modal-->
                <Modal
                    v-model="editModal"
                    title="Edit Tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.tagName" placeholder="Edit Tag Name" />


                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing' : 'Edit Tag'}}</Button>
                    </div>
                </Modal>
                <!--delete modal confirmation-->
                <deleteModal></deleteModal>

            </div>

        </div>
    </div>
</template>

<script>
import deleteModal from "../components/deleteModal";
import {mapGetters} from "vuex"
export default {
    data(){
        return {
            data: {
                tagName: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            tags: [],
            editData: {
                tagName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        }
    },
    methods: {
        async addTag() {

            const res = await this.callApi('post', 'app/create_tag', this.data)
            if (res.status === 201) {
                this.tags.unshift(res.data);
                this.successMessage('Tag added successfully !');
                this.addModal = false;
                this.data.tagName = '';
            } else {
                if(res.status === 422) {
                    if(res.data.errors.tagName){
                        this.errorMessage(res.data.errors.tagName[0])

                    }
                }else {
                    this.genericMessage('An error occured');
                }
            }


        },

        async editTag() {

            const res = await this.callApi('post', 'app/edit_tag', this.editData)
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
                this.tags.splice(this.deletingIndex,1);
                this.successMessage('Tag has been deleted !');
            }else{
                this.errorMessage('An error occurred !')
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(tag, i) {
            const deleteModalObj = {
                    showDeleteModal: true,
                    deleteUrl : 'app/delete_tag',
                    data: tag,
                    deleteIndex: i,
                    isDeleted: false,
            }
            this.$store.commit('setDeletingModalObj', deleteModalObj)
        }
    },

    async created() {
        const  res =  await this.callApi('get', 'app/get_tags')
        if(res.status === 200) {
            this.tags = res.data;
        }else{
            this.errorMessage('An error ocurred !');
        }
    },
    components: {
        deleteModal
    },
    computed: {
      ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            if(obj.isDeleted){
                this.tags.splice(obj.deletingIndex,1)
            }
        }

    }


}
</script>

<style scoped>

</style>
