<template>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">admins     <Button @click="addModal=true"><Icon type="md-add" /> Add Admin</Button></p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->


                        <!-- ITEMS -->
                        <tr v-for="(admin, i) in admins" :key="i" v-if="admins.length">
                            <td>{{admin.id}}</td>
                            <td class="_table_name">{{admin.fullName}}</td>
                            <td>{{admin.email}}</td>
                            <td>{{admin.userType}}</td>

                            <td>{{admin.created_at}}</td>
                            <td>
                                <Button type="info" size="small" @click="showEditModal(admin, i)">Edit</Button>
                                <Button type="error" size="small"  @click="showDeletingModal(admin, i)" :loading="isDeleting">Delete</Button>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- admin adding modal-->
                <Modal
                    v-model="addModal"
                    title="Add new Admin"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="space">
                        <Input v-model="data.fullName" type="text" placeholder="Full name" />
                    </div>
                    <div class="space">
                        <Input v-model="data.email"  type="email" placeholder="Email" />
                    </div>
                    <div class="space">
                        <Input v-model="data.password" type="password" placeholder="Password" />
                    </div>
                    <div class="space">
                        <Select v-model="data.userType" placeholder="Select Admin Type">
                            <Option value="Admin"></Option>
                            <Option value="Editor"></Option>
                        </Select>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding' : 'Add Admin'}}</Button>
                    </div>
                </Modal>
                <!-- admin editing modal-->
                <Modal
                    v-model="editModal"
                    title="Edit Admin"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="space">
                        <Input v-model="editData.fullName" type="text" placeholder="Full name" />
                    </div>
                    <div class="space">
                        <Input v-model="editData.email"  type="email" placeholder="Email" />
                    </div>
                    <div class="space">
                        <Input v-model="editData.password" type="password" placeholder="Password" />
                    </div>
                    <div class="space">
                        <Select v-model="editData.userType" placeholder="Select Admin Type">
                            <Option value="Admin"></Option>
                            <Option value="Editor"></Option>
                        </Select>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button type="primary" @click="editAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing' : 'Edit Admin'}}</Button>
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
                fullName: '',
                email: '',
                password: '',
                userType: 'Admin'
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            admins: [],
            editData: {
                fullName: '',
                email: '',
                password: '',
                userType: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        }
    },
    methods: {
        async addAdmin() {
            if(this.data.fullName.trim() == '')
                return this.errorMessage('Full Name is required!')
            if(this.data.email.trim() == '')
                return this.errorMessage('Email is required!')
            if(this.data.password.trim() == '')
                return this.errorMessage('Password is required!')
            if(this.data.userType.trim() == '')
                return this.errorMessage('User Type is required!')
            const res = await this.callApi('post', 'app/create_admin', this.data)
            if (res.status === 201) {
                this.admins.unshift(res.data);
                this.successMessage('Admin added successfully !');
                this.addModal = false;
                this.data.fullName = '';
            } else {
                if(res.status === 422) {
                    for(let i in res.data.errors) {
                        this.errorMessage(res.data.errors[i][0]);
                    }
                }else {
                    this.genericMessage('An error occured');
                }
            }
        },

        async editAdmin() {
            if(this.editData.fullName.trim() == '')
                return this.errorMessage('Full Name is required!')
            if(this.editData.email.trim() == '')
                return this.errorMessage('Email is required!')
            if(this.editData.userType.trim() == '')
                return this.errorMessage('User Type is required!')

            const res = await this.callApi('post', 'app/edit_admin', this.editData)
            if (res.status === 200) {
                this.admins[this.index] = this.data;
                this.successMessage('Admin edited successfully !');
                this.editModal = false;
            } else {
                if(res.status === 422) {
                    for(let i in res.data.errors) {
                        this.errorMessage(res.data.errors[i][0]);
                    }
                }else {
                    this.errorMessage('An error occurred !');
                }
            }


        },
        showEditModal(admin, index) {
            let obj = {
                id: admin.id,
                fullName : admin.fullName,
                email : admin.email,
                password : admin.password,
                userType : admin.userType
            }
            this.editData = obj;
            this.editModal =true;
            this.index = index;
        },

        async deleteAdmin() {
            this.isDeleting = true;
            const res = await this.callApi('post', 'app/delete_admin', this.deleteItem)
            if (res.status === 200) {
                //remove from array one element starting from index
                this.admins.splice(this.deletingIndex,1);
                this.successMessage('Admin has been deleted !');
            }else{
                this.errorMessage('An error occurred !')
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(admin, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl : 'app/delete_admin',
                data: admin,
                deleteIndex: i,
                isDeleted: false,
            }
            this.$store.commit('setDeletingModalObj', deleteModalObj)
        }
    },

    async created() {
        const  res =  await this.callApi('get', 'app/get_admins')
        if(res.status === 200) {
            this.admins = res.data;
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
                this.admins.splice(obj.deletingIndex,1)
            }
        }

    }


}
</script>

<style scoped>

</style>


<!--https://www.youtube.com/watch?v=KfjEZzisQTo-->
