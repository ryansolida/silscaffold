<template>
    <div>
        <VueTrix v-model="modelValue" ref="trixEditor" placeholder="Enter content" localStorage/>
    </div>
</template>

<script>
export default {
    props: ['field','item','index','model'],
    data: function(){
        return {
            modelValue: false
        }
    },
    mounted(){
        this.modelValue = this.$props.model
        this.$refs['trixEditor'].$el.querySelectorAll('.trix-button-group, .trix-content').forEach((e)=>{
            e.classList.add('bg-white');
            e.style.background = '';
        })
        
    }
}
</script>