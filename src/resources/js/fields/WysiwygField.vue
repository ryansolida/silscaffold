<template>
    <div>
        <VueTrix @input="updateParent" v-model="modelValue" srcContent="Test" ref="trixEditor" placeholder="Enter content" localStorage/>
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
    },
    methods: {
        updateParent(){
            setTimeout(()=>{
                if ( this.modelValue == '' ){
                    this.modelValue = '<div></div>';
                }
                this.$emit('input',this.modelValue)
                
            },100);
        }
    }
}
</script>