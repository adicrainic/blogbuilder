<template>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">Roles     <Button @click="addModal=true" v-if="allowWrite"><Icon type="md-add" /> Add Role</Button></p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Is Admin</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->


                        <!-- ITEMS -->
                        <tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
                            <td>{{role.id}}</td>
                            <td class="_table_name">{{role.name}}</td>
                            <td>{{role.isAdmin}}</td>
                            <td>{{role.created_at}}</td>
                            <td>
                                <Button type="info" size="small" @click="showEditModal(role, i)" v-if="allowUpdate">Edit</Button>
                                <Button type="error" size="small"  @click="showDeletingModal(role, i)" :loading="isDeleting" v-if="allowDelete">Delete</Button>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- role adding modal-->
                <Modal
                    v-model="addModal"
                    title="Add new role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.name" placeholder="Add role Name" />


                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="primary" @click="addRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add role'}}</Button>
                    </div>
                </Modal>
                <!-- role editing modal-->
                <Modal
                    v-model="editModal"
                    title="Edit role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.name" placeholder="Edit role Name" />


                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="primary" @click="editRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing' : 'Edit role'}}</Button>
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
                name: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            roles: [],
            editData: {
                name: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        }
    },
    methods: {
        async addRole() {

            const res = await this.callApi('post', 'app/create_role', this.data)
            if (res.status === 201) {
                this.roles.unshift(res.data);
                this.successMessage('role added successfully !');
                this.addModal = false;
                this.data.name = '';
            } else {
                if(res.status === 422) {
                    if(res.data.errors.name){
                        this.errorMessage(res.data.errors.name[0])

                    }
                }else {
                    this.genericMessage('An error occured');
                }
            }


        },

        async editRole() {

            const res = await this.callApi('post', 'app/edit_role', this.editData)
            if (res.status === 200) {
                this.roles[this.index].name = this.editData.name;
                this.successMessage('role edited successfully !');
                this.editModal = false;
            } else {
                if(res.status === 422) {
                    if(res.data.errors.name){
                        this.errorMessage(res.data.errors.name[0])

                    }
                }else {
                    this.genericMessage('An error occurred !');
                }
            }


        },
        showEditModal(role, index) {
            let obj = {
                id: role.id,
                name : role.name

            }
            this.editData = obj;
            this.editModal =true;
            this.index = index;
        },

        async deleterole() {
            this.isDeleting = true;
            const res = await this.callApi('post', 'app/delete_role', this.deleteItem)
            if (res.status === 200) {
                //remove from array one element starting from index
                this.roles.splice(this.deletingIndex,1);
                this.successMessage('Role has been deleted !');
            }else{
                this.errorMessage('An error occurred !')
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(role, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl : 'app/delete_role',
                data: role,
                deleteIndex: i,
                isDeleted: false,
            }
            this.$store.commit('setDeletingModalObj', deleteModalObj)
        }
    },

    async created() {
        const  res =  await this.callApi('get', 'app/get_roles')
        if(res.status === 200) {
            this.roles = res.data;
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
                this.roles.splice(obj.deletingIndex,1)
            }
        }

    }


}
</script>

<style scoped>

</style>
