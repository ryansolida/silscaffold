<template>
    <div>
        <select :ref="'input'+index" v-model="modelValue" @input="window.setTimeout(()=>$emit('input',modelValue,1));" class="h-10 border-2 border-gray-400 bg-white inner-shadow w-full">
            <option v-for="(text,val) in field.options" :value="val">
                &nbsp;&nbsp;{{text}}&nbsp;&nbsp;
            </option>
        </select>
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