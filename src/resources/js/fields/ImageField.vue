<template>
    <div>
        <div v-if="modelValue"><img :src="modelValue.thumbnail_url"></div>
        <a href="javascript:;" @click="openMedia(field.name)">Update Image</a>
    </div>
</template>

<script>
export default {
    props: ['field','item','index','model'],
    mounted(){
        //alert(window.cloudinaryConfig);
        this.cloudinaryWidget = cloudinary.createUploadWidget(window.cloudinaryConfig, (error, result) => { 
            if (!error && result && result.event === "success") { 
                this.widgetCallback(result.info);//console.log('Done! Here is the image info: ', result.info); 
            }
        });
        this.modelValue = this.$props.model
    },
    data: function(){
        return {
            modelValue: false
        }
    },
    methods:{
        openMedia(fieldName){
            this.widgetCallback = (info)=>{
                //this.item.fieldName = 
                //this.item[fieldName] = info;
                this.modelValue = info;
                this.$emit('input',this.modelValue);
            }
            this.cloudinaryWidget.open();
        }
    }
}
</script>