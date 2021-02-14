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



    }
}
