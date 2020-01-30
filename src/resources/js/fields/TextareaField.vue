<template>
    <div>
        <textarea :ref="'input'+index" v-model="modelValue" @input="$emit('input',modelValue)" :placeholder="field.label" 
                                class="border-2 p-2 shadow-inner rounded w-full border-gray-400" style="min-height: 150px"></textarea>
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
    }
}
</script>