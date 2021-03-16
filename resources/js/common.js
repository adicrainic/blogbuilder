import { mapGetters } from 'vuex'
export  default {
    data() {
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try  {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            }catch (e) {
                console.log(e.response);
                return e.response;
            }
        },

        //notifications
        infoMessage (desc, title='Info') {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        successMessage (desc, title='Success') {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warningMessage (desc, title='Warning') {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        errorMessage (desc, title='Error') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        //generic
        genericMessage (desc = "Something went wrong!", title='Error') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        checkUserPermission(key) {
            if(!this.userPermission) return true


            let isPermitted = false
            for(let d of this.userPermission) {
                if(this.$route.name === d.name) {
                    if(d[key])
                    {
                        isPermitted = true;
                        break;
                    }
                }
            }

            return isPermitted
        }
    },

    computed: {
        ...mapGetters(
            {
                "userPermission" : "getUserPermission"
            }
        ),


        allowRead() {
            return this.checkUserPermission('read');
        },
        allowWrite() {
            return this.checkUserPermission('write');

        },
        allowUpdate() {
            return this.checkUserPermission('update');

        },
        allowDelete(){
            return this.checkUserPermission('delete');
        }

    }
}
