<template>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">Create Blog</p>
                <div class="_overflow _table_div blog_editor">
                    <editor
                        ref="editor"
                        autofocus
                        holder-id="codex-editor"
                        save-button-id="save-button"
                        :init-data="initData"
                        @save="onSave"
                        :config="{
                            tools: {
                              header: require('@editorjs/header')
                            }
                        }"
                    />
                </div>
                    <div class="_input_field">
                        <Input type="text" placeholder="title"></Input>
                    </div>
                    <div class="_input_field">
                        <Button  @click="save">Save</Button>
                    </div>
                </div>
            </div>


    </div>
</template>

<script>
export default {
    data(){
        return {
            config: {
                tools: {
                },

                image: {
                    // Like in https://github.com/editor-js/image#config-params
                    field: 'image',
                    types: 'image/*',
                },
            },
            data: {

            },
            initData: {

            }
        }
    },
    methods : {
        onSave(){

        },
        async save(){
            const res = await this.$refs.editor.save

        }
    }
}
</script>

<style>
.blog_editor {
    width: 717px;
    margin-left: 160px;
    padding: 4px 7px;
    font-size: 14px;
    border: 1px solid #dcdee2;
    border-radius: 4px;
    color: #515a6e;
    background-color: #fff;
    background-image: none;
    z-index:  -1;
}
.blog_editor:hover {
    border: 1px solid #57a3f3;
}
._input_field{
    margin: 20px 0 20px 160px;
    width: 717px;
}

</style>
