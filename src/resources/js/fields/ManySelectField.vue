<template>
    <div>
        <select v-model="newItem"  class="h-10 border-2 border-gray-400 bg-white inner-shadow w-full">
            <option v-for="option in field.options" :value="option" :key="'option'+option.id">{{option[field.relationship.option_display_field]}}</option>
        </select>
        <a href="javascript:;" @click="add(newItem)" class="bg-white border-2 border-gray-400 px-2 py-1 shadow rounded my-4 inline-block font-semibold">Add Selected</a>   
        <div class="flex w-full flex-wrap">
            <div class="px-2 py-1 mr-2 my-2 rounded bg-white shadow cursor-pointer flex items-center" @click="remove(index)" v-for="(selectedItem,index) in item[field.name]">{{selectedItem[field.relationship.option_display_field]}} <span class="font-bold ml-2 mr-1 text-gray-600 bg-gray-400 font-bold rounded-full w-5 h-5 text-xs leading-none flex justify-center items-center">X</span></div>
        </div>
        
    </div>
</template>

<script>
export default {
    props: ['field','item','index','model'],
    data: function(){
        return {
            modelValue: false,
            newItem: false
        }
    },
    mounted(){

        this.modelValue = this.$props.model
        if ( this.modelValue == '' || this.modelValue == undefined ){
            this.modelValue = [];
        }
    },
    methods: {
        add(val){
            if ( val == undefined ){
                return
            }
            this.modelValue.push(val);
            this.$emit('input',this.modelValue);
        },
        remove(index){
            this.modelValue.splice(index,1);
        }
    }
}
</script>