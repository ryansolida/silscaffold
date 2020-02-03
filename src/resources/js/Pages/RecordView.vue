<template>
    <div>
        <div class="w-5/6 mx-auto">
            <div class="text-3xl font-bold border-b mb-8">
                {{item[scaffold.display_title_field]}} <span class="text-xl text-gray-500 font-normal">{{scaffold.display_name}}</span>
            </div>
            <form @submit.prevent="submit">
                
                <div v-for="(field,index) in fields" class="flex p-4 py-8 border-b-2 border-gray-400" :class="index%2==0?'bg-gray-300':'bg-gray-100'">

                    <div class="w-1/6 text-right pr-8 h-auto flex justify-end items-center font-semibold text-gray-800">
                        {{field.label}}
                    </div>
                    <div class="w-5/6">
                        <ScaffoldField :field="field" @input="item[field.name] = $event" :model="item[field.name]" :item="item" :index="index"  />
                        
                       
                    </div>
                </div>
                
                
                
                
                <div class="mt-8">
                    <button type="submit" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold">SUBMIT</button>
                    <div class="mt-4"><a href="javascript:;" class="text-red-600 text-sm" @click="goBack">Go Back</a></div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: ['item','slug','scaffold','fields'],
    
    mounted(){
        //this.$refs.input0.focus();
        //this.$refs['input0'][0].focus();

        

        console.log(this.item);
    },
    methods: {
        goBack(){
            if ( confirm("Are you sure you want to go back and lose your changes?") ){
                window.location="/admin/"+this.slug+"/list";
            }
        },
        submit(){
            var id = '';
            if ( this.item.id !== undefined ){
                id = this.item.id;
                this.$inertia.post('/admin/'+this.slug+'/post/'+id, this.item)
            } else{
                this.$inertia.post('/admin/'+this.slug+'/post', this.item)
            }

            
            
        }
    }
}
</script>