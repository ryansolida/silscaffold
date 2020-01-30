<template>
    <div>
        <div :class="entries.length>0?'mb-8':''">
            <div v-for="(entry,index) in entries" :key="'iter'+index" class="my-3 bg-white rounded-lg shadow p-4">
                <ScaffoldField class="my-3" v-for="(subfield,subindex) in field.fields" @input="entries[index][subfield.name] = $event; $emit('input',entries)" :model="entries[index][subfield.name]" :field="subfield" :item="item" :index="subindex" :key="index+''+subindex+''+componentKey" />
                <a href="javascript:;" @click="removeIteration(index)" class="rounded bg-gray-800 text-white px-3 py-2 shadow text-sm font-semibold">Remove</a>    
            </div>
        </div>
        <div>
            <a href="javascript:;" @click="addNew" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold">Add New</a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['field','item','index','model'],
    data: function(){
        return {
            entries: [],//this.$props.item[this.$props.field.name]
            componentKey: 0,
        }
    },
    mounted(){
        var fieldData = this.$props.model;
        if ( fieldData != undefined ){
            this.entries = fieldData;
        }
    },
    methods: {
        addNew(){
            var newEntry = {};
            console.log(this.$props.field.fields);
            this.$props.field.fields.forEach((f)=>{
                newEntry[f.name] = ''
            });
            this.entries.push(newEntry);
            this.componentKey++;
        },
        updateSubfield(index,subfieldName,data){
            this.entries[index][subfieldName] = data
            console.log(this.entries[index]);
        },
        removeIteration(index){
            if ( confirm("Are you sure you want to remove this item?") ){
                this.entries.splice(index,1);
                this.componentKey++;
            }
        }
    }
}
</script>