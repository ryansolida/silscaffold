<template>
    <div>
        External 
        
        <component :is="embedComp"></component>
    </div>
</template>

<script>
export default {
    props: ['content'],
    data(){
        return {
            compomentEmbedData: false,
            embedComp: false,
            defaultContent: "Yo"
        }
    },
    mounted(){
        console.log(this.$props);
        this.embedComp = Vue.component('embedComp', (resolve, reject)=>{
        //setTimeout(function () {
            // Pass the component definition to the resolve callback
            resolve({
                template: "<div>"+this.$props.content+"</div>"
            })
        //}, 1000)
        })
    },
    created(){
        var self = this;
        //this.embedComp = 
    }
}
</script>