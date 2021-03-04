<template>
 <div>
     <!--delete modal confirmation-->
     <Modal :value="getDeleteModalObj.showDeleteModal" width="360"
     :mask-closable="false"
     :closable="false"
     >
         <p slot="header" style="color:#f60;text-align:center">
             <Icon type="ios-information-circle"></Icon>
             <span>Delete confirmation</span>
         </p>
         <div style="text-align:center">
             <p>Are you sure you want to delete ?</p>
         </div>
         <div slot="footer">
             <Button type="default" size="large"  @click="closeModal">Close</Button>
             <Button type="error" size="large" :loading="isDeleting" :disabled="isDeleting" @click="deleteRecord">Delete</Button>
         </div>
     </Modal>

 </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    data() {
      return {
          isDeleting: false,

      }
   },
   methods: {
       async deleteRecord() {
           this.isDeleting = true;
           const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl , this.getDeleteModalObj.data);
           if (res.status === 200) {
               this.successMessage('Success !');
               this.$store.commit('setDeleteModal',true);
           }else{
               this.errorMessage('An error occurred !')
               this.$store.commit('setDeleteModal', false)

           }
           this.isDeleting = false;

       },
       closeModal(){
           this.$store.commit('setDeleteModal', false)
       }
   },

   computed : {
       ...mapGetters(['getDeleteModalObj'])
   }

}
</script>

<style scoped>

</style>
